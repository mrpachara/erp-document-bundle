<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class PurchaseOrderQueryService extends PurchaseOrderQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:PurchaseOrder');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseOrderDetail');
    }

    /** @required */
    public function setPurchaseRequestQueryService(PurchaseRequestQueryService $purchaseRequestQueryService)
    {
        $this->purchaseRequestQueryService = $purchaseRequestQueryService;
    }
}
