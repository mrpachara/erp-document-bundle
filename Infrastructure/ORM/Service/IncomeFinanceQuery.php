<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeFinanceQuery as QueryInterface;

abstract class IncomeFinanceQuery extends IncomeQuery implements QueryInterface
{
}
