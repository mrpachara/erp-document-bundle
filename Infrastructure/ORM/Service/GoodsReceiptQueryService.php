<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class GoodsReceiptQueryService extends GoodsReceiptQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:GoodsReceipt');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:GoodsReceiptDetail');
        $this->purchaseOrderDetailStatusChangedRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseOrderDetailStatusChanged');
    }

    /** @required */
    public function setPurchaseOrderQueryService(PurchaseOrderQueryService $purchaseOrderQueryService)
    {
        $this->purchaseOrderQueryService = $purchaseOrderQueryService;
    }
}
