<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class QuotationAuthorization extends AbstractPurchaseAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_EDIT');
    }
    
    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_APPROVE');
    }
    
    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_CANCEL');
    }
    
    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_REJECT');
    }
    
}
