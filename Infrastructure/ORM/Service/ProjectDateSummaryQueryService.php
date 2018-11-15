<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQueryService;

class ProjectDateSummaryQueryService extends ProjectDateSummaryQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectRepository = $doctrine->getRepository('ErpMasterBundle:Project');
    }

    /** @required */
    public function setProjectBoqRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectBoqRepository = $doctrine->getRepository('ErpMasterBundle:ProjectBoq');
    }

    /** @required */
    public function setProjectQueryService(ProjectQueryService $projectQueryService)
    {
        $this->projectQuery = $projectQueryService;
    }
}
