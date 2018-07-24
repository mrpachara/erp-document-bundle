<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class QuotationQueryService extends PurchaseQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Quotation');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:QuotationDetail');

    }

    /** @required */
    public function setPurchaseRequestQueryService(PurchaseRequestQueryService $purchaseRequestQueryService)
    {
        $this->purchaseRequestQueryService = $purchaseRequestQueryService;
    }
}
