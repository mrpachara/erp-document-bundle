<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class ExpenseDetail extends PurchaseDetail {
  /**
   * @var PurchaseOrderDetailStatusChanged
   */
  protected $statusChanged;

  /**
   * constructor
   *
   * @param Expense|null $purchase
   */
  public function __construct(Expense $purchase = null) {
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
