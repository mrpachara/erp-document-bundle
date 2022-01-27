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

interface DocumentWithProject {
     public function assignWithEmployeesFilter(
        QueryBuilder $qb,
        string $alias,
        $employees,
        array $types
    ) : QueryBuilder;

    public function assignWithUserFilter(
        QueryBuilder $qb,
        string $alias,
        SystemUser $user,
        array $types
    ) : QueryBuilder;

    public function withEmployeesQueryBuilder(
        string $alias,
        $employees,
        array $types
    ) : QueryBuilder;

    public function searchWithEmployees($params, $employees, array $types, ?array &$context = null);

    public function searchWithUser($params, SystemUser $user, array $types, ?array &$context = null);

    function findWithUser($id, SystemUser $user, array $types, ?int $lockMode = null);
}
