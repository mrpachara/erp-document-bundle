<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQuery;

abstract class PurchaseQuery extends DocumentQuery implements QueryInterface
{
    use DocumentWithProjectTrail;

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
}
