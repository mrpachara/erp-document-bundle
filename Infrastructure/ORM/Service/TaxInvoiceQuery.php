<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\TaxInvoiceQuery as QueryInterface;

abstract class TaxInvoiceQuery extends IncomeQuery implements QueryInterface
{
    /** @var BillingNoteQueryService */
    protected $billingNoteQueryService;

    public function searchBillingNoteRemain(array $params, array &$context = null)
    {
        return $this->billingNoteQueryService->searchRemain($params, $context);
    }

    public function getBillingNoteRemain($id, ?array $params = null)
    {
        return $this->billingNoteQueryService->getRemain($id, $params);
    }
}
