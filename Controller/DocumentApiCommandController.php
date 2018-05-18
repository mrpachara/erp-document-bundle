<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Document Api CommandController
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/document")
 */
class DocumentApiCommandController extends DocumentApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\DocumentAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAutorization(\Erp\Bundle\DocumentBundle\Authorization\DocumentAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;
    //
    // /** @required */
    // public function setCommanHandler(\Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler $commandHandler)
    // {
    //     $this->commandHandler = $commandHandler;
    // }
}
