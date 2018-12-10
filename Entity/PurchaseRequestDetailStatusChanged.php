<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseRequestDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var PurchaseRequestDetail
   */
  protected $purchaseRequestDetail;

  /**
   * @var bool
   */
  protected $removed;

  /**
   * constructor
   */
  public function __construct() { }

  public function getPurchaseRequestDetail() {
    return $this->purchaseRequestDetail;
  }

  public function setPurchaseRequestDetail(?PurchaseRequestDetail $purchaseRequestDetail) {
    $this->purchaseRequestDetail = $purchaseRequestDetail;

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
