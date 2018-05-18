<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class ProjectBoqSummaryQueryService extends ProjectBoqSummaryQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->projectBoqDataRepository = $doctrine->getRepository('ErpMasterBundle:ProjectBoqData');
    }
    
    /** @required */
    public function setPurchaseQueryService(PurchaseQueryService $purchaseQueryService)
    {
        $this->purchaseQuery = $purchaseQueryService;
    }
}
