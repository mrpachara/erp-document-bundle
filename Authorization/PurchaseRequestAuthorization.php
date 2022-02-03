<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class PurchaseRequestAuthorization extends AbstractPurchaseAuthorization
{
    public function list(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_LIST');
    }

    public function listWorker(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_LIST_WORKER');
    }

    public function listIndividual(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_LIST_INDIVIDUAL');
    }

    public function get(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW');
    }

    public function getWorker(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW_WORKER');
    }

    public function getIndividual(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW_INDIVIDUAL');
    }

    public function add(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CREATE');
    }

    public function addWorker(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CREATE_WORKER');
    }

    public function addIndividual(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CREATE_INDIVIDUAL');
    }

    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT');
    }

    public function replaceWorker(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT_WORKER');
    }

    public function replaceIndividual(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT_INDIVIDUAL');
    }

    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CANCEL');
    }

    public function reject(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_REJECT');
    }
}
