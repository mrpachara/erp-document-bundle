<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use Erp\Bundle\CoreBundle\Controller\CoreAccountApiCommand;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Erp\Bundle\CoreBundle\Domain\Adapter\LockMode;

use Erp\Bundle\DocumentBundle\Entity\TerminatedDocument;

use Erp\Bundle\CoreBundle\Controller\InitialItem;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    protected function getReplaceRuleForFindFunction($id)
    {
        return [
            'replace' => function($class, &$data, array $queryParams) use ($id) {
                $queryParams['lock'] = [
                    'mode' => LockMode::PESSIMISTIC_WRITE,
                ];

                $doc = $this->domainQuery->findWith($id, $queryParams);

                // TODO: change to specific array of grants, e.g. ['replace-worker', 'replace-individual']
                if(empty($doc) || !$this->grant(['replace'], [$doc]))
                    return null;

                $item = new $class();

                $item->setUpdateOf($doc);
                $doc->addUpdatedBy($item);

                return $item;
            },
        ];
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
        return $this->createCommand(
            $request,
            $this->getReplaceRuleForFindFunction($id)
        );
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

    protected function terminateCommand($id, Request $request, $callbacks)
    {
        $newCallbacks = array_map(function($callback) use ($id, $request) {
            return function($grants) use ($id, $request, $callback) {
                $data = $this->extractTerminatedDocumentData($request);

                /********************************************************************
                 * NOTE: MUST throw exception, unless transaction will be commited. *
                 ********************************************************************/
                $item = $this->commandHandler->execute(function ($em) use ($callback, $id, $data, $grants) {
                    if(!($item = $callback($id, $data)))
                        throw new NotFoundHttpException("Entity not found.");
                    if (!$this->grant($grants, [$item])) {
                        throw new AccessDeniedException("Terminate is not allowed.");
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

                return $item;
            };
        }, $callbacks);

        $result = $this->tryGrant($newCallbacks);

        if($result instanceof AccessDeniedException) {
            throw new AccessDeniedException("Terminate is not allowed.");
        }

        $item = $result;
        return $this->view(['data' => $this->domainQuery->find($item->getId())], 200);

        // foreach($callbacks as $grantText => $callback) {
        //     $grants = preg_split('/\s+/', $grantText);
        //     if (!$this->grant($grants, [])) continue;

        //     $data = $this->extractTerminatedDocumentData($request);

        //     $item = $this->commandHandler->execute(function ($em) use ($callback, $id, $data, $grants) {
        //         if(!($item = $callback($id, $data)))
        //             throw new NotFoundHttpException("Entity not found.");
        //         if (!$this->grant($grants, [$item])) {
        //             throw new AccessDeniedException("Terminate is not allowed.");
        //         }

        //         /** @var TerminatedDocument */
        //         $termDoc = new TerminatedDocument();
        //         $termDoc = $this->patchTerminatedDocumentItem($termDoc, $data);
        //         $this->initialItem($termDoc);
        //         $em->persist($termDoc);

        //         $item->setTerminated($termDoc);

        //         if ($termDoc->getType() === 'REJECT') {
        //             for ($affDoc = $item->getUpdateOf(); $affDoc !== null; $affDoc = $affDoc->getUpdateOf()) {
        //                 $affDoc->setTerminated($termDoc);
        //             }
        //         }

        //         return $item;
        //     });

        //     return $this->view(['data' => $this->domainQuery->find($item->getId())], 200);
        // }

        // throw new AccessDeniedException("Terminate is not allowed.");
    }

    protected function getTeminateCallback()
    {
        return function($id, &$data) {
            return $this->tryGrant(
                $this->getReplaceRuleForFindFunction($id)
            );
//            return $this->domainQuery->find($id, LockMode::PESSIMISTIC_WRITE);
        };
    }

    protected function cancelAction($id, Request $request)
    {
        return $this->terminateCommand($id, $request, [
            'cancel' => $this->getTeminateCallback(),
        ]);
    }

    protected function rejectAction($id, Request $request)
    {
        return $this->terminateCommand($id, $request, [
            'reject' => $this->getTeminateCallback(),
        ]);
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
        $data = $this->extractTerminatedDocumentData($request);

        // return document
        switch($data['type']) {
            case 'CANCEL':
                return $this->cancelAction($id, $request);
            case 'REJECT':
                return $this->rejectAction($id, $request);
            default:
                throw new BadRequestHttpException("Unknown terminated type!!!");
        }
    }
}
