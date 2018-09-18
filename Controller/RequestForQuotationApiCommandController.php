<?php
namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * PurchaseRequest Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/request-for-quotation")
 */
class RequestForQuotationApiCommandController extends DocumentApiCommand
{

    /**
     *
     * @var \Erp\Bundle\DocumentBundle\Authorization\RequestForQuotationAuthorization
     */
    protected $authorization = null;

    /**
     *
     * @required
     */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\RequestForQuotationAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     *
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\RequestForQuotationQuery
     */
    protected $domainQuery;

    /**
     *
     * @required
     */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\RequestForQuotationQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    // * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    // */
    // protected $commandHandler;
    protected function prepareData($data, $for)
    {
        if (isset($data['id']))
            unset($data['id']);
        if (! empty($data['project']))
            $data['project'] = array_intersect_key($data['project'], array_flip([
                'id',
                'dtype'
            ]));
        if (! empty($data['requester']))
            $data['requester'] = array_intersect_key($data['requester'], array_flip([
                'id',
                'dtype'
            ]));
        if (! empty($data['vendor']))
            $data['vendor'] = array_intersect_key($data['vendor'], array_flip([
                'id',
                'dtype'
            ]));
        if (! empty($data['boq']))
            $data['boq'] = array_intersect_key($data['boq'], array_flip([
                'id',
                'dtype'
            ]));
        if (! empty($data['budgetType']))
            $data['budgetType'] = array_intersect_key($data['budgetType'], array_flip([
                'id',
                'dtype'
            ]));
        $data['approved'] = ! empty($data['approved']);

        if (! empty($data['requestedVendors'])) {
            foreach ($data['requestedVendors'] as $index => $requestedVendor) {
                $data['requestedVendors'][$index] = array_intersect_key($requestedVendor, array_flip([
                    'id',
                    'dtype'
                ]));
            }
        }


       

        foreach ($data['details'] as $index => $detail) {
            if (isset($detail['id']))
                unset($detail['id']);
            unset($detail['boqData']);

            if (! empty($detail['purchaseRequestDetail'])) {
                $detail['purchaseRequestDetail'] = array_intersect_key($detail['purchaseRequestDetail'], array_flip([
                    'id',
                    'dtype'
                ]));
            }

            $data['details'][$index] = $detail;
        }

        return $data;
    }
}
