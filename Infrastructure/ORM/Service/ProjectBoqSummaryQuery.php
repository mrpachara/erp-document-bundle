<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Erp\Bundle\DocumentBundle\Entity\DetailStatusChanged;
use Erp\Bundle\DocumentBundle\Entity\PurchaseDetail;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqData;
use TypeError;

abstract class ProjectBoqSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectBoqDataRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var PurchaseQuery */
    protected $purchaseQuery;

    /**
     * Prepare project boq in deep.
     */
    private function prepareDeepProjectBoqData(ProjectBoqData $boqData, array $ids): array
    {
        if (\in_array($boqData->getId(), $ids)) {
            return $ids;
        }

        $ids[] = $boqData->getId();

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

        foreach ($boqData->getChildren() as $child) {
            $ids = $this->prepareDeepProjectBoqData($child, $ids);
        }

        return $ids;
    }

    private function setDeepCost(ProjectBoqData $boqData, array $purchaseDetailMap): void
    {
        $purchaseDetails = $purchaseDetailMap[$boqData->getId()] ?? [];

        foreach ($purchaseDetails as list(
            0 => $purchaseDetail,
            'budgetTypeId' => $budgetTypeId,
            'isApproved' => $isApproved
        )) {
            // NOTE: $budgets refers to objects so not need to set back.
            $budgets = $boqData->getBudgets();

            if ($purchaseDetail['dtype'] === 'purchaserequestdetail') {
                $budgets[$budgetTypeId]->cost['request'][$isApproved ? 'approved' : 'nonapproved'] += $purchaseDetail['total'];
            } elseif ($purchaseDetail['dtype'] === 'purchaseorderdetail') {
                $budgets[$budgetTypeId]->cost['order'][$isApproved ? 'approved' : 'nonapproved'] += $purchaseDetail['total'];
            } elseif ($purchaseDetail['dtype'] === 'expensedetail') {
                $budgets[$budgetTypeId]->cost['expense'][$isApproved ? 'approved' : 'nonapproved'] += $purchaseDetail['total'];
            } else {
                throw new TypeError("Unknown type for '${$purchaseDetail['dtype']}'");
            }
        }

        foreach ($boqData->getChildren() as $child) {
            $this->setDeepCost($child, $purchaseDetailMap);

            foreach ($child->getBudgets() as $childBudget) {
                foreach ($childBudget->cost as $costKey => $costWithTypes) {
                    foreach ($costWithTypes as $costType => $costValue)
                        $boqData->getBudgets()[$childBudget->getBoqBudgetType()->getId()]->cost[$costKey][$costType] += $costValue;
                }
            }
        }
    }

    public function getProjectBoqDataSummary($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoqData */
        $boqData = $this->projectBoqDataRepository->find($id);

        $ids = $this->prepareDeepProjectBoqData($boqData, []);

        $transActivePurchaseQb = $this->purchaseQuery->getAliveDocumentQueryBuilder('_transActiveDocument');
        $transPurchaseDetailQb = $this->purchaseQuery->createDetailQueryBuilder('_transPurchaseDetail');

        $transPurchaseDetailQb
            ->innerJoin(
                '_transPurchaseDetail.purchase',
                '_transPurchase',
                'WITH',
                $transPurchaseDetailQb->expr()->in(
                    '_transPurchase.id',
                    $transActivePurchaseQb->select('_transActiveDocument.id')->getDQL()
                )
            )
            ->innerJoin(
                '_transPurchaseDetail.statusChanged',
                '_transPurchaseDetail_statusChanged',
                'WITH',
                $transPurchaseDetailQb->expr()->in(
                    '_transPurchaseDetail_statusChanged.type',
                    [
                        $transPurchaseDetailQb->expr()->literal(DetailStatusChanged::FINISH),
                        $transPurchaseDetailQb->expr()->literal(DetailStatusChanged::REMOVED),
                        // TODO: add rule for DetailStatusChanged::SPRITTED
                    ]
                )
            );

        $activePurchaseQb = $this->purchaseQuery->getAliveDocumentQueryBuilder('_activeDocument');
        $purchaseDetailQb = $this->purchaseQuery->createDetailQueryBuilder('_purchaseDetail');

        $excepts = array_map(function ($value) use ($purchaseDetailQb) {
            return $purchaseDetailQb->expr()->literal($value);
        }, $excepts);
        $ids = array_map(function ($value) use ($purchaseDetailQb) {
            return $purchaseDetailQb->expr()->literal($value);
        }, $ids);
        $purchaseDetailQb
            ->select('_purchaseDetail, _boqData , _budgetType.id AS budgetTypeId, _purchase.approved as isApproved')
            ->innerJoin(
                '_purchaseDetail.purchase',
                '_purchase',
                'WITH',
                $purchaseDetailQb->expr()->andX(
                    $purchaseDetailQb->expr()->in(
                        '_purchase.id',
                        $activePurchaseQb->select('_activeDocument.id')->getDQL()
                    ),
                    (empty($excepts)) ? '1 = 1' :
                        $purchaseDetailQb->expr()->notIn(
                            '_purchase.id',
                            $excepts
                        )
                )
            )
            ->innerJoin('_purchaseDetail.boqData', '_boqData')
            ->innerJoin('_purchase.budgetType', '_budgetType')
            ->andWhere(
                $purchaseDetailQb->expr()->not(
                    $purchaseDetailQb->expr()->exists(
                        $transPurchaseDetailQb
                            ->andWhere('_purchaseDetail.id = _transPurchaseDetail_statusChanged.detail')
                            ->getDQL()
                    )
                )
            )
            // TMP-NOTE: start edit
            ->andWhere(
                (empty($ids)) ? '1 = 0' :
                    $purchaseDetailQb->expr()->in('_purchaseDetail.boqData', $ids)
            );
        // ->andWhere('_purchaseDetail.boqData = :boqDataId')
        // ->setParameter('boqDataId', $boqData->getId());

        $purchaseDetails = $purchaseDetailQb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        // foreach ($purchaseDetails as $key => $value) {
        //     dump($key);
        //     dump($value);
        //     break;
        // }

        $purchaseDetailMap = \array_reduce($purchaseDetails, function (array $carry, array $purchaseDetail) {
            $id = $purchaseDetail[0]['boqData']['id'];

            if (empty($carry[$id])) {
                $carry[$id] = [$purchaseDetail];
            } else {
                $carry[$id][] = $purchaseDetail;
            }

            return $carry;
        }, []);

        // foreach ($boqData->getBudgets() as $budget) {
        //     $budget->cost = [
        //         'request' => [
        //             'approved' => 0,
        //             'nonapproved' => 0,
        //         ],
        //         'order' => [
        //             'approved' => 0,
        //             'nonapproved' => 0,
        //         ],
        //         'expense' => [
        //             'approved' => 0,
        //             'nonapproved' => 0,
        //         ],
        //     ];
        // }

        // foreach ($purchaseDetails as $purchaseDetail) {
        //     $purchase = $purchaseDetail->getPurchase();
        //     // NOTE: $budgets refers to objects so not need to set back.
        //     $budgets = $boqData->getBudgets();

        //     if ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\PurchaseRequestDetail) {
        //         $budgets[$purchase->getBudgetType()->getId()]->cost['request'][$purchase->getApproved() ? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
        //     } elseif ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\PurchaseOrderDetail) {
        //         $budgets[$purchase->getBudgetType()->getId()]->cost['order'][$purchase->getApproved() ? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
        //     } elseif ($purchaseDetail instanceof \Erp\Bundle\DocumentBundle\Entity\ExpenseDetail) {
        //         $budgets[$purchase->getBudgetType()->getId()]->cost['expense'][$purchase->getApproved() ? 'approved' : 'nonapproved'] += $purchaseDetail->getTotal();
        //     }
        // }

        // foreach ($boqData->getChildren() as $child) {
        //     $childResult = $this->getProjectBoqDataSummary($child->getId());

        //     foreach ($childResult->getBudgets() as $childBudget) {
        //         foreach ($childBudget->cost as $costKey => $costWithTypes) {
        //             foreach ($costWithTypes as $costType => $costValue)
        //                 $boqData->getBudgets()[$childBudget->getBoqBudgetType()->getId()]->cost[$costKey][$costType] += $costValue;
        //         }
        //     }
        // }

        $this->setDeepCost($boqData, $purchaseDetailMap);

        return $boqData;
    }

    function getProjectBoqsSummary($idProject, $excepts = null)
    {
        $projectBoqs = $this->projectBoqRepository->findByProject($idProject);

        foreach ($projectBoqs as $projectBoq) {
            $this->getProjectBoqDataSummary($projectBoq->getId(), $excepts);
        }

        return $projectBoqs;
    }
}
