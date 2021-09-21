<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\QueryHelperService;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\EmployeeQuery;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\EmployeeQueryService;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQuery;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQueryService;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

trait DocumentWithProjectTrail {
    /** @var QueryHelperService */
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

    public function projectWithSomeWorkersQueryBuilder(
        string $alias,
        $workers
    ) : QueryBuilder
    {
        $projectAlias = "{$alias}_project_for_workers";
        $projectWorkerQb = $this->projectQuery->someWorkersQueryBuilder($projectAlias, $workers);

        $qb = $this->createQueryBuilder($alias);
        $projectWorkerQb->andWhere(
            $qb->expr()->eq("{$alias}.project", $projectAlias)
        );

        $qb
            ->andWhere(
                $qb->expr()->exists(
                    $projectWorkerQb->getDql()
                )
            )
            ->setParameters($projectWorkerQb->getParameters())
        ;

        return $qb;
    }

    public function searchWithSomeWorkers($params, $workers, ?array &$context = null)
    {
        if(empty($workers)) return [];

        $alias = 'doc';
        $qb = $this->projectWithSomeWorkersQueryBuilder($alias, $workers);
        $qb = $this->applySearchFilter($qb, $params, $alias, $context);

        return $this->qh->execute($qb->getQuery(), $params, $context);
    }

    public function searchWithUser($params, SystemUser $user, ?array &$context = null)
    {
        $workers = $this->employeeQuery->findByThing($user->getThing());
        return $this->searchWithSomeWorkers($params, $workers, $context);
    }
}
