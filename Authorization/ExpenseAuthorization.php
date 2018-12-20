<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class ExpenseAuthorization extends AbstractPurchaseAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_EDIT');
    }

    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_CANCEL');
    }

    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_REJECT');
    }
}
