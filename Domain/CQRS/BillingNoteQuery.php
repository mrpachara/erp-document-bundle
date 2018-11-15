<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * BillingNote Query (CQRS)
 */
interface BillingNoteQuery extends IncomeQuery{
  public function searchDeliveryNoteRemain(array $params, array &$context = null);

  public function getDeliveryNoteRemain($id);
}
