<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseOrderDetail extends PurchaseDetail {
  /**
   * @var PurchaseRequestDetailStatusChanged
   */
  protected $statusChanged;

  /**
   * constructor
   *
   * @param PurchaseOrder|null $purchase
   */
  public function __construct(PurchaseOrder $purchase = null) {
    parent::__construct($purchase);
  }

  public function getStatusChanged() {
    return $this->statusChanged;
  }

  public function setStatusChanged(PurchaseRequestDetailStatusChanged $statusChanged) {
    $this->statusChanged = $statusChanged;

    return $this;
  }
}
