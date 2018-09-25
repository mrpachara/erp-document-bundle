<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseOrderDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var PurchaseOrderDetail
   */
    protected $purchaseOrderDetail;

  /**
   * @var bool
   */
  protected $removed;

  /**
   * constructor
   */
  public function __construct() { }

  public function getPurchaseOrderDetail() {
      return $this->PurchaseOrderDetail;
  }

  public function setPurchaseOrderDetail(?PurchaseOrderDetail $purchaseOrderDetail) {
      $this->purchaseOrderDetail = $purchaseOrderDetail;

    return $this;
  }

  public function getRemoved() {
    return $this->removed;
  }

  public function setRemoved($removed) {
    $this->removed = $removed;

    return $this;
  }
}
