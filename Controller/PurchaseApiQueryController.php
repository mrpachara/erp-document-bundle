<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Purchase Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/purchase")
 */
class PurchaseApiQueryController extends PurchaseApiQuery {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\PurchaseAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }
}
