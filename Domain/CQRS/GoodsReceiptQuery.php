<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * GoodsReceipt Query (CQRS)
 */
interface GoodsReceiptQuery extends PurchaseQuery{
  public function searchPurchaseOrderRemain(array $params, array &$context = null);

  public function getPurchaseOrderRemain($id);
}
