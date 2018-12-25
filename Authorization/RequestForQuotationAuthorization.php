<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class RequestForQuotationAuthorization extends AbstractPurchaseAuthorization
{
    public function list(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_LIST');
    }
    
    public function get(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_VIEW');
    }
    
    public function add(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_CREATE');
    }
    
    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_EDIT');
    }
    
    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_APPROVE');
    }
    
    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_CANCEL');
    }
    
    public function reject(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_RQ_REJECT');
    }
}
