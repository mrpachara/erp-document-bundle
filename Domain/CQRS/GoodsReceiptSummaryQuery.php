<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Document Query (CQRS)
 */
interface GoodsReceiptSummaryQuery {
    public function getGoodsReceiptSummary($id);
}
