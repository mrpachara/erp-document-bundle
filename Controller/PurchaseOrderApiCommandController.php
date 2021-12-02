<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * PurchaseOrder Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/purchase-order")
 */
class PurchaseOrderApiCommandController extends PurchaseApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseOrderAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\PurchaseOrderAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

  protected function prepareData($data, $for) {
       $data = parent::prepareData($data, $for);

        return $data;
  }
}
