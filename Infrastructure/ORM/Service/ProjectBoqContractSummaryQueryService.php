<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class ProjectBoqContractSummaryQueryService extends ProjectBoqContractSummaryQuery
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
    public function setIncomeQueryService(IncomeQueryService $incomeQueryService)
    {
        $this->incomeQuery = $incomeQueryService;
    }
}
