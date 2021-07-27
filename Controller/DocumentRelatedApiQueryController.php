<?php
namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\User\UserInterface;
use Erp\Bundle\DocumentBundle\Domain\CQRS\Query\DocumentQuery;
use FOS\RestBundle\View\View;

/**
 *
 * @author pachara
 *
 * BillingNote Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/document-related")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class DocumentRelatedApiQueryController
{

    private $query;
    /**
     */
    public function __construct(DocumentQuery $query)
    {
        $this->query = $query;
    }
    
    /**
     * List all tracking related documents
     *
     * @Rest\Get("/tracking")
     */
    public function listTrackingAction(UserInterface $user)
    {
        $relatedDocs = $this->query->getRelatedDocumentTracking($user);
        $view = new View( [
            'data' => $relatedDocs,
        ]);
        
        $context = $view->getContext();
        $context
            ->addGroup('short')
        ;

        return $view;
    }

    /**
     * List all next related documents
     *
     * @Rest\Get("/next")
     */
    public function listNextAction(UserInterface $user)
    {
        $relatedDocs = $this->query->getRelatedDocumentNext($user);
        $view = new View( [
            'data' => $relatedDocs,
        ]);
        
        $context = $view->getContext();
        $context
            ->addGroup('short')
        ;
        
        return $view;
    }
}

