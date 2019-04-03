<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqWithSummaryQuery as QueryInterface;
use Doctrine\ORM\EntityRepository;

class ProjectBoqWithSummaryQueryService implements QueryInterface
{

    /** @var EntityRepository */
    protected $projectBoqRepository;

    /** @var \Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service\ProjectBoqSummaryQueryService */
    protected $costService;

    /** @var \Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service\ProjectBoqContractSummaryQueryService */
    protected $valueService;

    function __construct(
        \symfony\Bridge\Doctrine\RegistryInterface $doctrine,
        \Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service\ProjectBoqSummaryQueryService $costService,
        \Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service\ProjectBoqContractSummaryQueryService $valueService
    )
    {
        $this->projectBoqRepository = $doctrine->getRepository('ErpMasterBundle:ProjectBoq');
        $this->costService = $costService;
        $this->valueService = $valueService;
    }

    public function getAllProjectBoq($idProject, $excepts = null)
    {
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq[] */
        $boqs = $this->projectBoqRepository->findByProject($idProject);

        foreach($boqs as $boq) {
            $this->valueService->getProjectBoqDataSummary($boq->getId(), $excepts);
            $this->costService->getProjectBoqDataSummary($boq->getId(), $excepts);
        }

        return $boqs;
    }
    
    public function getProjectBoq($idProject, $id, $excepts = null)
    {
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq */
        $boq = $this->projectBoqRepository->findOneBy(['id' => $id, 'project' => $idProject]);
        
        if(!empty($boq)) {
            $this->valueService->getProjectBoqDataSummary($boq->getId(), $excepts);
            $this->costService->getProjectBoqDataSummary($boq->getId(), $excepts);
        }
        
        return $boq;
    }

    public function getAllProjectContractByBoq($idProject, $id, $excepts = null)
    {
        /** @var \Erp\Bundle\MasterBundle\Entity\ProjectBoq */
        $boq = $this->projectBoqRepository->findOneBy(['id' => $id, 'project' => $idProject]);
        
        if(!empty($boq)) {
            return $this->valueService->getIncomeActiveByBoq($boq->getId(), $excepts);
        }
        
        return [];
    }
}
