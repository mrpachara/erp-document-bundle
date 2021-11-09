<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\RevenueQuery as QueryInterface;

abstract class RevenueQuery extends IncomeQuery implements QueryInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    protected $taxInvoiceDetailStatusChangedRepository;

    /** @var TaxInvoiceQueryService */
    protected $taxInvoiceQueryService;

    public function getTaxInvoiceDetailStatusChangedQueryBuilder($alias)
    {
        $activeRevenueQb = $this->getAliveDocumentQueryBuilder('_activeDocument');
        $statusChangedQb = $this->taxInvoiceDetailStatusChangedRepository->createQueryBuilder('_statusChanged');
        return $statusChangedQb
        ->innerJoin(
            'ErpDocumentBundle:RevenueDetail', '_revenueDetail',
            'WITH', '_statusChanged = _revenueDetail.statusChanged'
            )
            ->innerJoin(
                'ErpDocumentBundle:Revenue',
                '_revenue',
                'WITH',
                $statusChangedQb->expr()->andX(
                    $statusChangedQb->expr()->eq('_revenueDetail.income', '_revenue'),
                    $statusChangedQb->expr()->in(
                        '_revenue.id',
                        $activeRevenueQb->select('_activeDocument.id')->getDQL()
                        )
                    )
                )
                ->where($statusChangedQb->expr()->eq('_statusChanged.taxInvoiceDetail', $alias))
                ;
    }

    public function searchTaxInvoiceRemain(array $params, array &$context = null)
    {
        $context = (array)$context;
        $statusChangedQb = $this->getTaxInvoiceDetailStatusChangedQueryBuilder('_detail');

        /** @var \Doctrine\ORM\QueryBuilder */
        $qb = $this->taxInvoiceQueryService->searchQueryBuilder($params, '_entity', $context);
        $qb
        ->leftJoin('_entity.updatedBys', '_updatedBy', 'WITH', '_updatedBy.terminated IS NULL')
        ->innerJoin('_entity.details', '_detail')
        ->leftJoin(
            'ErpDocumentBundle:TaxInvoiceDetailStatusChanged',
            '_activeStatusChanged',
            'WITH',
            $qb->expr()->andX(
                $qb->expr()->eq('_detail', '_activeStatusChanged.taxInvoiceDetail'),
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

    public function getTaxInvoiceRemain($id)
    {
        $taxInvoice = $this->taxInvoiceQueryService->find($id);
        if (empty($taxInvoice)) {
            return $taxInvoice;
        }

        $statusChangedQb = $this->getTaxInvoiceDetailStatusChangedQueryBuilder('_taxInvoiceDetail');

        $qb = $this->taxInvoiceQueryService->createDetailQueryBuilder('_taxInvoiceDetail');
        $qb
        ->innerJoin(
            'ErpDocumentBundle:TaxInvoice', '_taxInvoice',
            'WITH', '_taxInvoiceDetail.income = _taxInvoice'
            )
            ->leftJoin(
                'ErpDocumentBundle:TaxInvoiceDetailStatusChanged', '_activeStatusChanged',
                'WITH',
                $qb->expr()->andX(
                    $qb->expr()->eq('_taxInvoiceDetail', '_activeStatusChanged.taxInvoiceDetail'),
                    $qb->expr()->in(
                        '_activeStatusChanged.id',
                        $statusChangedQb->select('_statusChanged.id')->getDQL()
                        )
                    )
                )
                ->andWhere('_activeStatusChanged IS NULL')

                ->andWhere('_taxInvoice = :taxInvoiceId')
                ->setParameter('taxInvoiceId', $taxInvoice->getId())
                ;

                $details = new \Erp\Bundle\CoreBundle\Collection\ArrayCollection($qb->distinct()->getQuery()->getResult());
                if(count($details) == 0) return null;
                $taxInvoice->setDetails($details);

                return $taxInvoice;
    }
}
