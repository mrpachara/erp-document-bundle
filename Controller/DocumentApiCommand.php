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

    protected function replaceCommand(Request $request, $callbacks)
    {
        $newCallbacks = array_map(function($callback) use ($request) {
            return function($grants) use ($request, $callback) {
                $data = $this->extractData($request, self::FOR_CREATE);
                $queryParams = $request->attributes->get('queryParams', []);

                /********************************************************************
                 * NOTE: MUST throw exception, unless transaction will be commited. *
                 ********************************************************************/
                $item = $this->commandHandler->execute(function ($em) use ($callback, $data, $queryParams, $grants) {
                    $class = $this->domainQuery->getClassName();
                    list($doc, $item) = $callback($class, $data, $queryParams);
                    if (empty($doc) || empty($item)) {
                        throw new AccessDeniedException("Create is not allowed.");
                    }

                    if($this instanceof InitialItem) $this->initialItem($item);
                    $item = $this->patchExistedItem($item, $data);

                    if (!$this->grant($grants, [$doc, $item])) {
                        throw new AccessDeniedException("Create is not allowed.");
                    }

                    $item->setUpdateOf($doc);
                    $doc->addUpdatedBy($item);

                    $em->persist($item);

                    return $item;
                });

                return $item;
            };
        }, $callbacks);

        $result = $this->tryGrant($newCallbacks);

        if($result instanceof AccessDeniedException) {
            throw new AccessDeniedException("Create is not allowed.");
        }

        $item = $result;
        return $this->view(['data' => $this->domainQuery->find($item->getId())], 200);
    }

    protected function getActionRuleForFindFunction($id, string $action)
    {
        return [
            "{$action}" => function($class, &$data, array $queryParams) use ($id, $action) {
                $queryParams['lock'] = [
                    'mode' => LockMode::PESSIMISTIC_WRITE,
                ];

                $doc = $this->domainQuery->findWith($id, $queryParams);

                // TODO: change to specific array of grants, e.g. ['replace-worker', 'replace-individual']
                if(empty($doc) || !$this->grant([$action], [$doc]))
                    return [null, null];

                $item = new $class();

                // NOTE: Don't update $doc here, it will be granted later

                return [$doc, $item];
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
        return $this->replaceCommand(
            $request,
            $this->getActionRuleForFindFunction($id, 'replace')
        );
    }

    protected function prepareTerminatedDocumentData($data)
    {
        return $data;
    }

    protected function extractTerminatedDocumentData(Request $request)
    {
        return $this->prepareTerminatedDocumentData(
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

    protected function getTeminateCallback(string $type)
    {
        return function($id, &$data) use ($type) {
            return $this->tryGrant(
                $this->getActionRuleForFindFunction($id, $type)
            );
//            return $this->domainQuery->find($id, LockMode::PESSIMISTIC_WRITE);
        };
    }

    protected function cancelAction($id, Request $request)
    {
        return $this->terminateCommand($id, $request, [
            'cancel' => $this->getTeminateCallback('cancel'),
        ]);
    }

    protected function rejectAction($id, Request $request)
    {
        return $this->terminateCommand($id, $request, [
            'reject' => $this->getTeminateCallback('reject'),
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
