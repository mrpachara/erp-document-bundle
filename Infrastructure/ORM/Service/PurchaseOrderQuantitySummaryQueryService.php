<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class PurchaseOrderQuantitySummaryQueryService extends PurchaseOrderQuantitySummaryQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->purchaseOrderRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseOrder');
    }

    /** @required */
    public function setGoodsReceiptQueryService(GoodsReceiptQueryService $goodsReceiptQueryService)
    {
        $this->goodsReceiptQuery = $goodsReceiptQueryService;
    }

    /** @required */
    public function setPurchaseQueryService(PurchaseQueryService $purchaseQueryService)
    {
        $this->purchaseQuery = $purchaseQueryService;
    }
}
