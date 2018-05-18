<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\MasterBundle\Entity\CostItem;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqData;

class PurchaseRequestDetail extends PurchaseDetail {
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
   * constructor
   *
   * @param PurchaseRequest|null $purchase
   */
  public function __construct(PurchaseRequest $purchase = null) {
    parent::__construct($purchase);
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
}
