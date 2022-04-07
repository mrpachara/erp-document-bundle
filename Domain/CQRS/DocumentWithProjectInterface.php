<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\DocumentBundle\Entity\Document;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\Project;
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
     * This will get employees from the given user then searchWithEmployees()
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
    function findWithUser($id, SystemUser $user, array $types, ?int $lockMode = null);

    /**
     * Search projects by related given employees.
     *
     * @param array $params
     * @param Employee[] $employees
     * @param ?array &$context
     *
     * @return Project[]
     */
    function searchProjectsWithEmployees($params, $employees, ?array &$context = null);

    /**
     * Search projects having the given user.
     * This will get employees from the given user then searchProjectsWithEmployees()
     * @see searchProjectsWithEmployees
     *
     * @param array $params
     * @param SystemUser $user
     * @param ?array &$context
     *
     * @return Project[]
     */
    function searchProjectsWithUser($params, SystemUser $user, ?array &$context = null);

    /**
     * Search projects with given parameters.
     * If parameters contain 'document-with-user',
     * search allowed project.
     *
     * @param array $params
     * @param ?array &$context
     *
     * @return Project[]
     */
    function searchProjectWith($params, ?array &$context = null);
}
