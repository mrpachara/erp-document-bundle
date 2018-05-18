<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Erp\Bundle\DocumentBundle\Collection\DocumentRestResponse;
use Psr\Http\Message\ServerRequestInterface;

use Erp\Bundle\CoreBundle\Controller\CQRSQueryApi;

/**
 * PurchaseRequest Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/purchase-request")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class PurchaseRequestApiQueryController extends PurchaseApiQuery
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseRequestAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\PurchaseRequestAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseRequestQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseRequestQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    /**
     * get action
     *
     * @Rest\Get("/{id}.{format}")
     *
     * @param string $id
     * @param string $format
     * @param ServerRequestInterface $request
     */
    public function pdfAction($id, $format, ServerRequestInterface $request)
    {
        $response = $this->getAction($id, $request);

        $responseData = $response->getData();
        /** @var Erp\Bundle\DocumentBundle\Entity\Purchase */
        $purchase = $responseData['data'];

        $view = $this->render('@ErpDocument/pdf/purchase-request.pdf.twig', [
            'model' => $purchase,
        ]);

        $output = $this->get(\Erp\Bundle\DocumentBundle\Service\PDFService::class)->generatePdf($view, ['format' => 'A4']);

        return new \TFox\MpdfPortBundle\Response\PDFResponse($output);
    }
}
