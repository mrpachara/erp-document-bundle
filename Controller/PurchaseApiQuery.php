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

    protected function assignWithUserSearchRule($queryParams, $rules)
    {
        $withUser = $this->tryGrant($rules);

        if($withUser instanceof AccessDeniedException) {
            throw new AccessDeniedException("Access denied!!!");
        }

        if(!empty($withUser)) {
            $queryParams['document-with-user'] = [
                'user' => $this->getUser(),
                'types' => $withUser,
            ];
        }

        return $queryParams;
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
                if($detail->getBoqData()) {
                    $this->projectBoqSummaryQuery->getProjectBoqDataSummary($detail->getBoqData()->getId());
                }
            }
        }

        return $response;
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
}
