<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class RevenueDetail extends IncomeDetail {
    /**
     * constructor
     *
     * @param Revenue|null $income
     */
    public function __construct(Revenue $income = null) {
        parent::__construct($income);
    }
}
