<?php

namespace Erp\Bundle\DocumentBundle\Entity;

class PurchaseRequestDetail extends PurchaseDetail
{
    /**
     * constructor
     *
     * @param PurchaseRequest|null $purchase
     */
    public function __construct(PurchaseRequest $purchase = null)
    {
        parent::__construct($purchase);
    }
}
