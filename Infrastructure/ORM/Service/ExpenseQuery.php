<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ExpenseQuery as QueryInterface;

abstract class ExpenseQuery extends PurchaseQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $purchaseOrderExpenseDetailStatusChangedRepository;

    /** @var PurchaseOrderQueryService */
    protected $purchaseOrderQueryService;

    public function getPurchaseOrderExpenseDetailStatusChangedQueryBuilder($alias)
    {
        $activeExpenseQb = $this->getActiveDocumentQueryBuilder();
        $statusChangedQb = $this->purchaseOrderExpenseDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
            ->innerJoin(
                'ErpDocumentBundle:ExpenseDetail', '_expenseDetail',
                'WITH', '_statusChanged = _expenseDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:Expense',
                '_expense',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_expenseDetail.purchase', '_expense'),
                    $statusChangedQb->expr()->in(
                        '_expense.id',
                        $activeExpenseQb->select('_activeDocument.id')->getDQL()
                    )
                )
            )
            ->where($statusChangedQb->expr()->eq('_statusChanged.purchaseOrderDetail', $alias))
        ;
    }

    public function searchPurchaseOrderExpenseRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        $statusChangedQb = $this->getPurchaseOrderExpenseDetailStatusChangedQueryBuilder('_detail');

        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->purchaseOrderQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
            ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
            ->innerJoin('_entity.details', '_detail')
            ->leftJoin(
                'ErpDocumentBundle:PurchaseOrderExpenseDetailStatusChanged',
                '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_detail', '_activeStatusChanged.purchaseOrderDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                    )
                )
            )

            ->andWhere('_activeStatusChanged IS NULL')

            ->andWhere('_entity.terminated IS NULL')
            ->andWhere('_updatedBy IS NULL')
            ->andWhere('_entity.approved = TRUE')
        ;

        return $this->qh->execute($qb->distinct()->getQuery(), $params, $context);
    }

    public function getPurchaseOrderExpenseRemain($id)
    {
        $purchaseOrder = $this->purchaseOrderQueryService->find($id);
        if (empty($purchaseOrder)) {
            return $purchaseOrder;
        }

        $statusChangedQb = $this->getPurchaseOrderExpenseDetailStatusChangedQueryBuilder('_purchaseOrderDetail');

        $qb = $this->purchaseOrderQueryService->createDetailQueryBuilder('_purchaseOrderDetail');
        $qb
            ->innerJoin(
                'ErpDocumentBundle:PurchaseOrder', '_purchaseOrder',
                'WITH', '_purchaseOrderDetail.purchase = _purchaseOrder'
            )
            ->leftJoin(
                'ErpDocumentBundle:PurchaseOrderExpenseDetailStatusChanged', '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_purchaseOrderDetail', '_activeStatusChanged.purchaseOrderDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                    )
                )
            )
            ->andWhere('_activeStatusChanged IS NULL')

            ->andWhere('_purchaseOrder = :purchaseOrderId')
            ->setParameter('purchaseOrderId', $purchaseOrder->getId())
        ;

        $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
        if(count($details) == 0) return null;
        $purchaseOrder->setDetails($details);

        return $purchaseOrder;
    }
}
