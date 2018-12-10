<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectBoqContractSummary Query (CQRS)
 */
interface ProjectBoqContractSummaryQuery {
    public function getProjectBoqDataSummary($id, $excepts = null);
    public function getProjectBoqsSummary($idProject, $excepts = null);
}
