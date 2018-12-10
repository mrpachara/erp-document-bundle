<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class RevenueDetail extends IncomeDetail {
    /**
     * @var TaxInvoiceDetailStatusChanged
     */
    protected $statusChanged;
    
    /**
     * constructor
     *
     * @param Revenue|null $income
     */
    public function __construct(Revenue $income = null) {
        parent::__construct($income);
    }
    
    public function getStatusChanged() {
        return $this->statusChanged;
    }
    
    public function setStatusChanged(TaxInvoiceDetailStatusChanged $statusChanged) {
        $this->statusChanged = $statusChanged;
        
        return $this;
    }
}
