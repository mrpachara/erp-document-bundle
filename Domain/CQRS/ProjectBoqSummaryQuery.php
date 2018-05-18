<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * Document Query (CQRS)
 */
interface ProjectBoqSummaryQuery {
    public function getProjectBoqDataSummary($id);
}
