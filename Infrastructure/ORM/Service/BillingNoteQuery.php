<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\BillingNoteQuery as QueryInterface;

abstract class BillingNoteQuery extends IncomeQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $deliveryNoteDetailStatusChangedRepository;
    
    /** @var DeliveryNoteQueryService */
    protected $deliveryNoteQueryService;
    
    public function getDeliveryNoteDetailStatusChangedQueryBuilder($alias)
    {
        $activeBillingNoteQb = $this->getActiveDocumentQueryBuilder();
        $statusChangedQb = $this->deliveryNoteDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
        ->innerJoin(
            'ErpDocumentBundle:BillingNoteDetail', '_billingNoteDetail',
            'WITH', '_statusChanged = _billingNoteDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:BillingNote',
                '_billingNote',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_billingNoteDetail.income', '_billingNote'),
                    $statusChangedQb->expr()->in(
                        '_billingNote.id',
                        $activeBillingNoteQb->select('_activeDocument.id')->getDQL()
                        )
                    )
                )
                ->where($statusChangedQb->expr()->eq('_statusChanged.deliveryNoteDetail', $alias))
                ;
    }
    
    public function searchDeliveryNoteRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        $statusChangedQb = $this->getDeliveryNoteDetailStatusChangedQueryBuilder('_detail');
        
        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->deliveryNoteQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
        ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        ->innerJoin('_entity.details', '_detail')
        ->leftJoin(
            'ErpDocumentBundle:DeliveryNoteDetailStatusChanged',
            '_activeStatusChanged',
            'WITH',
            $qb->expr()->andX(
                $qb->expr()->eq('_detail', '_activeStatusChanged.deliveryNoteDetail'),
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
    
    public function getDeliveryNoteRemain($id)
    {
        $deliveryNote = $this->deliveryNoteQueryService->find($id);
        if (empty($deliveryNote)) {
            return $deliveryNote;
        }
        
        $statusChangedQb = $this->getDeliveryNoteDetailStatusChangedQueryBuilder('_deliveryNoteDetail');
        
        $qb = $this->deliveryNoteQueryService->createDetailQueryBuilder('_deliveryNoteDetail');
        $qb
        ->innerJoin(
            'ErpDocumentBundle:DeliveryNote', '_deliveryNote',
            'WITH', '_deliveryNoteDetail.income = _deliveryNote'
            )
            ->leftJoin(
                'ErpDocumentBundle:DeliveryNoteDetailStatusChanged', '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_deliveryNoteDetail', '_activeStatusChanged.deliveryNoteDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                        )
                    )
                )
                ->andWhere('_activeStatusChanged IS NULL')
                
                ->andWhere('_deliveryNote = :deliveryNoteId')
                ->setParameter('deliveryNoteId', $deliveryNote->getId())
                ;
                
                $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
                if(count($details) == 0) return null;
                $deliveryNote->setDetails($details);
                
                return $deliveryNote;
    }
}
