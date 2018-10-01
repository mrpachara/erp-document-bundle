<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ServerRequestInterface;

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
        
        $view = $this->render('@ErpDocument/pdf/purchase-request.pdf.twig', [
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
}
