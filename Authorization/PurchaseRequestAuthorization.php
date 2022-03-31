<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;
use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseRequestQuery;

class PurchaseRequestAuthorization extends AbstractPurchaseAuthorization
{
    /** @required */
    public function setDomainQuery(PurchaseRequestQuery $domainQuery) {
        $this->domainQuery = $domainQuery;
    }

    public function list(...$args)
    {
        return parent::list(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_LIST') && (
                $this->listAll(...$args) ||
                $this->listWorker(...$args) ||
                $this->listIndividual(...$args)
            )
        ;
    }

    public function listAll(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_LIST_ALL');
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
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW') && (
                $this->getAll(...$args) ||
                $this->getWorker(...$args) ||
                $this->getIndividual(...$args)
            )
        ;
    }

    public function getAll(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW_ALL');
    }

    public function getWorker(...$args)
    {
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER])
        ;
    }

    public function getIndividual(...$args)
    {
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_VIEW_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER])
        ;
    }

    public function add(...$args)
    {
        return parent::add(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CREATE') && (
                $this->addAll(...$args) ||
                $this->addWorker(...$args) ||
                $this->addIndividual(...$args)
            )
        ;
    }

    public function addAll(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CREATE_ALL');
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
        return parent::replace(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT') && (
                $this->replaceAll(...$args) ||
                $this->replaceWorker(...$args) ||
                $this->replaceIndividual(...$args)
            )
        ;
    }

    public function replaceAll(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT_ALL');
    }

    public function replaceWorker(...$args)
    {
        return parent::replace(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER])
        ;
    }

    public function replaceIndividual(...$args)
    {
        return parent::replace(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_EDIT_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER])
        ;
    }

    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CANCEL') && (
                $this->cancelAll(...$args) ||
                $this->cancelWorker(...$args) ||
                $this->cancelIndividual(...$args)
            )
        ;
    }

    public function cancelAll(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CANCEL_ALL');
    }

    public function cancelWorker(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CANCEL_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER])
        ;
    }

    public function cancelIndividual(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_CANCEL_INIDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER])
        ;
    }

    public function reject(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_REJECT') && (
                $this->rejectAll(...$args) ||
                $this->rejectWorker(...$args) ||
                $this->rejectIndividual(...$args)
            )
        ;
    }

    public function rejectAll(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_REJECT_ALL');
    }

    public function rejectWorker(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_REJECT_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER])
        ;
    }

    public function rejectIndividual(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_REJECT_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER])
        ;
    }
}
