<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseFinanceQuery as QueryInterface;

abstract class PurchaseFinanceQuery extends PurchaseQuery implements QueryInterface
{
}
