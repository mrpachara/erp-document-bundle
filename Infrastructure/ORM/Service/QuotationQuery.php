<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\QuotationQuery as QueryInterface;

abstract class QuotationQuery extends PurchaseQuery implements QueryInterface
{
    
    /** @var RequestForQuotationQueryService */
    protected $requestForQuotationQueryService;
    
    public function searchRequestForQuotationRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        
        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->requestForQuotationQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
        ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        ->innerJoin('_entity.details', '_detail')
            
            ->andWhere('_entity.terminated IS NULL')
            ->andWhere('_updatedBy IS NULL')
            ->andWhere('_entity.approved = TRUE')
            ;
            
            return $this->qh->execute($qb->distinct()->getQuery(), $params, $context);
    }
    
    public function getRequestForQuotationRemain($id)
    {
        $requestForQuotation = $this->requestForQuotationQueryService->find($id);
        if (empty($requestForQuotation)) {
            return $requestForQuotation;
        }
        
        $qb = $this->RequestForQuotationQueryService->createDetailQueryBuilder('_requestForQuotationDetail');
        $qb
        ->innerJoin(
            'ErpDocumentBundle:RequestForQuotation', '_requestForQuotation',
            'WITH', '_requestForQuotationDetail.purchase = _requestForQuotation'
            )
                
                ->andWhere('_requestForQuotation = :requestForQuotationId')
                ->setParameter('requestForQuotationId', $requestForQuotation->getId())
                ;
                
                $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
                if(count($details) == 0) return null;
                $requestForQuotation->setDetails($details);
                
                return $requestForQuotation;
    }
}
