<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

use Psr\Http\Message\ServerRequestInterface;
use Erp\Bundle\DocumentBundle\Entity\Income;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;

/**
 * Income Api Query
 */
abstract class IncomeApiQuery extends DocumentApiQuery {
    use AssignWithUserSearchTrait;

    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\IncomeAuthorization
     */
    protected $authorization;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery
     */
    protected $domainQuery;

    /** @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqContractSummaryQuery-*/
    protected $projectBoqContractSummaryQuery;

    /** @required */
    public function setProjectBoqContractSummaryQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqContractSummaryQuery $projectBoqContractSummaryQuery)
    {
        $this->projectBoqContractSummaryQuery = $projectBoqContractSummaryQuery;
    }

    protected function getListWithUserRules()
    {
        return [
            'list-all' => function($grants) {
                return [];
            },
            'list-worker list-individual' => function($grants) {
                return [ServiceInterface::WORKER, ServiceInterface::OWNER];
            },
            'list-worker' => function($grants) {
                return [ServiceInterface::WORKER];
            },
            'list-individual' => function($grants) {
                return [ServiceInterface::OWNER];
            }
        ];
    }

    /**
     * list action
     *
     * @Rest\Get("")
     *
     * @param ServerRequestInterface $request
     */
    public function listAction(ServerRequestInterface $request)
    {
        $queryParams = $this->assignWithUserSearchRule(
            $request->getQueryParams(),
            $this->getListWithUserRules()
        );
        $request = $request->withQueryParams($queryParams);

        return parent::listAction($request);
    }

    protected function getGetWithUserRules()
    {
        return [
            'get-all' => function($grants) {
                return [];
            },
            'get-worker get-individual' => function($grants) {
                return [ServiceInterface::WORKER, ServiceInterface::OWNER];
            },
            'get-worker' => function($grants) {
                return [ServiceInterface::WORKER];
            },
            'get-individual' => function($grants) {
                return [ServiceInterface::OWNER];
            }
        ];
    }

    protected function getRemainWithUserRules()
    {
        return [
            'add-all' => function($grants) {
                return [];
            },
            'add-worker add-individual' => function($grants) {
                return [ServiceInterface::WORKER, ServiceInterface::OWNER];
            },
            'add-worker' => function($grants) {
                return [ServiceInterface::WORKER];
            },
            'add-individual' => function($grants) {
                return [ServiceInterface::OWNER];
            }
        ];
    }

    /**
     * get allowed projects
     *
     * @Rest\Get("/allowed-project")
     *
     * @param string $id
     * @param ServerRequestInterface $request
     */
    public function getAllAllowedProjects(ServerRequestInterface $request)
    {
        $queryParams = $this->assignWithUserSearchRule(
            $request->getQueryParams(),
            $this->getRemainWithUserRules()
        );

        return ['data' => $this->domainQuery->searchProjectWith($queryParams)];
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
        $queryParams = $this->assignWithUserSearchRule(
            $request->getQueryParams(),
            $this->getGetWithUserRules()
        );
        $request = $request->withQueryParams($queryParams);

        $response = parent::getAction($id, $request);

        $responseData = $response->getData();
        /** @var Income */
        $income = $responseData['data'];

        if (!empty($income)) {
            // TODO: check authentication
            $this->projectBoqContractSummaryQuery->getProjectBoqDataSummary($income->getBoq()->getId());
        }

        return $response;
    }
}
