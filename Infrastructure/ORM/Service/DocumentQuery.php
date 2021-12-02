<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery as QueryInterface;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\CoreAccountQuery as ParentQuery;

use Erp\Bundle\DocumentBundle\Entity\Document;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\CoreBundle\Domain\Adapter\Query;

abstract class DocumentQuery extends ParentQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $docRepository = null;

    public function setDocRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine){
        $this->docRepository = $doctrine->getRepository(Document::class);
    }

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

    public function getAliveDocumentQueryBuilder($alias) : QueryBuilder
    {
        /** @var QueryBuilder */
        $qb = $this->repository->createQueryBuilder($alias);
        return $this->assignAliveDocumentQuery($qb, $alias);
    }

    public function assignAliveDocumentQuery(QueryBuilder $qb, $alias) : QueryBuilder
    {
        return $qb
            // TODO: The updated document should be dead forever, so don't check terminated IS NULL for updatedBy documents.
            //   This will be the cause of creating multiple branches of document and breaking some constraints, e.g. PO document.
            ->leftJoin("{$alias}.updatedBys", "{$alias}_aliveDocumentUpdatedBy", 'WITH', "{$alias}_aliveDocumentUpdatedBy.terminated IS NULL")
            ->andWhere("{$alias}.terminated IS NULL")
            ->andWhere("{$alias}_aliveDocumentUpdatedBy IS NULL")
        ;
    }

    public function assignApprovedDocumentQuery(QueryBuilder $qb, $alias) : QueryBuilder
    {
        return $qb
            ->andWhere("{$alias}.approved = 1")
        ;
    }
}
