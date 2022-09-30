<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Expense Query (CQRS)
 */
interface ExpenseQuery extends PurchaseFinanceQuery
{
    public function searchPurchaseOrderExpenseRemain(array $params, array &$context = null);

    public function getPurchaseOrderExpenseRemain($id, ?array $params = null);
}
