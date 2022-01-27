<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class IncomeQuery extends DocumentQuery implements QueryInterface
{
    use DocumentWithProjectTrail;

    /** @var IncomeQueryService */
    protected $detailQueryService;

    /** @required */
    public function setDetailQueryService(IncomeQueryService $detailQueryService){
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

        return $options;
    }
}
