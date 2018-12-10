<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ProjectDateSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/project-date-summary")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ProjectDateSummaryApiQueryController extends FOSRestController
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectDateSummaryQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectDateSummaryQuery $domainQuery)
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
        $items = $this->domainQuery->getProjectDateSummary($idProject, $excepts);

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
        $item = $this->domainQuery->getProjectDateSummary($id);

        return $this->view(['data' => $item], 200);
    }
}
