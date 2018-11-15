<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ProjectBoqSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/project-boq-summary")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ProjectBoqSummaryApiQueryController extends FOSRestController
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    /**
     * get action
     *
     * @Rest\Get("/project/{idProject}")
     *
     * @param string $id
     * @param ServerRequestInterface $request
     */
    public function listAction($idProject, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $excepts = (empty($query['excepts']))? null : $query['excepts'];
        $items = $this->domainQuery->getProjectBoqsSummary($idProject, $excepts);

        return $this->view(['data' => $items], 200);
    }

    /**
     * get action
     *
     * @Rest\Get("/{id}")
     *
     * @param string $id
     * @param ServerRequestInterface $request
     */
    public function getAction($id, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $excepts = (empty($query['excepts']))? null : $query['excepts'];
        $item = $this->domainQuery->getProjectBoqDataSummary($id, $excepts);

        return $this->view(['data' => $item], 200);
    }
}
