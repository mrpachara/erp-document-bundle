<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Erp\Bundle\DocumentBundle\Entity\Purchase;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;

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
        } catch (AccessDeniedException $excp) {
            // dummay
        }

        return $this->listQuery($request, [
            'list-worker list-individual' => function($queryParams, &$context) {
                /** @var SystemUser */
                $user = $this->getUser();

                return $this->domainQuery->searchWithUser($queryParams, $user,
                    [ServiceInterface::WORKER, ServiceInterface::OWNER]
                , $context);
            },
            'list-worker' => function($queryParams, &$context) {
                /** @var SystemUser */
                $user = $this->getUser();

                return $this->domainQuery->searchWithUser($queryParams, $user,
                    [ServiceInterface::WORKER]
                , $context);
            },
            'list-individual' => function($queryParams, &$context) {
                /** @var SystemUser */
                $user = $this->getUser();

                return $this->domainQuery->searchWithUser($queryParams, $user,
                    [ServiceInterface::OWNER]
                , $context);
            },
        ]);
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
        $response = null;
        try {
            $response = parent::getAction($id, $request);
        } catch (AccessDeniedException $excp) {
            // dummay
        }

        if($response === null) {
            $response = $this->getQuery($id, $request, [
                'get-worker get-individual' => function($id, $queryParams, &$context) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    return $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::WORKER, ServiceInterface::OWNER]
                    );
                },
                'get-worker' => function($id, $queryParams, &$context) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    return $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::WORKER]
                    );
                },
                'get-indidual' => function($id, $queryParams, &$context) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    return $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::OWNER]
                    );
                },
            ]);
        }

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
