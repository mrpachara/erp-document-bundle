<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class DocumentQueryService extends DocumentQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Document');
    }
}
