<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqContractSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery;

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
        
        $activeIncomeQb = $this->incomeQuery->getActiveDocumentQueryBuilder();
        $incomeDetailQb = $this->incomeQuery->createDetailQueryBuilder('_incomeDetail');
        $excepts = array_map(function($value) use ($incomeDetailQb) {
            return $incomeDetailQb->expr()->literal($value);
        }, $excepts);
            $incomeDetailQb
            ->select('')
            ->innerJoin(
            '_incomeDetail.income',
            '_income',
                'WITH',
                $incomeDetailQb->expr()->andX(
                    $incomeDetailQb->expr()->in(
                        '_income.id',
                        $activeIncomeQb->select('_activeDocument.id')->getDQL()
                        ),
                    (empty($excepts))? '1 = 1' :
                    $incomeDetailQb->expr()->notIn(
                        '_income.id',
                        $excepts
                        )
                    )
                )
            ->innerJoin('_income.boq','_boq')
            ->andWhere('_boq = :boqId')
            ->setParameter('boqId', $projectBoq->getId())
        ;

            $incomeDetails = $incomeDetailQb->getQuery()->getResult();

            $projectBoq->value = [
                'delivery' => 0,
                'billing' => 0,
                'taxinvoice' => 0
            ];

            foreach ($incomeDetails as $incomeDetail) {
                $income = $incomeDetail->getIncome();
                
                if ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\DeliveryNoteDetail) {
                    if ($income->updatable()) {
                        $projectBoq->value['delivery'] += $incomeDetail->getTotal();
                    }
                } elseif ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\BillingNoteDetail) {
                    if ($income->updatable()) {
                        $projectBoq->value['billing'] += $incomeDetail->getTotal();
                    }
                } elseif ($incomeDetail instanceof \Erp\Bundle\DocumentBundle\Entity\TaxInvoiceDetail) {
                        $projectBoq->value['taxinvoice'] += $incomeDetail->getTotal();
                }
            }
            
            
            return $projectBoq;
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
