<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectDateSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Erp\Bundle\MasterBundle\Infrastructure\ORM\Service\ProjectQuery;


abstract class ProjectDateSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var ProjectQuery */
    protected $projectQuery;

    public function getProjectDateSummary($id, $excepts = null)
    {
        $excepts = (array)$excepts;
        /** @var \Erp\Bundle\MasterBundle\Entity\Project */
        $project = $this->projectRepository->find($id);

        $activeProjectQb = $this->projectQuery->getActiveMasterQueryBuilder();
        $projectBoqQb = $this->projectQuery->createDetailQueryBuilder('_projectBoq');
        $excepts = array_map(function($value) use ($projectBoqQb) {
            return $projectBoqQb->expr()->literal($value);
        }, $excepts);
        $projectBoqQb
            ->innerJoin(
                '_projectBoq.project',
                '_project',
                'WITH',
                $projectBoqQb->expr()->andX(
                    $projectBoqQb->expr()->in(
                        '_project.id',
                        $activeProjectQb->select('_activeMaster.id')->getDQL()
                    ),
                    (empty($excepts))? '1 = 1' :
                    $projectBoqQb->expr()->notIn(
                        '_project.id',
                        $excepts
                    )
                )
            )
            ->andWhere('_projectBoq.project = :projectId')
            ->setParameter('projectId', $project->getId())
        ;

            
            $projectBoqQb
            ->select('_project.id AS id', 'MIN(_projectBoq.boqStartDate) AS boqStartDate', 'MAX(_projectBoq.boqFinishDate) AS boqFinishDate')
            ->innerJoin('_projectBoq.project', '_project')
            ->innerJoin('_project.project', '_project')
            ->groupBy('_project.id')
            ;
            
            $projectBoqQb->setParameters($activeProjectQb->getParameters());
            $projectBoq = $projectBoqQb->getQuery()->getResult();
            
            return $projectBoq;
        }
    }
