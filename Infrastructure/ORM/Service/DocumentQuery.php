<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery as QueryInterface;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\CoreAccountQuery as ParentQuery;

use Erp\Bundle\DocumentBundle\Entity\Document;

use Doctrine\ORM\QueryBuilder;

abstract class DocumentQuery extends ParentQuery implements QueryInterface
{
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
        return $qb
            ->leftJoin("{$alias}.updatedBys", '_activeDocumentUpdatedBy', 'WITH', '_activeDocumentUpdatedBy.terminated IS NULL')
            ->andWhere("{$alias}.terminated IS NULL")
            ->andWhere('_activeDocumentUpdatedBy IS NULL')
        ;
    }
}
