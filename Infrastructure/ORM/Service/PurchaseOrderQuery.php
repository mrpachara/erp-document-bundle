<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery as QueryInterface;

abstract class PurchaseOrderQuery extends PurchaseQuery implements QueryInterface
{
    /** @var PurchaseRequestQueryService */
    protected $purchaseRequestQueryService;

    public function searchPurchaseRequestRemain(array $params, array &$context = null)
    {
        return $this->purchaseRequestQueryService->searchRemain($params, $context);
    }

    public function getPurchaseRequestRemain($id, ?array $params = null)
    {
        return $this->purchaseRequestQueryService->getRemain($id, $params);
    }
}
