<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class BillingNoteDetail extends IncomeDetail {
    /**
     * constructor
     *
     * @param BillingNote|null $income
     */
    public function __construct(BillingNote $income = null) {
        parent::__construct($income);
    }
}
