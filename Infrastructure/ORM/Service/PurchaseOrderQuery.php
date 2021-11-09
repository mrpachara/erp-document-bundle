<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery as QueryInterface;

abstract class PurchaseOrderQuery extends PurchaseQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $purchaseRequestDetailStatusChangedRepository;

    /** @var PurchaseRequestQueryService */
    protected $purchaseRequestQueryService;

    public function getPurchaseRequestDetailStatusChangedQueryBuilder($alias)
    {
        $activePurchaseOrderQb = $this->getAliveDocumentQueryBuilder('_activeDocument');
        $statusChangedQb = $this->purchaseRequestDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
            ->innerJoin(
                'ErpDocumentBundle:PurchaseOrderDetail', '_purchaseOrderDetail',
                'WITH', '_statusChanged = _purchaseOrderDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:PurchaseOrder',
                '_purchaseOrder',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_purchaseOrderDetail.purchase', '_purchaseOrder'),
                    $statusChangedQb->expr()->in(
                        '_purchaseOrder.id',
                        $activePurchaseOrderQb->select('_activeDocument.id')->getDQL()
                    )
                )
            )
            ->where($statusChangedQb->expr()->eq('_statusChanged.purchaseRequestDetail', $alias))
        ;
    }

    public function searchPurchaseRequestRemain(array $params, array &$context = null)
    {
        // $context = (array)$context;
        // $statusChangedQb = $this->getPurchaseRequestDetailStatusChangedQueryBuilder('_detail');

        // $qb = $this->purchaseRequestQueryService->searchQueryBuilder($params, '_entity', $context);
        // $qb
        //     ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        //     ->innerJoin('_entity.details', '_detail')
        //     ->leftJoin(
        //         'ErpDocumentBundle:PurchaseRequestDetailStatusChanged',
        //         '_activeStatusChanged',
        //         'WITH',
        //         $qb->expr()->andX(
        //             $qb->expr()->eq('_detail', '_activeStatusChanged.purchaseRequestDetail'),
        //             $qb->expr()->in(
        //                 '_activeStatusChanged.id',
        //                 $statusChangedQb->select('_statusChanged.id')->getDQL()
        //             )
        //         )
        //     )

        //     ->andWhere('_activeStatusChanged IS NULL')

        //     ->andWhere('_entity.terminated IS NULL')
        //     ->andWhere('_updatedBy IS NULL')
        //     ->andWhere('_entity.approved = TRUE')
        // ;

        // return $this->qh->execute($qb->distinct()->getQuery(), $params, $context);
        return $this->purchaseRequestQueryService->searchRemain($params, $context);
    }

    public function getPurchaseRequestRemain($id)
    {
        $purchaseRequest = $this->purchaseRequestQueryService->find($id);
        if (empty($purchaseRequest)) {
            return $purchaseRequest;
        }

        // $statusChangedQb = $this->getPurchaseRequestDetailStatusChangedQueryBuilder('_purchaseRequestDetail');

        // $qb = $this->purchaseRequestQueryService->createDetailQueryBuilder('_purchaseRequestDetail');
        // $qb
        //     ->innerJoin(
        //         'ErpDocumentBundle:PurchaseRequest', '_purchaseRequest',
        //         'WITH', '_purchaseRequestDetail.purchase = _purchaseRequest'
        //     )
        //     ->leftJoin(
        //         'ErpDocumentBundle:PurchaseRequestDetailStatusChanged', '_activeStatusChanged',
        //         'WITH',
        //         $qb->expr()->andX(
        //             $qb->expr()->eq('_purchaseRequestDetail', '_activeStatusChanged.purchaseRequestDetail'),
        //             $qb->expr()->in(
        //                 '_activeStatusChanged.id',
        //                 $statusChangedQb->select('_statusChanged.id')->getDQL()
        //             )
        //         )
        //     )
        //     ->andWhere('_activeStatusChanged IS NULL')

        //     ->andWhere('_purchaseRequest = :purchaseRequestId')
        //     ->setParameter('purchaseRequestId', $purchaseRequest->getId())
        // ;

        // $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
        // if(count($details) == 0) return null;
        // $purchaseRequest->setDetails($details);

        $detailAlias = '_purchase_detail';
        $detailRemainQb = $this->purchaseRequestQueryService->createDetailQueryBuilder($detailAlias);
        $detailRemainQb = $this->purchaseRequestQueryService->assignDetailRemainQuery($detailRemainQb, $detailAlias);

        $expr = $detailRemainQb->expr();
        $detailRemainQb->andWhere($expr->eq("{$detailAlias}.purchase", ':purchaseRequest'));
        $detailRemainQb->setParameter('purchaseRequest', $purchaseRequest);

        $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($detailRemainQb->getQuery()->getResult());
        if(count($details) == 0) return null;
        $purchaseRequest->setDetails($details);

        return $purchaseRequest;
    }
}
