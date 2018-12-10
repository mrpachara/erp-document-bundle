<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqBudgetSummaryQuery as QueryInterface;

use Doctrine\ORM\EntityRepository;
use Erp\Bundle\MasterBundle\Domain\CQRS\ProjectBoqQuery;

abstract class ProjectBoqBudgetSummaryQuery implements QueryInterface
{
    /** @var EntityRepository */
    protected $projectBoqDataRepository;

    /** @var EntityRepository */
    protected $projectRepository;

    /** @var ProjectBoqQuery */
    protected $projectBoqQuery;

    public function getProjectBoqDataSummary($id)
    {
        /** @var \Erp\Bundle\MasterBundle\Entity\Project */
        $project = $this->projectRepository->find($id);
        
        $projectBoqQb = $this->projectBoqQuery->createQueryBuilder('_projectBoq');
        $projectBoqQb
            ->select('_project.id AS id,SUM(_budgets.budget) AS total')
            ->innerJoin('_projectBoq.budgets','_budgets')
            ->innerJoin('_projectBoq.project','_project')
                ->andwhere('_project.id = :projectId')
 
                ->setParameter('projectId', $project->getId())
                ;
                
                $projectBoqs = $projectBoqQb->getQuery()->getResult();

                
                return $projectBoqs;
    }
    
}
