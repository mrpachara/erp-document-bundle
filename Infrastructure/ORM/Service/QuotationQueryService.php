<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class QuotationQueryService extends QuotationQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Quotation');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:QuotationDetail');

    }

}
