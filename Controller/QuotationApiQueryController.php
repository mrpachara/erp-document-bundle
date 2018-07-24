<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Erp\Bundle\DocumentBundle\Collection\DocumentRestResponse;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Erp\Bundle\CoreBundle\Controller\CQRSQueryApi;

/**
 * PurchaseOrder Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/quotation")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class QuotationApiQueryController extends PurchaseApiQuery
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\QuotationAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\QuotationAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseOrderQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    /**
     * @var \Erp\Bundle\SettingBundle\Domain\CQRS\SettingQuery
     */
    protected $settingQuery = null;

    /** @required */
    public function setSettingQuery(\Erp\Bundle\SettingBundle\Domain\CQRS\SettingQuery $settingQuery)
    {
        $this->settingQuery = $settingQuery;
    }

    /**
     * @var \Erp\Bundle\CoreBundle\Domain\CQRS\TempFileItemQuery
     */
    protected $fileQuery = null;

    /** @required */
    public function setFileQuery(\Erp\Bundle\CoreBundle\Domain\CQRS\TempFileItemQuery $fileQuery)
    {
        $this->fileQuery = $fileQuery;
    }

    protected function getResponse($data, $context)
    {
        $context = parent::getResponse($data, $context);
        $context['actions'][] = 'print';
        return $context;
    }

    protected function listPurchaseRequestRemainResponse($data, $context)
    {
        $context = $this->prepareContext($context);

        // if (!isset($context['searchable'])) {
        //     $context['searchable'] = true;
        // }

        foreach (['add'] as $action) {
            if (!in_array($action, $context['actions'])) {
                $context['actions'][] = $action;
            }
        }

        $context['actions'] = $this->prepareActions($context['actions'], $data);
        $context['data'] = $data;

        return $context;
    }

    /**
     * list purchaseRequestRemain action
     *
     * @Rest\Get("/purchase-request-remain")
     *
     * @param ServerRequestInterface $request
     */
    public function listPurchaseRequestRemainAction(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();
        $items = [];
        $context = [];

        $items = $this->domainQuery->searchPurchaseRequestRemain($queryParams, $context);

        return $this->view($this->listPurchaseRequestRemainResponse($items, $context), 200);
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

        $origin = $this->domainQuery->origin($purchase);

        $profile = $this->settingQuery->findOneByCode('profile')->getValue();

        $logo = null;
        if(!empty($profile['logo'])) {
            $logo = stream_get_contents($this->fileQuery->get($profile['logo'])->getData());
        }

        $view = $this->render('@ErpDocument/pdf/quotation.pdf.twig', [
            'profile' => $profile,
            'origin' => $origin,
            'model' => $purchase,
        ]);

        $output = $this->get(\Erp\Bundle\DocumentBundle\Service\PDFService::class)->generatePdf($view, ['format' => 'A4'], function($mpdf) use ($purchase, $logo) {
            $status = $purchase->getStatus();

            if(!empty($status)) {
                $mpdf->SetWatermarkText($status);
                $mpdf->showWatermarkText = true;
            }

            $mpdf->imageVars['logo'] = $logo;
        });

        return new \TFox\MpdfPortBundle\Response\PDFResponse($output);
    }

    /**
     * get purchaseRequestRemain action
     *
     * @Rest\Get("/purchase-request-remain/{id}")
     *
     * @param string $id
     * @param ServerRequestInterface $request
     */
    public function getPurchaseRequestRemainAction($id, ServerRequestInterface $request)
    {
        $item = $this->domainQuery->getPurchaseRequestRemain($id);
        if (empty($item)) {
            throw new HttpException(404, "Entity not found.");
        }

        return $this->view(['data' => $item], 200);
    }
}
