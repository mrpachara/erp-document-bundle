<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class BillingNoteQueryService extends BillingNoteQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:BillingNote');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:BillingNoteDetail');
    }

    /** @required */
    public function setDeliveryNoteQueryService(DeliveryNoteQueryService $deliveryNoteQueryService)
    {
        $this->deliveryNoteQueryService = $deliveryNoteQueryService;
    }
}
