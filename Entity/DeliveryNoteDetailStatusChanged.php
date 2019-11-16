<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class DeliveryNoteDetailStatusChanged extends DocumentObjectValue {
  /**
   * @var DeliveryNoteDetail
   */
    protected $deliveryNoteDetail;

  /**
   * @var bool
   */
  protected $removed;

  /**
   * constructor
   */
  public function __construct() { }

  public function getDeliveryNoteDetail() {
    return $this->DeliveryNoteDetail;
  }

  public function setDeliveryNoteDetail(?DeliveryNoteDetail $deliveryNoteDetail) {
    $this->deliveryNoteDetail = $deliveryNoteDetail;

    return $this;
  }

  public function getRemoved() {
    return $this->removed;
  }

  public function setRemoved($removed) {
    $this->removed = $removed;

    return $this;
  }
}
