<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class PurchaseFinanceQueryService extends PurchaseFinanceQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:PurchaseFinance');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseDetail');
    }
}
