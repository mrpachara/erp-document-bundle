<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * PurchaseOrder Query (CQRS)
 */
interface QuotationQuery extends PurchaseQuery{
    public function searchRequestForQuotationRemain(array $params, array &$context = null);
    
    public function getRequestForQuotationRemain($id);
}
