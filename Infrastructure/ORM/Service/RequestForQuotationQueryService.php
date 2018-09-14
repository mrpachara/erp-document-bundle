<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class RequestForQuotationQueryService extends RequestForQuotationQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:RequestForQuotation');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:RequestForQuotationDetail');
      
    }

    /** @required */
    public function setPurchaseRequestQueryService(PurchaseRequestQueryService $purchaseRequestQueryService)
    {
        $this->purchaseRequestQueryService = $purchaseRequestQueryService;
    }
    public function searchPurchaseRequestRemain(array $params, array &$context = null)
    {}

    public function getPurchaseRequestRemain($id)
    {}

}
