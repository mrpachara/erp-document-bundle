<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseOrderDetail extends PurchaseDetail {
  /**
   * constructor
   *
   * @param PurchaseOrder|null $purchase
   */
  public function __construct(PurchaseOrder $purchase = null) {
    parent::__construct($purchase);
  }
}
