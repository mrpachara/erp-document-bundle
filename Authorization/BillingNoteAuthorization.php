<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class BillingNoteAuthorization extends AbstractIncomeAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_EDIT_PURCHASE_PO');
    }

    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_APPROVE_PURCHASE_PO');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_CANCEL_PURCHASE_PO');
    }

    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_REJECT_PURCHASE_PO');
    }
}
