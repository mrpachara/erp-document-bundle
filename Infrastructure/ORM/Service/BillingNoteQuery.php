<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\BillingNoteQuery as QueryInterface;

abstract class BillingNoteQuery extends IncomeQuery implements QueryInterface
{
    /** @var DeliveryNoteQueryService */
    protected $deliveryNoteQueryService;

    public function searchDeliveryNoteRemain(array $params, array &$context = null)
    {
        return $this->deliveryNoteQueryService->searchRemain($params, $context);
    }

    public function getDeliveryNoteRemain($id, ?array $params = null)
    {
        return $this->deliveryNoteQueryService->getRemain($id, $params);
    }
}
