<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class BillingNoteQueryService extends BillingNoteQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:BillingNote');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:BillingNoteDetail');
        $this->deliveryNoteDetailStatusChangedRepository = $doctrine->getRepository('ErpDocumentBundle:DeliveryNoteDetailStatusChanged');
    }

    /** @required */
    public function setDeliveryNoteQueryService(PurchaseRequestQueryService $deliveryNoteQueryService)
    {
        $this->deliveryNoteQueryService = $deliveryNoteQueryService;
    }
}
