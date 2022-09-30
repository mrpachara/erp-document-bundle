<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * PurchaseOrder Query (CQRS)
 */
interface PurchaseOrderQuery extends PurchaseFinanceQuery
{
    public function searchPurchaseRequestRemain(array $params, array &$context = null);

    public function getPurchaseRequestRemain($id, ?array $params = null);
}
