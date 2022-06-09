<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;
use Erp\Bundle\DocumentBundle\Domain\CQRS\BillingNoteQuery;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DeliveryNoteQuery;

class BillingNoteAuthorization extends AbstractIncomeAuthorization
{
    /** @required */
    public function setDomainQuery(BillingNoteQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    /** @required */
    public function setReferenceQuery(DeliveryNoteQuery $referenceQuery)
    {
        $this->referenceQuery = $referenceQuery;
    }

    public function list(...$args)
    {
        return parent::list(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_LIST') && ($this->listAll(...$args) ||
                $this->listWorker(...$args) ||
                $this->listIndividual(...$args)
            );
    }

    public function listAll(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_LIST_ALL');
    }

    public function listWorker(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_LIST_WORKER');
    }

    public function listIndividual(...$args)
    {
        return parent::list(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_LIST_INDIVIDUAL');
    }

    public function get(...$args)
    {
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_VIEW') && ($this->getAll(...$args) ||
                $this->getWorker(...$args) ||
                $this->getIndividual(...$args)
            );
    }

    public function getAll(...$args)
    {
        return parent::get(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_VIEW_ALL');
    }

    public function getWorker(...$args)
    {
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_VIEW_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER]);
    }

    public function getIndividual(...$args)
    {
        return parent::get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_VIEW_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER]);
    }

    public function add(...$args)
    {
        return parent::add(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CREATE') && (
                ($this->addAll(...$args)) ||
                $this->addWorker(...$args) ||
                $this->addIndividual(...$args)
            );
    }

    public function addAll(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CREATE_ALL');
    }

    public function addWorker(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CREATE_WORKER') &&
            $this->isUserReferenceGranted($args, [ServiceInterface::WORKER]);
    }

    public function addIndividual(...$args)
    {
        return parent::add(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CREATE_INDIVIDUAL') &&
            $this->isUserReferenceGranted($args, [ServiceInterface::OWNER]);
    }

    public function replace(...$args)
    {
        return parent::replace(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_EDIT');
    }

    public function replaceAll(...$args)
    {
        $this->authorizationChecker->isGranted('ROLE_INCOME_BN_EDIT') && ($this->replaceAll(...$args) ||
            $this->replaceWorker(...$args) ||
            $this->replaceIndividual(...$args)
        );
    }

    public function replaceWorker(...$args)
    {
        return parent::replace(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_EDIT_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER]);
    }

    public function replaceIndividual(...$args)
    {
        return parent::replace(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_EDIT_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER]);
    }

    public function approve(...$args)
    {
        return parent::approve(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_APPROVE');
    }

    public function cancel(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CANCEL') && ($this->cancelAll(...$args) ||
                $this->cancelWorker(...$args) ||
                $this->cancelIndividual(...$args)
            );
    }

    public function cancelAll(...$args)
    {
        return parent::cancel(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CANCEL_ALL');
    }

    public function cancelWorker(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CANCEL_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER]);
    }

    public function cancelIndividual(...$args)
    {
        return parent::cancel(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_CANCEL_INIDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER]);
    }

    public function reject(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_REJECT') && ($this->rejectAll(...$args) ||
                $this->rejectWorker(...$args) ||
                $this->rejectIndividual(...$args)
            );
    }

    public function rejectAll(...$args)
    {
        return parent::reject(...$args) && $this->authorizationChecker->isGranted('ROLE_INCOME_BN_REJECT_ALL');
    }

    public function rejectWorker(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_REJECT_WORKER') &&
            $this->isUserGranted($args, [ServiceInterface::WORKER]);
    }

    public function rejectIndividual(...$args)
    {
        return parent::reject(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_INCOME_BN_REJECT_INDIVIDUAL') &&
            $this->isUserGranted($args, [ServiceInterface::OWNER]);
    }
}
