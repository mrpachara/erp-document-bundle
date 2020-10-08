<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class TaxInvoiceDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var TaxInvoiceDetail
   */
    protected $taxInvoiceDetail;

  /**
   * @var bool
   */
  protected $removed;

  /**
   * constructor
   */
  public function __construct() { }

  public function getTaxInvoiceDetail() {
    return $this->taxInvoiceDetail;
  }

  public function setTaxInvoiceDetail(?TaxInvoiceDetail $taxInvoiceDetail) {
    $this->taxInvoiceDetail = $taxInvoiceDetail;

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
