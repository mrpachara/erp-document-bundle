<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectBoqContractSummary Query (CQRS)
 */
interface ProjectBoqContractSummaryQuery
{
    public function getProjectBoqDataSummary($id, $excepts = null);
    public function getProjectBoqsSummary($idProject, $excepts = null);
    // public function getIncomeDetailActive($id, $excepts = null);
    public function getIncomeActiveByBoq($id, $excepts = null);
}
