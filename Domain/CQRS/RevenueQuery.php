<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Revenue Query (CQRS)
 */
interface RevenueQuery extends IncomeQuery{
  public function searchTaxInvoiceRemain(array $params, array &$context = null);

  public function getTaxInvoiceRemain($id, ?array $params = null);
}
