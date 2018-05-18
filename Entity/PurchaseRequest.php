<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\CoreAccount;
use Erp\Bundle\CoreBundle\Entity\Thing;

use Erp\Bundle\MasterBundle\Entity\Vendor;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\ProjectBoq;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqBudgetType;

/**
 * PurchaseRequest Entity
 */
class PurchaseRequest extends Purchase {
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
