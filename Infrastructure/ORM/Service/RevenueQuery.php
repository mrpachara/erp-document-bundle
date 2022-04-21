<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\RevenueQuery as QueryInterface;

abstract class RevenueQuery extends IncomeQuery implements QueryInterface
{
    /** @var TaxInvoiceQueryService */
    protected $taxInvoiceQueryService;

    public function searchTaxInvoiceRemain(array $params, array &$context = null)
    {
        return $this->taxInvoiceQueryService->searchRemain($params, $context);
    }

    public function getTaxInvoiceRemain($id, ?array $params = null)
    {
        return $this->taxInvoiceQueryService->getRemain($id, $params);
    }
}
