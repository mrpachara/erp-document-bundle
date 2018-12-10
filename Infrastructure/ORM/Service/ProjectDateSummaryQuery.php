<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectDateSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Erp\Bundle\MasterBundle\Domain\CQRS\ProjectBoqQuery;


abstract class ProjectDateSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectRepository;

    /** @var EntityRepository */
    protected $projectBoqRepository;
    
    /** @var ProjectBoqQuery */
    protected $projectBoqQuery;
    
    public function getProjectDateSummary($id)
    {
        /** @var \Erp\Bundle\MasterBundle\Entity\Project */
        $project = $this->projectRepository->find($id);
        $projectBoqQb = $this->projectBoqQuery->createQueryBuilder('_projectBoq');
        $projectBoqQb
        ->select('_project.id AS id', 'MIN(_projectBoq.boqStartDate) AS boqStartDate', 
                 'MAX(_projectBoq.boqFinishDate) AS boqFinishDate', 'SUM(_projectBoq.boqContract) AS boqContract')
        ->innerJoin(
            '_projectBoq.project',
            '_project'
            )
        ->andwhere('_project.id = :projectId')
        ->setParameter('projectId', $project)
            ;

            
            $projectBoqs = $projectBoqQb->getQuery()->getResult();
            
            return $projectBoqs;
        }
    }
