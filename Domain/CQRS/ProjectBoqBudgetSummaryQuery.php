<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectBoqBudgetSummary Query (CQRS)
 */
interface ProjectBoqBudgetSummaryQuery {
    public function getProjectBoqDataSummary($id);
}
