<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * PurchaseOrder Query (CQRS)
 */
interface RequestForQuotationQuery extends  DocumentQuery{
  public function searchPurchaseRequestRemain(array $params, array &$context = null);

  public function getPurchaseRequestRemain($id);
}
