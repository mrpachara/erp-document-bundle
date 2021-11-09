<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Erp\Bundle\CoreBundle\Domain\Adapter\LockMode;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentWithProjectInterface as ServiceInterface;

/**
 * Purchase Api Command
 */
abstract class PurchaseApiCommand extends DocumentApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\PurchaseAuthorization
     */
    protected $authorization = null;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery
     */
    protected $domainQuery;

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    /**
     * replace action
     *
     * @Rest\Put("/{id}")
     *
     * @param string $id
     * @param Request $request
     */
    public function replaceAction($id, Request $request)
    {
        try {
            return parent::replaceAction($id, $request);
        } catch (AccessDeniedException $excp) {
            // dummay
        }

        return $this->createCommand($request, [
            'replace-worker replace-individual' => function($class, &$data) use ($id) {
                /** @var SystemUser */
                $user = $this->getUser();

                $doc = $this->domainQuery->findWithUser($id, $user,
                    [ServiceInterface::WORKER, ServiceInterface::OWNER],
                    LockMode::PESSIMISTIC_WRITE
                );

                if(empty($doc) || !$this->grant(['replace-worker', 'replace-individual'], [$doc]))
                    return null;


                $item = new $class();

                $item->setUpdateOf($doc);
                $doc->addUpdatedBy($item);

                return $item;
            },
            'replace-worker' => function($class, &$data) use ($id) {
                /** @var SystemUser */
                $user = $this->getUser();

                $doc = $this->domainQuery->findWithUser($id, $user,
                    [ServiceInterface::WORKER],
                    LockMode::PESSIMISTIC_WRITE
                );

                if(empty($doc) || !$this->grant(['replace-worker'], [$doc]))
                    return null;

                $item = new $class();

                $item->setUpdateOf($doc);
                $doc->addUpdatedBy($item);

                return $item;
            },
            'replace-individual' => function($class, &$data) use ($id) {
                /** @var SystemUser */
                $user = $this->getUser();

                $doc = $this->domainQuery->findWithUser($id, $user,
                    [ServiceInterface::OWNER],
                    LockMode::PESSIMISTIC_WRITE
                );

                if(empty($doc) || !$this->grant(['replace-individual'], [$doc]))
                    return null;

                $item = new $class();

                $item->setUpdateOf($doc);
                $doc->addUpdatedBy($item);

                return $item;
            },
        ]);
    }
}
