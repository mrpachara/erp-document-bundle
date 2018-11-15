<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class IncomeQueryService extends IncomeQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Income');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:IncomeDetail');
    }
}
