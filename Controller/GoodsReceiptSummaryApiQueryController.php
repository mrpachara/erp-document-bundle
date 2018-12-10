<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * GoodsReceiptSummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/goods-receipt-by-po")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class GoodsReceiptSummaryApiQueryController extends FOSRestController
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\GoodsReceiptSummaryQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\GoodsReceiptSummaryQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
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
        $item = $this->domainQuery->getGoodsReceiptSummary($id);

        return $this->view(['data' => $item], 200);
    }
}
