<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Quotation Query (CQRS)
 */
interface QuotationQuery extends PurchaseFinanceQuery
{
    public function searchRequestForQuotationRemain(array $params, array &$context = null);

    public function getRequestForQuotationRemain($id);
}
