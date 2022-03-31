<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use Erp\Bundle\CoreBundle\Controller\CoreAccountApiQuery;

/**
 * Document Api Query
 */
abstract class DocumentApiQuery extends CoreAccountApiQuery
{
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\AbstractDocumentAuthorization
     */
    protected $authorization;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\DocumentQuery
     */
    protected $domainQuery;

    protected function getResponse($data, $context)
    {
        $context = parent::getResponse($data, $context);
        $documentActions = $this->prepareActions(['replace', 'cancel', 'reject'], $data);
        $context['actions'] = array_merge($context['actions'], $documentActions);
        return $context;
    }
}
