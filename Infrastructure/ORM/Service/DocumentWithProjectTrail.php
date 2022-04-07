<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\QueryHelper;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\EmployeeQuery;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\EmployeeQueryService;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQuery;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQueryService;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

trait DocumentWithProjectTrail {
    /** @var QueryHelper */
    protected $qh;

    /** @var ProjectQuery */
    protected $projectQuery;

    /** @var EmployeeQuery */
    protected $employeeQuery;

    /** @required */
    public function setProjectQuery(ProjectQueryService $projectQuery)
    {
        $this->projectQuery = $projectQuery;
    }

    /** @required */
    public function setEmployeeQuery(EmployeeQueryService $employeeQuery)
    {
        $this->employeeQuery = $employeeQuery;
    }

    abstract public function createQueryBuilder(string $alias) : QueryBuilder;
    abstract public function applySearchFilter(QueryBuilder $qb, array $params, string $alias, &$context = null) : QueryBuilder;

    public function assignWithEmployeesFilter(
        QueryBuilder $qb,
        string $alias,
        $employees,
        array $types
    ) : QueryBuilder
    {
        if(empty($types)) throw new \InvalidArgumentException("\$types could not be empty.");

        $expr = $qb->expr();
        $orX = $expr->orX();

        if(in_array(ServiceInterface::OWNER, $types)) {
            $requestersVar = "{$alias}_requesters";

            $orX->add(
                $expr->in("{$alias}.requester", ":{$requestersVar}")
            );

            $qb->setParameter($requestersVar, $employees);
        }

        if(in_array(ServiceInterface::WORKER, $types)) {
            $projectAlias = "{$alias}_project_for_employees";
            $projectOfWorkersQb = $this->projectQuery->memberOfWorkersQueryBuilder($projectAlias, $employees);

            $projectOfWorkersQb->andWhere(
                $expr->eq("{$alias}.project", $projectAlias)
            );

            $orX->add(
                $expr->exists(
                    $projectOfWorkersQb->getDql()
                )
            );

            $this->qh->appendParameters($qb, $projectOfWorkersQb->getParameters());
        }

        $qb->andWhere($orX);

        return $qb;
    }

    public function assignWithUserFilter(
        QueryBuilder $qb,
        string $alias,
        SystemUser $user,
        array $types
    ) : QueryBuilder
    {
        $employees = $this->employeeQuery->findByThing($user->getThing());
        return $this->assignWithEmployeesFilter($qb, $alias, $employees, $types);
    }

    public function withEmployeesQueryBuilder(
        string $alias,
        $employees,
        array $types
    ) : QueryBuilder
    {
        $qb = $this->createQueryBuilder($alias);
        $qb = $this->assignWithEmployeesFilter($qb, $alias, $employees, $types);

        return $qb;
    }

    public function searchWithEmployees($params, $employees, array $types, ?array &$context = null)
    {
        if(empty($employees)) return [];

        $alias = 'doc';
        $qb = $this->withEmployeesQueryBuilder($alias, $employees, $types);
        $qb = $this->applySearchFilter($qb, $params, $alias, $context);

        return $this->qh->execute($qb->getQuery(), $params, $context);
    }

    public function searchWithUser($params, SystemUser $user, array $types, ?array &$context = null)
    {
        $employees = $this->employeeQuery->findByThing($user->getThing());
        return $this->searchWithEmployees($params, $employees, $types, $context);
    }

    function findWithUser($id, SystemUser $user, array $types, ?int $lockMode = null)
    {
        $employees = $this->employeeQuery->findByThing($user->getThing());

        if(empty($employees)) return null;

        $alias = 'doc';
        $qb = $this->withEmployeesQueryBuilder($alias, $employees, $types);
        $idVar = "{$alias}_id";

        $expr = $qb->expr();
        $qb
            ->andWhere($expr->eq("{$alias}.id", ":{$idVar}"))
            ->setParameter($idVar, $id)
        ;

        $query = $qb->getQuery();
        if($lockMode !== null) {
            $query->setLockMode($lockMode);
        }

        return $query->getOneOrNullResult();
    }

    function findWith($id, ?array $params = null)
    {
        if(empty($params) || !key_exists('document-with-user', $params)) {
            return parent::findWith($id, $params);
        }

        $withUser = $params['document-with-user'];
        $lock = array_merge([
            'mode' => null,
            'version' => null,
        ], ($params && key_exists('lock', $params))? $params['lock'] : []);
        return $this->findWithUser($id, $withUser['user'], $withUser['types'], $lock['mode']);
    }

    function searchProjectsWithEmployees($params, $employees, ?array &$context = null)
    {
        $alias = 'allowed_project';
        $qb = $this->projectQuery->memberOfWorkersQueryBuilder($alias, $employees);
        $qb = $this->projectQuery->applySearchFilter($qb, $params, $alias, $context);

        return $this->qh->execute($qb->getQuery(), $params, $context);
    }

    function searchProjectsWithUser($params, SystemUser $user, ?array &$context = null)
    {
        $employees = $this->employeeQuery->findByThing($user->getThing());
        return $this->searchProjectsWithEmployees($params, $employees, $context);
    }

    function searchProjectWith($params, ?array &$context = null)
    {
        if(array_key_exists('document-with-user', $params)) {
            $user = $params['document-with-user']['user'];
            unset($params['document-with-user']);
            return $this->searchProjectsWithUser($params, $user, $context);
        } else {
            return $this->projectQuery->search($params, $context);
        }
    }
}
