<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ServerRequestInterface;


/**
 * ProjectSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/project-boq-with-summary/{idProject}")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ProjectBoqWithSummaryApiQueryController {
    
    /** @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqWithSummaryQuery */
    protected $domainQuery;
    public function __construct(
        \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqWithSummaryQuery $domainQuery
         )
    {
        $this->domainQuery = $domainQuery;
    }
    
    /**
     * @Rest\Get("")
     */
    public function listAction($idProject, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $excepts = (empty($query['excepts']))? null : $query['excepts'];
        
        return [
            'data' => $this->domainQuery->getAllProjectBoq($idProject, $excepts),
        ];
        
    }
    
    /**
     * @Rest\Get("/{id}")
     */
    public function getAction($idProject, $id, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $excepts = (empty($query['excepts']))? null : $query['excepts'];
        return [
            'data' => $this->domainQuery->getProjectBoq($idProject, $id, $excepts),
        ];
        
    }


}
