<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class GoodsReceiptDetail extends PurchaseDetail {
  /**
   * constructor
   *
   * @param GoodsReceipt|null $purchase
   */
  public function __construct(GoodsReceipt $purchase = null) {
      parent::__construct($purchase);
  }
}
