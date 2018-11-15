<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\Thing;

/**
 * DeliveryNote Entity
 */
class DeliveryNote extends Income {
  /**
   * constructor
   *
   * @param Thing|null $thing
   */
  public function __construct(Thing $thing = null) {
    parent::__construct($thing);
  }

  public function setDetails(ArrayCollection $details) {
    $this->details = $details;

    return $this;
  }
}
