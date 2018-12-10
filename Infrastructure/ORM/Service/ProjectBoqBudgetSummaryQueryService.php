<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectBoqQueryService;

class ProjectBoqBudgetSummaryQueryService extends ProjectBoqBudgetSummaryQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectBoqDataRepository = $doctrine->getRepository('ErpMasterBundle:ProjectBoqData');
    }

    /** @required */
    public function setProjectRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectRepository = $doctrine->getRepository('ErpMasterBundle:Project');
    }

    /** @required */
    public function setProjectBoqQueryService(ProjectBoqQueryService $projectBoqQueryService)
    {
        $this->projectBoqQuery = $projectBoqQueryService;
    }
}
