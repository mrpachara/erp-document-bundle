<?php
namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * PurchaseOrderQuantitySummary Api Query
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/purchase-order-quantity")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class PurchaseOrderQuantitySummaryApiQueyController
{
    
    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuantitySummaryQuery
     */
    protected $domainQuery;
    
    function __construct(\Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuantitySummaryQuery $domainQuery)
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
        $query = $request->getQueryParams();
        $excepts = (empty($query['excepts']))? null : $query['excepts'];
        $item = $this->domainQuery->getPurchaseOrderQuantitySummary($id, $excepts);
        
        return ['data' => $item];
    }
}

