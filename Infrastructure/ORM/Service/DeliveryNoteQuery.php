<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\DeliveryNoteQuery as QueryInterface;

abstract class DeliveryNoteQuery extends IncomeQuery implements QueryInterface
{
}
