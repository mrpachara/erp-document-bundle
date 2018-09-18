<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\RequestForQuotationQuery as QueryInterface;

abstract class RequestForQuotationQuery extends DocumentQuery implements QueryInterface
{
    
    /** @var PurchaseRequestQueryService */
    protected $purchaseRequestQueryService;
    
    

    public function searchPurchaseRequestRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        
        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->purchaseRequestQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
        ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        ->innerJoin('_entity.details', '_detail')
            
            ->andWhere('_entity.terminated IS NULL')
            ->andWhere('_updatedBy IS NULL')
            ->andWhere('_entity.approved = TRUE')
            ;
            
            return $this->qh->execute($qb->distinct()->getQuery(), $params, $context);
    }
    
    public function getPurchaseRequestRemain($id)
    {
        $purchaseRequest = $this->purchaseRequestQueryService->find($id);
        if (empty($purchaseRequest)) {
            return $purchaseRequest;
        }
        
       
        
        $qb = $this->purchaseRequestQueryService->createDetailQueryBuilder('_purchaseRequestDetail');
        $qb
        ->innerJoin(
            'ErpDocumentBundle:PurchaseRequest', '_purchaseRequest',
            'WITH', '_purchaseRequestDetail.purchase = _purchaseRequest'
            )
                
                ->andWhere('_purchaseRequest = :purchaseRequestId')
                ->setParameter('purchaseRequestId', $purchaseRequest->getId())
                ;
                
                $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
                if(count($details) == 0) return null;
                $purchaseRequest->setDetails($details);
                
                return $purchaseRequest;
    }

}
