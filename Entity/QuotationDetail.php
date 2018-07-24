<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class QuotationDetail extends PurchaseDetail {
  /**
   * @var PurchaseRequestDetail
   */
  protected $purchaseRequstDetail;


  /**
   * constructor
   *
   * @param PurchaseOrder|null $purchase
   */
  public function __construct(PurchaseOrder $purchase = null) {
    parent::__construct($purchase);
  }

  public function getPurchaseRequestDetail() {
    return $this->purchaseRequstDetail;
  }

  public function setPurchaseRequestDetail(PurchaseRequestDetail $purchaseRequstDetail) {
    $this->purchaseRequstDetail = $purchaseRequstDetail;

    return $this;
  }
}
