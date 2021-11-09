<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class ExpenseDetail extends PurchaseDetail {
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
}
