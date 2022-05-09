<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * TaxInvoice Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/tax-invoice")
 */
class TaxInvoiceApiCommandController extends IncomeApiCommand
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\TaxInvoiceAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\TaxInvoiceAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\TaxInvoiceQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\TaxInvoiceQuery $domainQuery)
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
