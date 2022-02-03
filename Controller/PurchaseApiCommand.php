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

    protected function getReplaceRuleForFindFunction($id)
    {
        return array_merge(
            parent::getReplaceRuleForFindFunction($id),
            [
                'replace-worker replace-individual' => function($class, &$data) use ($id) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    $doc = $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::WORKER, ServiceInterface::OWNER],
                        LockMode::PESSIMISTIC_WRITE
                    );

                    if(empty($doc) || !$this->grant(['replace-worker', 'replace-individual'], [$doc]))
                        return null;


                    $item = new $class();

                    $item->setUpdateOf($doc);
                    $doc->addUpdatedBy($item);

                    return $item;
                },
                'replace-worker' => function($class, &$data) use ($id) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    $doc = $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::WORKER],
                        LockMode::PESSIMISTIC_WRITE
                    );

                    if(empty($doc) || !$this->grant(['replace-worker'], [$doc]))
                        return null;

                    $item = new $class();

                    $item->setUpdateOf($doc);
                    $doc->addUpdatedBy($item);

                    return $item;
                },
                'replace-individual' => function($class, &$data) use ($id) {
                    /** @var SystemUser */
                    $user = $this->getUser();

                    $doc = $this->domainQuery->findWithUser($id, $user,
                        [ServiceInterface::OWNER],
                        LockMode::PESSIMISTIC_WRITE
                    );

                    if(empty($doc) || !$this->grant(['replace-individual'], [$doc]))
                        return null;

                    $item = new $class();

                    $item->setUpdateOf($doc);
                    $doc->addUpdatedBy($item);

                    return $item;
                },
            ]
        );
    }
}
