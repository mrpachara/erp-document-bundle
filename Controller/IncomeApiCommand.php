<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;
use Erp\Bundle\DocumentBundle\Entity\DetailStatusChanged;

/**
 * Income Api Command
 */
abstract class IncomeApiCommand extends DocumentApiCommand
{
    use AssignWithUserSearchTrait;

    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\IncomeAuthorization
     */
    protected $authorization = null;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery
     */
    protected $domainQuery;

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    protected function prepareData($data, $for)
    {
        if (isset($data['id'])) unset($data['id']);
        if (!empty($data['project'])) $data['project'] = array_intersect_key($data['project'], array_flip(['id', 'dtype']));
        if (!empty($data['requester'])) $data['requester'] = array_intersect_key($data['requester'], array_flip(['id', 'dtype']));
        if (!empty($data['vendor'])) $data['vendor'] = array_intersect_key($data['vendor'], array_flip(['id', 'dtype']));
        if (!empty($data['boq'])) $data['boq'] = array_intersect_key($data['boq'], array_flip(['id', 'dtype']));
        if (!empty($data['budgetType'])) $data['budgetType'] = array_intersect_key($data['budgetType'], array_flip(['id', 'dtype']));
        $data['approved'] = !empty($data['approved']);

        // TODO: move docTotal to Income.total
        $docTotal = $data['docTotal'];
        $total = $data['total'];

        $totalValue = (float) $total;
        foreach ($data['details'] as $index => $detail) {
            if (isset($detail['id'])) unset($detail['id']);
            if (!empty($detail['boqData']))
                $detail['boqData'] = array_intersect_key($detail['boqData'], array_flip(['id', 'dtype']));
            if (!empty($detail['statusChanged'])) {
                if (empty($detail['statusChanged']['type'])) {
                    unset($detail['statusChanged']);
                } else {
                    if (isset($detail['statusChanged']['id'])) unset($detail['statusChanged']['id']);
                    if (!empty($detail['statusChanged']['detail'])) {
                        $detail['statusChanged']['detail'] = array_intersect_key($detail['statusChanged']['detail'], array_flip(['id', 'dtype']));
                    }
                    if ($detail['statusChanged']['type'] === DetailStatusChanged::REMOVED) {
                        $detail['costItem'] = null;
                        $detail['price'] = null;
                        $detail['quantity'] = null;
                        $detail['boqData'] = null;
                        $detail['remark'] = null;
                    }
                }
            }

            if (empty($detail['_total'])) throw new \Exception("Invalid data format!!!");

            if (
                (!empty($detail['statusChanged']) && ($detail['statusChanged']['type'] === DetailStatusChanged::REMOVED)) ||
                ($totalValue === 0)
            ) {
                $detail['total'] = 0;
            } else {
                if ($docTotal === null) {
                    $detail['total'] = $detail['_total'];
                } else {
                    $detail['total'] = ($detail['_total'] / $totalValue) * $docTotal;
                }
            }

            $data['details'][$index] = $detail;
        }

        return $data;
    }

    protected function getActionWithUserRules(string $action)
    {
        return [
            "{$action}-all" => function ($grants) {
                return [];
            },
            // "{$action}-worker {$action}-individual" => function($grants) {
            //     return [ServiceInterface::WORKER, ServiceInterface::OWNER];
            // },
            "{$action}-worker" => function ($grants) {
                return [ServiceInterface::WORKER];
            },
            "{$action}-individual" => function ($grants) {
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
            $this->getActionWithUserRules('add')
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
            $this->getActionWithUserRules('replace')
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
        $data = $this->extractTerminatedDocumentData($request);
        $action = strtolower($data['type']);

        $queryParams = $this->assignWithUserSearchRule(
            $request->attributes->get('queryParams', []),
            $this->getActionWithUserRules($action)
        );
        $request->attributes->set('queryParams', $queryParams);

        return parent::terminateAction($id, $request);
    }
}
