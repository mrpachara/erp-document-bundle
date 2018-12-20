<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Revenue Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/revenue")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class RevenueApiQueryController extends IncomeApiQuery
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\RevenueAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\RevenueAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\RevenueQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\RevenueQuery $domainQuery)
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

    protected function listTaxInvoiceRemainResponse($data, $context)
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
     * list taxInvoiceRemain action
     *
     * @Rest\Get("/tax-invoice-remain")
     *
     * @param ServerRequestInterface $request
     */
    public function listTaxInvoiceRemainAction(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();
        $items = [];
        $context = [];

        $items = $this->domainQuery->searchTaxInvoiceRemain($queryParams, $context);

        return $this->view($this->listTaxInvoiceRemainResponse($items, $context), 200);
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
        /** @var Erp\Bundle\DocumentBundle\Entity\Income */
        $income = $responseData['data'];

        $origin = $this->domainQuery->origin($income);

        $profile = $this->settingQuery->findOneByCode('profile')->getValue();
        
        $bankAccounts = $this->settingQuery->findOneByCode('bankaccount')->getValue()['bankAccounts'];
        
        $logo = null;
        if(!empty($profile['logo'])) {
            $logo = stream_get_contents($this->fileQuery->get($profile['logo'])->getData());
        }

        $view = $this->render('@ErpDocument/pdf/revenue.pdf.twig', [
            'profile' => $profile,
            'origin' => $origin,
            'model' => $income,
            'bankAccounts' => $bankAccounts,
        ]);

        $output = $this->get(\Erp\Bundle\DocumentBundle\Service\PDFService::class)->generatePdf($view, ['format' => 'A4'], function($mpdf) use ($income, $logo) {
            $status = $income->getStatus();

            if(!empty($status)) {
                $mpdf->SetWatermarkText($status);
                $mpdf->showWatermarkText = true;
            }
            
            $mpdf->imageVars['logo'] = $logo;
        });

        return new \TFox\MpdfPortBundle\Response\PDFResponse($output);
    }

    /**
     * get taxInvoiceRemain action
     *
     * @Rest\Get("/tax-invoice-remain/{id}")
     *
     * @param string $id
     * @param ServerRequestInterface $request
     */
    public function getTaxInvoiceRemainAction($id, ServerRequestInterface $request)
    {
        $item = $this->domainQuery->getTaxInvoiceRemain($id);
        if (empty($item)) {
            throw new HttpException(404, "Entity not found.");
        }

        return $this->view(['data' => $item], 200);
    }
}