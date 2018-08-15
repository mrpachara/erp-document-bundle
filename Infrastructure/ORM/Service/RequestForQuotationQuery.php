<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\RequestForQuotationQuery as QueryInterface;

abstract class RequestForQuotationQuery extends PurchaseQuery implements QueryInterface
{


}
