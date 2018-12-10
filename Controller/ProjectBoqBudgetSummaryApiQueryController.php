<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * ProjectBoqSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/project-boq-budget-summary")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ProjectBoqBudgetSummaryApiQueryController extends FOSRestController
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqBudgetSummaryQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqBudgetSummaryQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
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
        $item = $this->domainQuery->getProjectBoqDataSummary($id);

        return $this->view(['data' => $item], 200);
    }
}
