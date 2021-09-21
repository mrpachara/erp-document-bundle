<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

use Psr\Http\Message\ServerRequestInterface;
use Erp\Bundle\DocumentBundle\Entity\Purchase;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Purchase Api Query
 */
abstract class PurchaseApiQuery extends DocumentApiQuery {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseAuthorization
     */
    protected $authorization;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery
     */
    protected $domainQuery;

    /** @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery-*/
    protected $projectBoqSummaryQuery;

    /** @required */
    public function setProjectBoqSummaryQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ProjectBoqSummaryQuery $projectBoqSummaryQuery)
    {
        $this->projectBoqSummaryQuery = $projectBoqSummaryQuery;
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
        try {
            return parent::listAction($request);
        } catch (UnprocessableEntityHttpException $excp) {
            // dummay
        }

        return $this->listQuery('list-individual', $request, function($queryParams, &$context) {
            /** @var SystemUser */
            $user = $this->getUser();

            return $this->domainQuery->searchWithUser($queryParams, $user, $context);
        });
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
        /** @var Purchase */
        $purchase = $responseData['data'];

        if (!empty($purchase)) {
            // TODO: check authentication
            foreach ($purchase->getDetails() as $detail) {
                // TODO: check if needed set back to $detail
                $this->projectBoqSummaryQuery->getProjectBoqDataSummary($detail->getBoqData()->getId());
            }
        }

        return $response;
    }
}
