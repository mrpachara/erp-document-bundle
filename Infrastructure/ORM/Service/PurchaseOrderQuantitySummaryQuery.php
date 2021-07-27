<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuantitySummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;

abstract class PurchaseOrderQuantitySummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $purchaseOrderRepository;
    
    /** @var GoodsReceiptQuery */
    protected $goodsReceiptQuery;

    /** @var PurchaseQuery */
    protected $purchaseQuery;

    public function getPurchaseOrderQuantitySummary($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\DocumentBundle\Entity\PurchaseOrder */
        $purchaseOrder = $this->purchaseOrderRepository->find($id);
        
        $activeGoodsReceiptQb = $this->goodsReceiptQuery->getActiveDocumentQueryBuilder()
            ->andWhere('_activeDocument.transferOf = :transferOf')
            
            ->setParameter('transferOf', $purchaseOrder->getId())
        ;
 
        $goodsReceiptDetailQb = $this->goodsReceiptQuery->createDetailQueryBuilder('_goodsReceiptDetail');
        $excepts = array_map(function($value) use ($goodsReceiptDetailQb) {
            return $goodsReceiptDetailQb->expr()->literal($value);
        }, $excepts);
            $goodsReceiptDetailQb
            ->innerJoin(
                '_goodsReceiptDetail.purchase',
                '_purchase',
                'WITH',
                $goodsReceiptDetailQb->expr()->andX(
                    $goodsReceiptDetailQb->expr()->in(
                        '_purchase.id',
                        $activeGoodsReceiptQb->select('_activeDocument.id')->getDQL()
                    ),
                    (empty($excepts))? '1 = 1' :
                    $goodsReceiptDetailQb->expr()->notIn(
                        '_purchase.id',
                        $excepts
                    )
                )
            )
        ;

        $goodsReceiptDetailQb
        ->select('_purchaseOrderDetail.id AS id', 'SUM(_goodsReceiptDetail.quantity) AS quantity')
        ->innerJoin('_goodsReceiptDetail.statusChanged', '_statusChanged')
        ->innerJoin('_statusChanged.purchaseOrderDetail', '_purchaseOrderDetail')
        ->groupBy('_purchaseOrderDetail.id')
        ;
            
        $goodsReceiptDetailQb->setParameters($activeGoodsReceiptQb->getParameters());
        $goodsReceiptDetails = $goodsReceiptDetailQb->getQuery()->getResult();
            
        return $goodsReceiptDetails;
        }
}
