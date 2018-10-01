<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\GoodsReceiptQuery as QueryInterface;

abstract class GoodsReceiptQuery extends PurchaseQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $purchaseOrderDetailStatusChangedRepository;

    /** @var PurchaseOrderQueryService */
    protected $purchaseOrderQueryService;

    public function find($id)
    {
        /**
         * @var \Erp\Bundle\DocumentBundle\Entity\GoodsReceipt $entity
         */
        $entity = parent::find($id);
        
        if($entity->getTransferOf() !== null) {
            $qb = $this->getActiveDocumentQueryBuilder('_activeDocument')
                ->andWhere('_activeDocument.transferOf = :transferOf')
                ->orderBy('_activeDocument.tstmp', 'DESC')
            ;
            
            $qb->setParameter('transferOf', $entity->getTransferOf()->getId());
            $qb->setMaxResults(1);
            $lastEntities = $qb->getQuery()->getResult();
            
            $entity->setLastGoodsReceipt((count($lastEntities) > 0)? $lastEntities[0] : null );
        }
        
        return $entity;
    }
    
    public function getPurchaseOrderDetailStatusChangedQueryBuilder($alias)
    {
        $activeGoodsReceiptQb = $this->getActiveDocumentQueryBuilder();
        $statusChangedQb = $this->purchaseOrderDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
            ->innerJoin(
                'ErpDocumentBundle:GoodsReceiptDetail', '_goodsReceiptDetail',
                'WITH', '_statusChanged = _goodsReceiptDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:GoodsReceipt',
                '_goodsReceipt',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_goodsReceiptDetail.purchase', '_goodsReceipt'),
                    $statusChangedQb->expr()->in(
                        '_goodsReceipt.id',
                        $activeGoodsReceiptQb->select('_activeDocument.id')->getDQL()
                    )
                )
            )
            ->where($statusChangedQb->expr()->eq('_statusChanged.purchaseOrderDetail', $alias))
            ->andWhere('_statusChanged.finish = TRUE')
        ;
    }

    public function searchPurchaseOrderRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        $statusChangedQb = $this->getPurchaseOrderDetailStatusChangedQueryBuilder('_detail');

        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->purchaseOrderQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
            ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
            ->innerJoin('_entity.details', '_detail')
            ->leftJoin(
                'ErpDocumentBundle:PurchaseOrderDetailStatusChanged',
                '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_detail', '_activeStatusChanged.purchaseOrderDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                    )
                )
            )

            ->andWhere('_activeStatusChanged IS NULL')

            ->andWhere('_entity.terminated IS NULL')
            ->andWhere('_updatedBy IS NULL')
            ->andWhere('_entity.approved = TRUE')
        ;

        return $this->qh->execute($qb->distinct()->getQuery(), $params, $context);
    }

    public function getPurchaseOrderRemain($id)
    {
        $purchaseOrder = $this->purchaseOrderQueryService->find($id);
        if (empty($purchaseOrder)) {
            return $purchaseOrder;
        }

        $statusChangedQb = $this->getPurchaseOrderDetailStatusChangedQueryBuilder('_purchaseOrderDetail');

        $qb = $this->purchaseOrderQueryService->createDetailQueryBuilder('_purchaseOrderDetail');
        $qb
            ->innerJoin(
                'ErpDocumentBundle:PurchaseOrder', '_purchaseOrder',
                'WITH', '_purchaseOrderDetail.purchase = _purchaseOrder'
            )
            ->leftJoin(
                'ErpDocumentBundle:PurchaseOrderDetailStatusChanged', '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_purchaseOrderDetail', '_activeStatusChanged.purchaseOrderDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                    )
                )
            )
            ->andWhere('_activeStatusChanged IS NULL')

            ->andWhere('_purchaseOrder = :purchaseOrderId')
            ->setParameter('purchaseOrderId', $purchaseOrder->getId())
        ;

        $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
        if(count($details) == 0) return null;
        $purchaseOrder->setDetails($details);

        return $purchaseOrder;
    }
}
