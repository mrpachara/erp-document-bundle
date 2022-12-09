<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use Erp\Bundle\DocumentBundle\Authorization\ProjectBoqWithSummaryAuthorization;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * ProjectBoqWithSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/project-boq-with-summary/{idProject}")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class ProjectBoqWithSummaryApiQueryController
{

    /** @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqWithSummaryQuery */
    protected $domainQuery;

    /** @var ProjectBoqWithSummaryAuthorization */
    protected $authorization;

    public function __construct(
        \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqWithSummaryQuery $domainQuery,
        ProjectBoqWithSummaryAuthorization $authorization
    ) {
        $this->domainQuery = $domainQuery;
        $this->authorization = $authorization;
    }

    /**
     * @Rest\Get("")
     */
    public function listAction($idProject, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $result = null;

        if ($this->authorization->list()) {
            $excepts = (empty($query['excepts'])) ? null : $query['excepts'];
            $projectBoqs = $this->domainQuery->getAllProjectBoq($idProject, $excepts);

            if ($this->authorization->list($projectBoqs)) {
                $result = $projectBoqs;
            }
        } else if ($this->authorization->listWithoutValue()) {
            $projectBoqs = $this->domainQuery->getAllProjectBoqWithoutValue($idProject);

            if ($this->authorization->listWithoutValue($projectBoqs)) {
                $result = $projectBoqs;
            }
        }

        if ($result !== null) {
            return [
                'data' => $result,
            ];
        } else {
            throw new AccessDeniedException("List is not allowed.");
        }
    }

    /**
     * @Rest\Get("/{id}")
     */
    public function getAction($idProject, $id, ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $result = null;

        if ($this->authorization->get()) {
            $excepts = (empty($query['excepts'])) ? null : $query['excepts'];
            $projectBoq = $this->domainQuery->getProjectBoq($idProject, $id, $excepts);

            if ($this->authorization->get($projectBoq)) {
                $result = $projectBoq;
            }
        } else if ($this->authorization->getWithoutValue()) {
            $projectBoq = $this->domainQuery->getProjectBoqWithoutValue($idProject, $id);

            if ($this->authorization->getWithoutValue($projectBoq)) {
                $result = $projectBoq;
            }
        }

        if ($result !== null) {
            return [
                'data' => $result,
            ];
        } else {
            throw new AccessDeniedException("Get is not allowed.");
        }
    }
}
