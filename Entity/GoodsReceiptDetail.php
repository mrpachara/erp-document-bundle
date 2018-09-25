<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class GoodsReceiptDetail extends PurchaseDetail {
  /**
   * @var PurchaseOrderDetailStatusChanged
   */
  protected $statusChanged;

  /**
   * constructor
   *
   * @param GoodsReceipt|null $purchase
   */
  public function __construct(GoodsReceipt $purchase = null) {
      parent::__construct($purchase);
  }

  public function getStatusChanged() {
    return $this->statusChanged;
  }

  public function setStatusChanged(PurchaseOrderDetailStatusChanged $statusChanged) {
    $this->statusChanged = $statusChanged;

    return $this;
  }
}
