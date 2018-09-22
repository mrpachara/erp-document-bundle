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
   * @param GoodsReceipt|null $goodsreceipt
   */
  public function __construct(GoodsReceipt $goodsreceipt = null) {
      parent::__construct($goodsreceipt);
  }

  public function getStatusChanged() {
    return $this->statusChanged;
  }

  public function setStatusChanged(PurchaseOrderDetailStatusChanged $statusChanged) {
    $this->statusChanged = $statusChanged;

    return $this;
  }
}
