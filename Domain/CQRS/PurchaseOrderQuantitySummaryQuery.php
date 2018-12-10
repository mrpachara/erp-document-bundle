<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Document Query (CQRS)
 */
interface PurchaseOrderQuantitySummaryQuery {
    public function getPurchaseOrderQuantitySummary($id, $excepts = null);

}
