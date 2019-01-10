<?php
namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\User\UserInterface;
use Erp\Bundle\DocumentBundle\Domain\CQRS\Query\DocumentQuery;

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
     * List all related documents
     *
     * @Rest\Get("")
     */
    public function listAction(UserInterface $user)
    {
        return [
            'data' => $this->query->getRelatedDocument($user),
        ];
    }
}

