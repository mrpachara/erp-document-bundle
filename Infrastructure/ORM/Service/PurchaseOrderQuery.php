<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery as QueryInterface;

abstract class PurchaseOrderQuery extends PurchaseQuery implements QueryInterface
{
    /** @var PurchaseRequestQueryService */
    protected $purchaseRequestQueryService;

    public function searchPurchaseRequestRemain(array $params, array &$context = null)
    {
        return $this->purchaseRequestQueryService->searchRemain($params, $context);
    }

    public function getPurchaseRequestRemain($id)
    {
        // $purchaseRequest = $this->purchaseRequestQueryService->find($id);
        // if (empty($purchaseRequest)) {
        //     return $purchaseRequest;
        // }

        // $detailAlias = '_purchase_detail';
        // $detailRemainQb = $this->purchaseRequestQueryService->createDetailQueryBuilder($detailAlias);
        // $detailRemainQb = $this->purchaseRequestQueryService->assignDetailRemainQuery($detailRemainQb, $detailAlias);

        // $expr = $detailRemainQb->expr();
        // $detailRemainQb->andWhere($expr->eq("{$detailAlias}.purchase", ':purchaseRequest'));
        // $detailRemainQb->setParameter('purchaseRequest', $purchaseRequest);

        // $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($detailRemainQb->getQuery()->getResult());
        // if(count($details) == 0) return null;
        // $purchaseRequest->setDetails($details);

        // return $purchaseRequest;

        return $this->purchaseRequestQueryService->getRemain($id);
    }
}
