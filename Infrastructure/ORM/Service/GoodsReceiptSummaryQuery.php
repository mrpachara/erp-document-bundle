<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\GoodsReceiptSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;

abstract class GoodsReceiptSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $purchaseOrderRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var GoodsReceiptQuery */
    protected $goodsReceiptQuery;

    public function getGoodsReceiptSummary($id)
    {
        /** @var \Erp\Bundle\DocumentBundle\Entity\PurchaseOrder */
        $purchaseOrder = $this->purchaseOrderRepository->find($id);

        $activeGoodsReceiptQb = $this->goodsReceiptQuery->getAliveDocumentQueryBuilder('_activeDocument');
        $activeGoodsReceiptQb
            ->andWhere('_activeDocument.transferOf = :purchaseOrderId')
            ->setParameter('purchaseOrderId', $purchaseOrder->getId())
        ;

            $goodsReceipt = $activeGoodsReceiptQb->getQuery()->getResult();

            return $goodsReceipt;
    }

}
