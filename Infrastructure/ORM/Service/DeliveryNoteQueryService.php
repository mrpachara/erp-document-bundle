<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class DeliveryNoteQueryService extends DeliveryNoteQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:DeliveryNote');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:DeliveryNoteDetail');
    }
}
