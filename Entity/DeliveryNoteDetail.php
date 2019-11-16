<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class DeliveryNoteDetail extends IncomeDetail {

  /**
   * constructor
   *
   * @param DeliveryNote|null $income
   */
    public function __construct(DeliveryNote $income = null) {
    parent::__construct($income);
  }
}
