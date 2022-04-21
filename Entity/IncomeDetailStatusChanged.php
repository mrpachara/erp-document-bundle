<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class IncomeDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var IncomeDetail
   */
  protected $detail;

  /**
   * @var string
   */
  protected $type;

  /**
   * constructor
   */
  public function __construct() { }

  public function getIncomeDetail() {
    return $this->detail;
  }

  public function setIncomeDetail(?IncomeDetail $incomeDetail) {
    $this->detail = $incomeDetail;

    return $this;
  }

  public function getType()
  {
      return $this->type;
  }
}
