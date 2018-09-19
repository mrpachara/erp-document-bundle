<?php

namespace Erp\Bundle\DocumentBundle\Entity;
use Erp\Bundle\MasterBundle\Entity\CostItem;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqData;

abstract class RequestForQuotationDetail {
  /**
   * @var PurchaseRequestDetail
   */
  protected $purchaseRequestDetail;

  /** @var string */
  protected $stockQuantity;

  
  /**
   * @var string
   */
  private $id;
 
  /**
   * costItem
   *
   * @var CostItem
   */
  protected $costItem;
  
  /**
   * requestForQuotation
   * @var requestForQuotation
   */
  protected $requestForQuotation;
  
  /**
   * quantity
   *
   * @var string
   */
  protected $quantity;
  
  
  /**
   * boqData
   *
   * @var ProjectBoqData
   */
  protected $boqData;
  
  /**
   * remark
   *
   * @var string
   */
  protected $remark;
  
  /**
   * constructor
   *
   * @param requestForQuotation|null $thing
   */
  public function __construct(RequestForQuotation $requestForQuotation = null) {
      $this->requestForQuotation = $requestForQuotation;
  }
  
  /**
   * Get id
   *
   * @return string
   */
  public function getId(){
      return $this->id;
  }
  
  /**
   * get requestForQuotation
   *
   * @return RequestForQuotation
   */
  public function getRequestForQuotation() {
      return $this->requestForQuotation;
  }
  
  /**
   * set requestForQuotation
   *
   * @param RequestForQuotation $requestForQuotation
   *
   * @return static
   */
  public function setPurchase(RequestForQuotation $requestForQuotation) {
      $this->requestForQuotation = $requestForQuotation;
      
      return $this;
  }
  
  /**
   * get costItem
   *
   * @return CostItem
   */
  public function getCostItem() {
      return $this->costItem;
  }
  
  /**
   * set costItem
   *
   * @param CostItem $costItem
   *
   * @return static
   */
  public function setCostItem(?CostItem $costItem) {
      $this->costItem = $costItem;
      
      return $this;
  }
  
  
  /**
   * get quantity
   *
   * @return string
   */
  public function getQuantity() {
      return $this->quantity;
  }
  
  /**
   * set quantity
   *
   * @param string $quantity
   *
   * @return static
   */
  public function setQuantity(string $quantity) {
      $this->quantity = $quantity;
      
      return $this;
  }
  
  /**
   * get boqData
   *
   * @return ProjectBoqData
   */
  public function getBoqData() {
      return $this->boqData;
  }
  
  /**
   * set boqData
   *
   * @param ProjectBoqData $boqData
   *
   * @return static
   */
  public function setBoqData(ProjectBoqData $boqData) {
      $this->boqData = $boqData;
      
      return $this;
  }
  
  /**
   * get remark
   *
   * @return string
   */
  public function getRemark() {
      return $this->remark;
  }
  
  /**
   * set remark
   *
   * @param string $remark
   *
   * @return static
   */
  public function setRemark(string $remark) {
      $this->remark = $remark;
      
      return $this;
  }
  
  public function getStockQuantity()
  {
      return $this->stockQuantity;
  }

  public function setStockQuantity($stockQuantity)
  {
      $this->stockQuantity = $stockQuantity;

      return $this;
  }  


  public function getPurchaseRequestDetail() {
    return $this->purchaseRequestDetail;
  }

  
  public function setPurchaseRequestDetail(PurchaseRequestDetail $purchaseRequestDetail) {
    $this->purchaseRequestDetail = $purchaseRequestDetail;

    return $this;
  }
}
