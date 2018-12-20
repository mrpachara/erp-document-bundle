<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

/**
 * ProjectSummary Query (CQRS)
 */
interface ProjectBoqWithSummaryQuery {
    public function getProjectBoq($idProject, $id, $excepts = null);
    public function getAllProjectBoq($id, $excepts = null);
}
