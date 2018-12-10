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
   * is finish
   * @var bool
   */
  protected $finish;

  /**
   * constructor
   */
  public function __construct() { }

  public function getPurchaseOrderDetail() {
      return $this->purchaseOrderDetail;
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
  
    public function getFinish()
    {
        return $this->finish;
    }

    public function setFinish($finish)
    {
        $this->finish = $finish;
        
        return $this;
    }

  
  
}
