<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\QuotationQuery as QueryInterface;

abstract class QuotationQuery extends PurchaseQuery implements QueryInterface
{

}
