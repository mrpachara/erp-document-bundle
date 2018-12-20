<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;

abstract class ProjectBoqSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectBoqDataRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var PurchaseQuery */
    protected $purchaseQuery;

    public function getProjectBoqDataSummary($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoqData */
        $boqData = $this->projectBoqDataRepository->find($id);

        $activePurchaseQb = $this->purchaseQuery->getActiveDocumentQueryBuilder();
        $purchaseDetailQb = $this->purchaseQuery->createDetailQueryBuilder('_purchaseDetail');
        $excepts = array_map(function($value) use ($purchaseDetailQb) {
            return $purchaseDetailQb->expr()->literal($value);
        }, $excepts);
        $purchaseDetailQb
            ->innerJoin(
                '_purchaseDetail.purchase',
                '_purchase',
                'WITH',
                $purchaseDetailQb->expr()->andX(
                    $purchaseDetailQb->expr()->in(
                        '_purchase.id',
                        $activePurchaseQb->select('_activeDocument.id')->getDQL()
                    ),
                    (empty($excepts))? '1 = 1' :
                    $purchaseDetailQb->expr()->notIn(
                        '_purchase.id',
                        $excepts
                    )
                )
            )
            ->andWhere('_purchaseDetail.boqData = :boqDataId')
            ->setParameter('boqDataId', $boqData->getId())
        ;

        $purchaseDetails = $purchaseDetailQb->getQuery()->getResult();

        foreach ($boqData->getBudgets() as $budget) {
            $budget->cost = [
                'request' => [
                    'approved' => 0,
                    'nonapproved' => 0,
                ],
                'order' => [
                    'approved' => 0,
                    'nonapproved' => 0,
                ],
                'expense' => [
                    'approved' => 0,
                    'nonapproved' => 0,
                ],
            ];
        }

        foreach ($purchaseDetails as $purchaseDetail) {
            $purchase = $purchaseDetail->getPurchase();
            $budgets = $boqData->getBudgets();

            if ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\PurchaseRequestDetail) {
                if ($purchase->updatable()) {
                    $budgets[$purchase->getBudgetType()->getId()]->cost['request'][$purchase->getApproved()? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
                }
            } elseif ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\PurchaseOrderDetail) {
                if ($purchase->updatable()) {
                    $budgets[$purchase->getBudgetType()->getId()]->cost['order'][$purchase->getApproved()? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
                }
            } elseif ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\ExpenseDetail) {
                $budgets[$purchase->getBudgetType()->getId()]->cost['expense'][$purchase->getApproved()? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
            }
        }

        foreach ($boqData->getChildren() as $child) {
            $childResult = $this->getProjectBoqDataSummary($child->getId());

            foreach ($childResult->getBudgets() as $childBudget) {
                foreach ($childBudget->cost as $costKey => $costValue) {
                    $boqData->getBudgets()[$childBudget->getBoqBudgetType()->getId()]->cost[$costKey] += $costValue;
                }
            }
        }

        return $boqData;
    }

    function getProjectBoqsSummary($idProject, $excepts = null)
    {
        $projectBoqs = $this->projectBoqRepository->findByProject($idProject);

        foreach($projectBoqs as $projectBoq) {
            $this->getProjectBoqDataSummary($projectBoq->getId(), $excepts);
        }

        return $projectBoqs;
    }
}
