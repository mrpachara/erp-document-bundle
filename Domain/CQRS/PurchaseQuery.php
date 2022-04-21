<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

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
