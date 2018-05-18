<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class PurchaseRequestQueryService extends PurchaseRequestQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:PurchaseRequest');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseRequestDetail');
    }
}
