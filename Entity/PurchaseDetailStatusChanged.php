<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var PurchaseDetail
   */
  protected $detail;

  /**
   * @var string
   */
  protected $type;

  /**
   * constructor
   */
  public function __construct() { }

  public function getPurchaseDetail() {
    return $this->detail;
  }

  public function setPurchaseDetail(?PurchaseDetail $purchaseDetail) {
    $this->detail = $purchaseDetail;

    return $this;
  }

  public function getType()
  {
      return $this->type;
  }
}
