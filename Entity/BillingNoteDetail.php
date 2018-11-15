<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class BillingNoteDetail extends IncomeDetail {
    /**
     * @var DeliveryNoteDetailStatusChanged
     */
    protected $statusChanged;
    
    /**
     * constructor
     *
     * @param BillingNote|null $income
     */
    public function __construct(BillingNote $income = null) {
        parent::__construct($income);
    }
    
    public function getStatusChanged() {
        return $this->statusChanged;
    }
    
    public function setStatusChanged(DeliveryNoteDetailStatusChanged $statusChanged) {
        $this->statusChanged = $statusChanged;
        
        return $this;
    }
}
