<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class RevenueAuthorization extends AbstractIncomeAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_EDIT_INCOME_RV');
    }

    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_APPROVE_INCOME_RV');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_CANCEL_INCOME_RV');
    }

    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_REJECT_INCOME_RV');
    }
}
