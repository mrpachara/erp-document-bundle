<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Document Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/document")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class DocumentApiQueryController extends DocumentApiQuery {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\DocumentAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\DocumentAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

}
