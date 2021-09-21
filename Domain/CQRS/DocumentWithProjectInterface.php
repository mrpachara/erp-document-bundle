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
    /**
     * Search by related given employees.
     *
     * @param array $params
     * @param Employee[] $employees
     * @param ?array &$context
     *
     * @return Document[]
     */
    function searchWithEmployees($params, $employees, ?array &$context = null);

    /**
     * Search by projects has the given user.
     * This will get employees from the given user then searchWithSomeEmployees()
     * @see searchWithSomeEmployees
     *
     * @param array $params
     * @param SystemUser $user
     * @param ?array &$context
     *
     * @return Document[]
     */
    function searchWithUser($params, SystemUser $user, ?array &$context = null);

    /**
     * Find from identifier for the given use.
     *
     * @param mixed $id Identifier.
     *
     * @return Document
     */
    function findWithUser($id, SystemUser $user);
}
