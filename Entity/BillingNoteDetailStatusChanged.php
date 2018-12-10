<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class BillingNoteDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var BillingNoteDetail
   */
    protected $billingNoteDetail;

  /**
   * @var bool
   */
  protected $removed;

  /**
   * constructor
   */
  public function __construct() { }

  public function getBillingNoteDetail() {
      return $this->BillingNoteDetail;
  }

  public function setBillingNoteDetail(?BillingNoteDetail $billingNoteDetail) {
    $this->billingNoteDetail = $billingNoteDetail;

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
