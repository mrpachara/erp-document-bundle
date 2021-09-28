<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\DocumentBundle\Entity\Document;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * Interface of Document that has project (CQRS)
 */
interface DocumentWithProjectInterface
{
    const OWNER = 1;
    const WORKER = 2;

    /**
     * Search by related given employees.
     *
     * @param array $params
     * @param Employee[] $employees
     * @param int[] $types
     * @param ?array &$context
     *
     * @return Document[]
     */
    function searchWithEmployees($params, $employees, array $types, ?array &$context = null);

    /**
     * Search by projects has the given user.
     * This will get employees from the given user then searchWithSomeEmployees()
     * @see searchWithSomeEmployees
     *
     * @param array $params
     * @param SystemUser $user
     * @param int[] $types
     * @param ?array &$context
     *
     * @return Document[]
     */
    function searchWithUser($params, SystemUser $user, array $types, ?array &$context = null);

    /**
     * Find from identifier for the given use.
     *
     * @param mixed $id Identifier.
     * @param int[] $types
     *
     * @return Document
     */
    function findWithUser($id, SystemUser $user, array $types);
}
