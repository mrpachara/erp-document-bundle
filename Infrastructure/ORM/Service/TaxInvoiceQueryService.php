<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class TaxInvoiceQueryService extends TaxInvoiceQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:TaxInvoice');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:TaxInvoiceDetail');
        $this->billingNoteDetailStatusChangedRepository = $doctrine->getRepository('ErpDocumentBundle:BillingNoteDetailStatusChanged');
    }

    /** @required */
    public function setBillingNoteQueryService(BillingNoteQueryService $billingNoteQueryService)
    {
        $this->billingNoteQueryService = $billingNoteQueryService;
    }
}
