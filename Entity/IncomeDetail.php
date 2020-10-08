<?php

namespace Erp\Bundle\DocumentBundle\Entity;

abstract class IncomeDetail {
  /**
  * @var string
  */
  private $id;

  /**
   * income
   * @var Income
   */
  protected $income;

  /**
   * name
   *
   * @var string
   */
  protected $name;
  
  /**
   * unit
   *
   * @var string
   */
  protected $unit;

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
   * remark
   *
   * @var string
   */
  protected $remark;

  /**
   * constructor
   *
   * @param Income|null $thing
   */
  public function __construct(Income $income = null) {
      $this->income = $income;
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
   * get income
   *
   * @return Income
   */
  public function getIncome() {
      return $this->income;
  }

  /**
   * set income
   *
   * @param Income $income
   *
   * @return static
   */
  public function setIncome(Income $income) {
      $this->income = $income;

    return $this;
  }

  /**
   * get name
   *
   * @return string
   */
  public function getName() {
      return $this->name;
  }
  
  /**
   * set name
   *
   * @param string $name
   *
   * @return static
   */
  public function setName(string $name) {
      $this->name = $name;
      
      return $this;
  }
  
  /**
   * get unit
   *
   * @return string
   */
  public function getUnit() {
      return $this->unit;
  }
  
  /**
   * set unit
   *
   * @param string $unit
   *
   * @return static
   */
  public function setUnit(string $unit) {
      $this->unit = $unit;
      
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
