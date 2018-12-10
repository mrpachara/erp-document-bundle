<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectDateSummary Query (CQRS)
 */
interface ProjectDateSummaryQuery {
    public function getProjectDateSummary($id);
}
