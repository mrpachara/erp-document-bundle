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

    /** @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery-*/
    protected $projectBoqSummaryQuery;

    //    /** @required */
    //    public function setProjectBoqSummaryQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery $projectBoqSummaryQuery)
    //    {
    //        $this->projectBoqSummaryQuery = $projectBoqSummaryQuery;
    //    }

//    /**
//     * get action
//     *
//     * @Rest\Get("/{id}")
//     *
//     * @param string $id
//     * @param ServerRequestInterface $request
//     */
//    public function getAction($id, ServerRequestInterface $request)
//    {
//        $response = parent::getAction($id, $request);

//        $responseData = $response->getData();
//        /** @var Erp\Bundle\DocumentBundle\Entity\Income */
//        $income = $responseData['data'];

//        if (!empty($income)) {
//            // TODO: check authentication
//            foreach ($income->getDetails() as $detail) {
//                // TODO: check if needed set back to $detail
//                $this->projectBoqSummaryQuery->getProjectBoqDataSummary($detail->getBoqData()->getId());
//            }
//        }

//        return $response;
//    }
}
