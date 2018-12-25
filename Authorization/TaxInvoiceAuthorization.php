<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class TaxInvoiceAuthorization extends AbstractIncomeAuthorization
{
    public function list(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_LIST');
    }
    
    public function get(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_VIEW');
    }
    
    public function add(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_CREATE');
    }
    
    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_EDIT');
    }

    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_CANCEL');
    }

    public function reject(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_TI_REJECT');
    }
}
