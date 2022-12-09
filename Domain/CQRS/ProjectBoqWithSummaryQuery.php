<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectSummary Query (CQRS)
 */
interface ProjectBoqWithSummaryQuery
{
    public function getProjectBoq($idProject, $id, $excepts = null);
    public function getProjectBoqWithoutValue($idProject, $id);
    public function getAllProjectContractByBoq($idProject, $id, $excepts = null);
    public function getAllProjectBoq($idProject, $excepts = null);
    public function getAllProjectBoqWithoutValue($idProject);
}
