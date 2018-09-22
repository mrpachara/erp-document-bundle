<?php

namespace Erp\Bundle\DocumentBundle\Entity;

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
