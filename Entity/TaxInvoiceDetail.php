<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class TaxInvoiceDetail extends IncomeDetail {
    /**
     * @var BillingNoteDetailStatusChanged
     */
    protected $statusChanged;
    
    /**
     * constructor
     *
     * @param TaxInvoice|null $income
     */
    public function __construct(TaxInvoice $income = null) {
        parent::__construct($income);
    }
    
    public function getStatusChanged() {
        return $this->statusChanged;
    }
    
    public function setStatusChanged(BillingNoteDetailStatusChanged $statusChanged) {
        $this->statusChanged = $statusChanged;
        
        return $this;
    }
}
