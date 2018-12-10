<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class RevenueQueryService extends RevenueQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Revenue');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:RevenueDetail');
        $this->taxInvoiceDetailStatusChangedRepository = $doctrine->getRepository('ErpDocumentBundle:TaxInvoiceDetailStatusChanged');
    }

    /** @required */
    public function setTaxInvoiceQueryService(TaxInvoiceQueryService $taxInvoiceQueryService)
    {
        $this->taxInvoiceQueryService = $taxInvoiceQueryService;
    }
}
