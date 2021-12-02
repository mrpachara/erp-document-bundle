<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

class ExpenseQueryService extends ExpenseQuery
{
    /** @required */
    public function setRepository(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->repository = $doctrine->getRepository('ErpDocumentBundle:Expense');
        $this->detailRepository = $doctrine->getRepository('ErpDocumentBundle:ExpenseDetail');
    }

    /** @required */
    public function setPurchaseOrderExpenseQueryService(PurchaseOrderQueryService $purchaseOrderQueryService)
    {
        $this->purchaseOrderQueryService = $purchaseOrderQueryService;
    }
}
