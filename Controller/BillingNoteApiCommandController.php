<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * BillingNote Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/billing-note")
 */
class BillingNoteApiCommandController extends IncomeApiCommand
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\BillingNoteAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\BillingNoteAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\BillingNoteQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\BillingNoteQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    protected function prepareData($data, $for)
    {
        $data = parent::prepareData($data, $for);

        return $data;
    }
}
