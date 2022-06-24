<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqContractSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Erp\Bundle\DocumentBundle\Entity\DetailStatusChanged;

abstract class ProjectBoqContractSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var IncomeQuery */
    protected $incomeQuery;

    public function getProjectBoqDataSummary($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq */
        $projectBoq = $this->projectBoqRepository->find($id);

        $transActivePurchaseQb = $this->incomeQuery->getAliveDocumentQueryBuilder('_transActiveDocument');
        $transIncomeDetailQb = $this->incomeQuery->createDetailQueryBuilder('_transIncomeDetail');

        $transIncomeDetailQb
            ->innerJoin(
                '_transIncomeDetail.income',
                '_transIncome',
                'WITH',
                $transIncomeDetailQb->expr()->in(
                    '_transIncome.id',
                    $transActivePurchaseQb->select('_transActiveDocument.id')->getDQL()
                )
            )
            ->innerJoin(
                '_transIncomeDetail.statusChanged',
                '_transIncomeDetail_statusChanged',
                'WITH',
                $transIncomeDetailQb->expr()->in(
                    '_transIncomeDetail_statusChanged.type',
                    [
                        $transIncomeDetailQb->expr()->literal(DetailStatusChanged::FINISH),
                        $transIncomeDetailQb->expr()->literal(DetailStatusChanged::REMOVED),
                        // TODO: add rule for DetailStatusChanged::SPRITTED
                    ]
                )
            );

        $activeIncomeQb = $this->incomeQuery->getAliveDocumentQueryBuilder('_activeDocument');
        $incomeDetailQb = $this->incomeQuery->createDetailQueryBuilder('_incomeDetail');

        $excepts = array_map(function ($value) use ($incomeDetailQb) {
            return $incomeDetailQb->expr()->literal($value);
        }, $excepts);
        $incomeDetailQb
            ->innerJoin(
                '_incomeDetail.income',
                '_income',
                'WITH',
                $incomeDetailQb->expr()->andX(
                    $incomeDetailQb->expr()->in(
                        '_income.id',
                        $activeIncomeQb->select('_activeDocument.id')->getDQL()
                    ),
                    (empty($excepts)) ? '1 = 1' :
                        $incomeDetailQb->expr()->notIn(
                            '_income.id',
                            $excepts
                        )
                )
            )
            ->andWhere(
                $incomeDetailQb->expr()->not(
                    $incomeDetailQb->expr()->exists(
                        $transIncomeDetailQb
                            ->andWhere('_incomeDetail.id = _transIncomeDetail_statusChanged.detail')
                            ->getDQL()
                    )
                )
            )
            ->andWhere('_income.boq = :boqId')
            ->setParameter('boqId', $projectBoq->getId());

        $incomeDetails = $incomeDetailQb->getQuery()->getResult();

        $projectBoq->value = [
            'delivery' => [
                'approved' => 0,
                'nonapproved' => 0,
            ],
            'billing' => [
                'approved' => 0,
                'nonapproved' => 0,
            ],
            'taxinvoice' => [
                'approved' => 0,
                'nonapproved' => 0,
            ],
            'revenue' => [
                'approved' => 0,
                'nonapproved' => 0,
            ],
        ];

        foreach ($incomeDetails as $incomeDetail) {
            $income = $incomeDetail->getIncome();

            if ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\DeliveryNoteDetail) {
                $projectBoq->value['delivery'][$income->getApproved() ? 'approved' : 'nonapproved'] += $incomeDetail->getTotal();
            } elseif ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\BillingNoteDetail) {
                $projectBoq->value['billing'][$income->getApproved() ? 'approved' : 'nonapproved'] += $incomeDetail->getTotal();
            } elseif ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\TaxInvoiceDetail) {
                $projectBoq->value['taxinvoice'][$income->getApproved() ? 'approved' : 'nonapproved'] += $incomeDetail->getTotal();
            } elseif ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\RevenueDetail) {
                $projectBoq->value['revenue'][$income->getApproved() ? 'approved' : 'nonapproved'] += $incomeDetail->getTotal();
            }
        }

        return $projectBoq;
    }

    function getProjectBoqsSummary($idProject, $excepts = null)
    {
        $projectBoqs = $this->projectBoqRepository->findByProject($idProject);

        foreach ($projectBoqs as $projectBoq) {
            $this->getProjectBoqDataSummary($projectBoq->getId(), $excepts);
        }

        return $projectBoqs;
    }

    // public function getIncomeDetailActive($id, $excepts = null)
    // {
    //     $excepts = (array)$excepts;
    //     /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq */
    //     $projectBoq = $this->projectBoqRepository->find($id);

    //     $activeIncomeQb = $this->incomeQuery->getAliveDocumentQueryBuilder('_activeDocument');
    //     $incomeDetailQb = $this->incomeQuery->createDetailQueryBuilder('_incomeDetail');
    //     $excepts = array_map(function ($value) use ($incomeDetailQb) {
    //         return $incomeDetailQb->expr()->literal($value);
    //     }, $excepts);
    //     $incomeDetailQb
    //         ->select('')
    //         ->innerJoin(
    //             '_incomeDetail.income',
    //             '_income',
    //             'WITH',
    //             $incomeDetailQb->expr()->andX(
    //                 $incomeDetailQb->expr()->in(
    //                     '_income.id',
    //                     $activeIncomeQb->select('_activeDocument.id')->getDQL()
    //                 ),
    //                 (empty($excepts)) ? '1 = 1' :
    //                     $incomeDetailQb->expr()->notIn(
    //                         '_income.id',
    //                         $excepts
    //                     )
    //             )
    //         )
    //         ->innerJoin('_income.boq', '_boq')
    //         ->andWhere('_boq = :boqId')
    //         ->setParameter('boqId', $projectBoq->getId());

    //     $incomeDetails = $incomeDetailQb->getQuery()->getResult();

    //     return $incomeDetails;
    // }

    public function getIncomeActiveByBoq($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq */
        $projectBoq = $this->projectBoqRepository->find($id);

        $activeIncomeQb = $this->incomeQuery->getAliveDocumentQueryBuilder('_income');
        $excepts = array_map(function ($value) use ($activeIncomeQb) {
            return $activeIncomeQb->expr()->literal($value);
        }, $excepts);
        $activeIncomeQb
            ->innerJoin('_income.boq', '_boq')
            ->andWhere('_boq = :boqId')
            ->andWhere((empty($excepts)) ? '1 = 1' :
                    $activeIncomeQb->expr()->notIn(
                        '_income.id',
                        $excepts
                    )
            )
            ->setParameter('boqId', $projectBoq->getId());

        $incomes = $activeIncomeQb->getQuery()->getResult();

        return $incomes;
    }
}
