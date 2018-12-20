<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class DeliveryNoteAuthorization extends AbstractIncomeAuthorization
{
    public function replace(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_DN_EDIT');
    }

    public function approve(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_DN_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_DN_CANCEL');
    }

    public function reject(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_DN_REJECT');
    }
}
