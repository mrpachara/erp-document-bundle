<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * DeliveryNote Api CommandController
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/delivery-note")
 */
class DeliveryNoteApiCommandController extends IncomeApiCommand
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\DeliveryNoteAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\DeliveryNoteAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\DeliveryNoteQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\DeliveryNoteQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    protected function prepareData($data, $for)
    {
        $data = parent::prepareData($data, $for);

        if (isset($data['id'])) {
            unset($data['id']);
        }
        if (!empty($data['project'])) {
            $data['project'] = array_intersect_key($data['project'], array_flip(['id', 'dtype']));
        }
        if (!empty($data['requester'])) {
            $data['requester'] = array_intersect_key($data['requester'], array_flip(['id', 'dtype']));
        }
        if (!empty($data['boq'])) {
            $data['boq'] = array_intersect_key($data['boq'], array_flip(['id', 'dtype']));
        }
        $data['approved'] = !empty($data['approved']);

        $total = $data['total'];

        if (empty($total)) {
            throw new \Exception("Invalid data format!!!");
        }

        foreach ($data['details'] as $index => $detail) {
            if (isset($detail['id'])) {
                unset($detail['id']);
            }
            if (!empty($detail['boqData'])) {
                $detail['boqData'] = array_intersect_key($detail['boqData'], array_flip(['id', 'dtype']));
            }

            if (empty($detail['_total'])) {
                throw new \Exception("Invalid data format!!!");
            }

            $detail['total'] = $detail['_total'];

            $data['details'][$index] = $detail;
        }

        return $data;
    }
}
