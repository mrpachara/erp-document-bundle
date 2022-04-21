<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class TaxInvoiceDetail extends IncomeDetail {
    /**
     * constructor
     *
     * @param TaxInvoice|null $income
     */
    public function __construct(TaxInvoice $income = null) {
        parent::__construct($income);
    }
}
