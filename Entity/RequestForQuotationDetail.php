<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class RequestForQuotationDetail extends PurchaseDetail {
  /**
   * @var PurchaseRequestDetail
   */
  protected $purchaseRequstDetail;

  /** @var string */
  protected $stockQuantity;

  public function getStockQuantity()
  {
      return $this->stockQuantity;
  }

  public function setStockQuantity($stockQuantity)
  {
      $this->stockQuantity = $stockQuantity;

      return $this;
  }  

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
