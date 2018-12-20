<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Income Api Query
 */
abstract class IncomeApiQuery extends DocumentApiQuery {
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
        $response = parent::getAction($id, $request);

        $responseData = $response->getData();
        /** @var Erp\Bundle\DocumentBundle\Entity\Income */
        $income = $responseData['data'];

        if (!empty($income)) {
            // TODO: check authentication
            $this->projectBoqContractSummaryQuery->getProjectBoqDataSummary($income->getBoq()->getId());
        }

        return $response;
    }
}
