<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use Erp\Bundle\CoreBundle\Controller\CoreAccountApiCommand;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Erp\Bundle\CoreBundle\Domain\Adapter\LockMode;

use Erp\Bundle\DocumentBundle\Entity\TerminatedDocument;

use Erp\Bundle\CoreBundle\Controller\InitialItem;

/**
 * Document Api Command
 */
abstract class DocumentApiCommand extends CoreAccountApiCommand implements InitialItem
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\AbstractDocumentAuthorization
     */
    protected $authorization;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery
     */
    protected $domainQuery;

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    protected $user;

    /** @required */
    public function setTokenStorage(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function initialItem($item)
    {
        $item->setCreator($this->tokenStorage->getToken()->getUser());
    }

    protected function prepareItemAfterPatch($item)
    {
        $item = parent::prepareItemAfterPatch($item);
        
        if(!$this->grant('approve', [$item])) $item->setApproved(false);
        
        return $item;
    }

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
        $grant = 'replace';
        return $this->createCommand($grant, $request, function($class, &$data) use ($id, $grant) {
            if(!($doc = $this->domainQuery->find($id, LockMode::PESSIMISTIC_WRITE)) || !$this->grant($grant, [$doc]))
                return null;

            $item = new $class();

            $item->setUpdateOf($doc);
            $doc->addUpdatedBy($item);

            return $item;
        });
    }

    protected function prepareTerminatedDocumentData($data)
    {
        return $data;
    }

    protected function extractTerminatedDocumentData(Request $request)
    {
        return $data = $this->prepareTerminatedDocumentData(
            $this->serializer->deserialize(
                $request->getContent(),
                'array',
                $request->getContentType()
            )
        );
    }

    protected function prepareTerminatedDocumentAfterPatch($item)
    {
        return $item;
    }

    protected function patchTerminatedDocumentItem($item, $data)
    {
        return $this->prepareTerminatedDocumentAfterPatch($this->serializer->deserializeToExisted(
            $item,
            $data,
            TerminatedDocument::class,
            'json'
        ));
    }

    protected function terminateCommand($grant, $id, Request $request, $callback)
    {
        if (!$this->grant($grant, [])) {
            throw new UnprocessableEntityHttpException("Terminate is not allowed.");
        }

        $data = $this->extractTerminatedDocumentData($request);

        $item = $this->commandHandler->execute(function ($em) use ($callback, $id, $data, $grant) {
            if(!($item = $callback($id, $data)))
                throw new NotFoundHttpException("Entity not found.");
            if (!$this->grant($grant, [$item])) {
                throw new UnprocessableEntityHttpException("Terminate is not allowed.");
            }

            /** @var TerminatedDocument */
            $termDoc = new TerminatedDocument();
            $termDoc = $this->patchTerminatedDocumentItem($termDoc, $data);
            $this->initialItem($termDoc);
            $em->persist($termDoc);

            $item->setTerminated($termDoc);

            if ($termDoc->getType() === 'REJECT') {
                for ($affDoc = $item->getUpdateOf(); $affDoc !== null; $affDoc = $affDoc->getUpdateOf()) {
                    $affDoc->setTerminated($termDoc);
                }
            }

            return $item;
        });

        return $this->view(['data' => $this->domainQuery->find($item->getId())], 200);
    }

    /**
     * terminate action
     *
     * @Rest\Put("/{id}/terminate")
     *
     * @param string $id
     * @param Request $request
     */
    public function terminateAction($id, Request $request)
    {
        // return document
        return $this->terminateCommand('terminate', $id, $request, function($id, &$data) {
            return $this->domainQuery->find($id, LockMode::PESSIMISTIC_WRITE);
        });
    }
}
