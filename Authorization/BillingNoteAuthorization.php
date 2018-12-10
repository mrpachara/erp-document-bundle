<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class BillingNoteAuthorization extends AbstractIncomeAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_EDIT_INCOME_BN');
    }

    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_APPROVE_INCOME_BN');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_CANCEL_INCOME_BN');
    }

    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_REJECT_INCOME_BN');
    }
}
