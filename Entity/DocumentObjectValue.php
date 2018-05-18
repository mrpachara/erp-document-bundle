<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\MasterBundle\Entity\CostItem;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqData;

abstract class DocumentObjectValue {
  /**
   * @var string
   */
  private $id;

  /**
   * Get id
   *
   * @return string
   */
  public function getId(){
    return $this->id;
  }
}
