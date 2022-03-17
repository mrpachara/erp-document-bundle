<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Erp\Bundle\CoreBundle\Domain\Adapter\LockMode;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;
use Erp\Bundle\DocumentBundle\Entity\DetailStatusChanged;

/**
 * Purchase Api Command
 */
abstract class PurchaseApiCommand extends DocumentApiCommand {
    use AssignWithUserSearchTrait;

    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseAuthorization
     */
    protected $authorization = null;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery
     */
    protected $domainQuery;

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    protected function prepareData($data, $for) {
        if(isset($data['id'])) unset($data['id']);
        if(!empty($data['project'])) $data['project'] = array_intersect_key($data['project'], array_flip(['id', 'dtype']));
        if(!empty($data['requester'])) $data['requester'] = array_intersect_key($data['requester'], array_flip(['id', 'dtype']));
        if(!empty($data['vendor'])) $data['vendor'] = array_intersect_key($data['vendor'], array_flip(['id', 'dtype']));
        if(!empty($data['boq'])) $data['boq'] = array_intersect_key($data['boq'], array_flip(['id', 'dtype']));
        if(!empty($data['budgetType'])) $data['budgetType'] = array_intersect_key($data['budgetType'], array_flip(['id', 'dtype']));
        $data['approved'] = !empty($data['approved']);

        // TODO: move docTotal to Purchase.total
        $docTotal = (key_exists('docTotal', $data))? $data['docTotal'] : null;
        $total = $data['total'];

        if(empty($total)) throw new \Exception("Invalid data format!!!");

        $totalValue = (double) $total;
        foreach($data['details'] as $index => $detail) {
            if(isset($detail['id'])) unset($detail['id']);
            if(!empty($detail['boqData']))
                $detail['boqData'] = array_intersect_key($detail['boqData'], array_flip(['id', 'dtype']));
            if(!empty($detail['statusChanged'])) {
                if(empty($detail['statusChanged']['type'])) {
                    unset($detail['statusChanged']);
                } else {
                    if(isset($detail['statusChanged']['id'])) unset($detail['statusChanged']['id']);
                    if(!empty($detail['statusChanged']['detail'])) {
                        $detail['statusChanged']['detail'] = array_intersect_key($detail['statusChanged']['detail'], array_flip(['id', 'dtype']));
                    }
                    if($detail['statusChanged']['type'] === DetailStatusChanged::REMOVED) {
                        $detail['costItem'] = null;
                        $detail['price'] = null;
                        $detail['quantity'] = null;
                        $detail['boqData'] = null;
                        $detail['remark'] = null;
                    }
                }
            }

            if(empty($detail['_total'])) throw new \Exception("Invalid data format!!!");

            if(
                (!empty($detail['statusChanged']) && ($detail['statusChanged']['type'] === DetailStatusChanged::REMOVED)) ||
                ($totalValue === 0)
            ) {
                $detail['total'] = 0;
            } else {
                if($docTotal === null) {
                    $detail['total'] = $detail['_total'];
                } else {
                    $detail['total'] = ($detail['_total'] / $totalValue) * $docTotal;
                }
            }

            $data['details'][$index] = $detail;
        }

        return $data;
    }

    protected function getCreateWithUserRules()
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

    protected function getReplaceWithUserRules()
    {
        return [
            'replace-all' => function($grants) {
                return [];
            },
            'replace-worker replace-individual' => function($grants) {
                return [ServiceInterface::WORKER, ServiceInterface::OWNER];
            },
            'replace-worker' => function($grants) {
                return [ServiceInterface::WORKER];
            },
            'replace-individual' => function($grants) {
                return [ServiceInterface::OWNER];
            }
        ];
    }

    /**
     * create action
     *
     * @Rest\Put("")
     *
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $queryParams = $this->assignWithUserSearchRule(
            $request->attributes->get('queryParams', []),
            $this->getCreateWithUserRules()
        );
        $request->attributes->set('queryParams', $queryParams);

        return parent::createAction($request);
    }

    /**
     * replace action
     *
     * @Rest\Put("/{id}")
     *
     * @param string $id
     * @param Request $request
     */
    public function replaceAction($id, Request $request)
    {
        $queryParams = $this->assignWithUserSearchRule(
            $request->attributes->get('queryParams', []),
            $this->getReplaceWithUserRules()
        );
        $request->attributes->set('queryParams', $queryParams);

        return parent::replaceAction($id, $request);
    }

    /**
     * terminate action
     *
     * @Rest\Put("/{id}/terminate")
     *
     * @param string $id
     * @param Request $request
     */
    public function terminateAction($id, Request $request)
    {
        $queryParams = $this->assignWithUserSearchRule(
            $request->attributes->get('queryParams', []),
            $this->getReplaceWithUserRules()
        );
        $request->attributes->set('queryParams', $queryParams);

        return parent::terminateAction($id, $request);
    }
}
