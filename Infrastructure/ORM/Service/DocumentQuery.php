<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery as QueryInterface;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\CoreAccountQuery as ParentQuery;

use Erp\Bundle\DocumentBundle\Entity\Document;

use Doctrine\ORM\QueryBuilder;

abstract class DocumentQuery extends ParentQuery implements QueryInterface
{
    public function searchOptions() {
        $result = parent::searchOptions();
        
        if(!isset($result['where'])) $result['where'] = [
            'fields' => [],
        ];
        
        $result['where']['fields'][] = 'approved';
        
        return $result;
    }
    
    public function origin(Document $doc)
    {
        /** @var QueryBuilder */
        $qb = $this->getRelatedDocumentQueryBuilder($doc, '_related');
        $qb->andWhere('_related.updateOf IS NULL');
        return $qb->getQuery()->getSingleResult();
    }

    public function getRelatedDocumentQueryBuilder(Document $doc, $alias = '_related')
    {
        /** @var QueryBuilder */
        $qb = $this->repository->createQueryBuilder($alias);
        return $qb
            ->where("{$alias}.thing = :_doc_thing")
            ->setParameter('_doc_thing', $doc->getThing()->getId())
        ;
    }

    public function getActiveDocumentQueryBuilder($alias = '_activeDocument')
    {
        /** @var QueryBuilder */
        $qb = $this->repository->createQueryBuilder($alias);
        return $this->assignActiveDocumentQuery($qb, $alias);
    }

    public function assignActiveDocumentQuery(QueryBuilder $qb, $alias)
    {
        return $qb
            ->leftJoin("{$alias}.updatedBys", "{$alias}_activeDocumentUpdatedBy", 'WITH', "{$alias}_activeDocumentUpdatedBy.terminated IS NULL")
            ->andWhere("{$alias}.terminated IS NULL")
            ->andWhere("{$alias}_activeDocumentUpdatedBy IS NULL")
        ;
    }
}
