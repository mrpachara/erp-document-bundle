<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\MasterBundle\Entity\CostItem;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqData;

abstract class PurchaseDetail {
  /**
  * @var string
  */
  private $id;

  /**
   * purchase
   * @var Purchase
   */
  protected $purchase;

  /**
   * costItem
   *
   * @var CostItem
   */
  protected $costItem;

  /**
   * price
   *
   * @var string
   */
  protected $price;

  /**
   * quantity
   *
   * @var string
   */
  protected $quantity;

  /**
   * total
   *
   * @var string
   */
  protected $total;

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
   * @param Purchase|null $thing
   */
  public function __construct(Purchase $purchase = null) {
    $this->purchase = $purchase;
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
   * get purchase
   *
   * @return Purchase
   */
  public function getPurchase() {
    return $this->purchase;
  }

  /**
   * set purchase
   *
   * @param Purchase $purchase
   *
   * @return static
   */
  public function setPurchase(Purchase $purchase) {
    $this->purchase = $purchase;

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
   * get price
   *
   * @return string
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * set price
   *
   * @param string $price
   *
   * @return static
   */
  public function setPrice(string $price) {
    $this->price = $price;

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
   * get total
   *
   * @return string
   */
  public function getTotal() {
    return $this->total;
  }

  /**
   * set total
   *
   * @param string $total
   *
   * @return static
   */
  public function setTotal(string $total) {
    $this->total = $total;

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
}
