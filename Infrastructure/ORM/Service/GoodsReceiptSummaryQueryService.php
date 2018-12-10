<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class GoodsReceiptSummaryQueryService extends GoodsReceiptSummaryQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->purchaseOrderRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseOrder');
    }

    /** @required */
    public function setProjectBoqRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectBoqRepository = $doctrine->getRepository('ErpMasterBundle:ProjectBoq');
    }

    /** @required */
    public function setGoodsReceiptQueryService(GoodsReceiptQueryService $goodsReceiptQueryService)
    {
        $this->goodsReceiptQuery = $goodsReceiptQueryService;
    }
}
