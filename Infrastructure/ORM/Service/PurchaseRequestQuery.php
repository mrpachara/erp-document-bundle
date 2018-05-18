<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseRequestQuery as QueryInterface;

abstract class PurchaseRequestQuery extends PurchaseQuery implements QueryInterface
{
}
