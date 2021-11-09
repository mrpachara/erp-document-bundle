<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\TaxInvoiceQuery as QueryInterface;

abstract class TaxInvoiceQuery extends IncomeQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $billingNoteDetailStatusChangedRepository;

    /** @var BillingNoteQueryService */
    protected $billingNoteQueryService;

    public function getBillingNoteDetailStatusChangedQueryBuilder($alias)
    {
        $activeTaxInvoiceQb = $this->getAliveDocumentQueryBuilder('_activeDocument');
        $statusChangedQb = $this->billingNoteDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
        ->innerJoin(
            'ErpDocumentBundle:TaxInvoiceDetail', '_taxInvoiceDetail',
            'WITH', '_statusChanged = _taxInvoiceDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:TaxInvoice',
                '_taxInvoice',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_taxInvoiceDetail.income', '_taxInvoice'),
                    $statusChangedQb->expr()->in(
                        '_taxInvoice.id',
                        $activeTaxInvoiceQb->select('_activeDocument.id')->getDQL()
                        )
                    )
                )
                ->where($statusChangedQb->expr()->eq('_statusChanged.billingNoteDetail', $alias))
                ;
    }

    public function searchBillingNoteRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        $statusChangedQb = $this->getBillingNoteDetailStatusChangedQueryBuilder('_detail');

        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->billingNoteQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
        ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        ->innerJoin('_entity.details', '_detail')
        ->leftJoin(
            'ErpDocumentBundle:BillingNoteDetailStatusChanged',
            '_activeStatusChanged',
            'WITH',
            $qb->expr()->andX(
                $qb->expr()->eq('_detail', '_activeStatusChanged.billingNoteDetail'),
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

    public function getBillingNoteRemain($id)
    {
        $billingNote = $this->billingNoteQueryService->find($id);
        if (empty($billingNote)) {
            return $billingNote;
        }

        $statusChangedQb = $this->getBillingNoteDetailStatusChangedQueryBuilder('_billingNoteDetail');

        $qb = $this->billingNoteQueryService->createDetailQueryBuilder('_billingNoteDetail');
        $qb
        ->innerJoin(
            'ErpDocumentBundle:BillingNote', '_billingNote',
            'WITH', '_billingNoteDetail.income = _billingNote'
            )
            ->leftJoin(
                'ErpDocumentBundle:BillingNoteDetailStatusChanged', '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_billingNoteDetail', '_activeStatusChanged.billingNoteDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                        )
                    )
                )
                ->andWhere('_activeStatusChanged IS NULL')

                ->andWhere('_billingNote = :billingNoteId')
                ->setParameter('billingNoteId', $billingNote->getId())
                ;

                $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
                if(count($details) == 0) return null;
                $billingNote->setDetails($details);

                return $billingNote;
    }
}
