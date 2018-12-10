<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * TaxInvoice Query (CQRS)
 */
interface TaxInvoiceQuery extends IncomeQuery{
  public function searchBillingNoteRemain(array $params, array &$context = null);

  public function getBillingNoteRemain($id);
}
