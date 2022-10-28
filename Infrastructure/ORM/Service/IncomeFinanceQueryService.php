<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class IncomeFinanceQueryService extends IncomeFinanceQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:IncomeFinance');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:IncomeDetail');
    }
}
