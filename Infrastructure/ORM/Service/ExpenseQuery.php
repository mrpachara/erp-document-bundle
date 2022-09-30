<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ExpenseQuery as QueryInterface;

abstract class ExpenseQuery extends PurchaseFinanceQuery implements QueryInterface
{
    /** @var PurchaseOrderQueryService */
    protected $purchaseOrderQueryService;

    public function searchPurchaseOrderExpenseRemain(array $params, array &$context = null)
    {
        return $this->purchaseOrderQueryService->searchRemain($params, $context);
    }

    public function getPurchaseOrderExpenseRemain($id, ?array $params = null)
    {
        return $this->purchaseOrderQueryService->getRemain($id, $params);
    }
}
