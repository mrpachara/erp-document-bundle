<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class PurchaseQueryService extends PurchaseQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Purchase');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:PurchaseDetail');
    }
}
