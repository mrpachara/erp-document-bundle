<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Expense Api Controller
 *
 * @Rest\Version("1.0")
 * @Rest\Route("/api/expense")
 */
class ExpenseApiCommandController extends PurchaseApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\ExpenseAuthorization
     */
    protected $authorization = null;

    /** @required */
    public function setAuthorization(\Erp\Bundle\DocumentBundle\Authorization\ExpenseAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\ExpenseQuery
     */
    protected $domainQuery;

    /** @required */
    public function setDomainQuery(\Erp\Bundle\DocumentBundle\Domain\CQRS\ExpenseQuery $domainQuery)
    {
        $this->domainQuery = $domainQuery;
    }

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;

    protected function prepareData($data, $for) {
        $data = parent::prepareData($data, $for);

        return $data;
    }
}
