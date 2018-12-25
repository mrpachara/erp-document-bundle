<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class QuotationAuthorization extends AbstractPurchaseAuthorization
{
    public function list(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_LIST');
    }
    
    public function get(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_VIEW');
    }
    
    public function add(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_CREATE');
    }
    
    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_EDIT');
    }
    
    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_APPROVE');
    }
    
    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_CANCEL');
    }
    
    public function reject(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_QT_REJECT');
    }
    
}
