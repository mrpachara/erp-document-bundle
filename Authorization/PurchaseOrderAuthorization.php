<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class PurchaseOrderAuthorization extends AbstractPurchaseAuthorization
{
    public function list(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_LIST');
    }

    public function get(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_VIEW');
    }

    public function add(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_CREATE');
    }

    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_EDIT');
    }

    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_CANCEL');
    }

    public function reject(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_REJECT');
    }
}
