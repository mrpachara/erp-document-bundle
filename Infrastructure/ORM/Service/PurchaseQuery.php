<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\DocumentBundle\Entity\DetailStatusChanged;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQuery;

abstract class PurchaseQuery extends DocumentQuery implements QueryInterface
{
    use DocumentWithProjectTrail;

    /** @var PurchaseQueryService */
    protected $detailQueryService;

    /** @required */
    public function setDetailQueryService(PurchaseQueryService $detailQueryService){
        $this->detailQueryService = $detailQueryService;
    }

    /** @var EntityRepository */
    protected $detailRepository;

    public function createDetailQueryBuilder($alias = null): QueryBuilder
    {
        $alias = ($alias)? $alias : '_entity_detail';
        return $this->detailRepository->createQueryBuilder($alias);
    }

    public function searchOptions()
    {
        $options = parent::searchOptions();
        $options['term']['fields'][] = 'requester.code';
        $options['term']['fields'][] = 'requester.thing.name';

        $options['term']['fields'][] = 'project.code';
        $options['term']['fields'][] = 'project.thing.name';
        $options['term']['fields'][] = 'approved';
        return $options;
    }

    public function assignDetailRemainQuery(QueryBuilder $qb, $alias) : QueryBuilder
    {
        $boundDetailAlias = "{$alias}_boundDetail";
        $boundHeaderAlias = "{$alias}_boundHeader";
        $statusChangedAlias = "{$alias}_statusChanged";
        $boundDetailQb = $this->detailQueryService->createDetailQueryBuilder($boundDetailAlias)
            ->leftJoin("{$boundDetailAlias}.statusChanged", $statusChangedAlias)
            ->leftJoin("{$boundDetailAlias}.purchase", $boundHeaderAlias)
        ;

        $boundDetailQb = $this->assignAliveDocumentQuery($boundDetailQb, $boundHeaderAlias);

        $expr = $qb->expr();
        $statusOrX = $expr->orX()
            ->add($expr->eq("{$statusChangedAlias}.type", $expr->literal(DetailStatusChanged::FINISH)))
            ->add($expr->eq("{$statusChangedAlias}.type", $expr->literal(DetailStatusChanged::REMOVED)))
        ;
        $boundDetailQb->andWhere($statusOrX);

        return $qb
            ->andWhere(
                $expr->not(
                    $expr->exists(
                        $boundDetailQb
                            ->andWhere(
                                $expr->eq("{$statusChangedAlias}.detail", $alias)
                            )
                            ->getDQL()
                    )
                )
            )
        ;
    }

    public function assignHeaderRemainQuery(QueryBuilder $qb, $alias) : QueryBuilder
    {
        $detailAlias = "{$alias}_detail";
        $detailQb = $this->createDetailQueryBuilder($detailAlias);
        $detailQb = $this->assignDetailRemainQuery($detailQb, $detailAlias);

        $expr = $qb->expr();
        return $qb
            ->andWhere(
                $expr->exists(
                    $detailQb->andWhere(
                        $expr->eq("{$detailAlias}.purchase", $alias)
                    )
                )
            )
        ;
    }

    public function searchRemain(array $params, array &$context = null) {
        $context = (array)$context;

        $alias = '_doc_remain';
        $qb = $this->searchQueryBuilder($params, $alias, $context);
        $qb = $this->assignHeaderRemainQuery($qb, $alias);

        return $this->qh->execute($qb->getQuery(), $params, $context);
    }
}
