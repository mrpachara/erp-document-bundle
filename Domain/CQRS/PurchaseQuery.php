<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * Purchase Query (CQRS)
 */
interface PurchaseQuery extends DocumentQuery
{
    /**
     * Search by project has some of the given workers.
     *
     * @param array $params
     * @param Employee[] $workers
     * @param ?array &$context
     *
     * @return Project[]
     */
    function searchWithSomeWorkers($params, $workers, ?array &$context = null);

    /**
     * Search by projects has the given user.
     * This will get workers from the given user then searchWithSomeWorkers()
     * @see searchWithSomeWorkers
     *
     * @param array $params
     * @param SystemUser $user
     * @param ?array &$context
     *
     * @return Project[]
     */
    function searchWithUser($params, SystemUser $user, ?array &$context = null);
}
