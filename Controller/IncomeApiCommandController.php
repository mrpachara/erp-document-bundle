<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Income Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/income")
 */
class IncomeApiCommandController extends IncomeApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\IncomeAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\IncomeAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }
}
