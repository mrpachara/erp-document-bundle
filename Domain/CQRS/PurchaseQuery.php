<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * Purchase Query (CQRS)
 */
interface PurchaseQuery extends DocumentQuery, DocumentWithProjectInterface
{
    /**
     * Search for Remain Purchase.
     */
    public function searchRemain(array $params, array &$context = null);

    /**
     * Get Remain Purchase.
     */
    public function getRemain($id, ?array $params = null);
}
