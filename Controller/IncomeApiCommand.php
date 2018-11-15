<?php

namespace Erp\Bundle\DocumentBundle\Controller;

/**
 * Income Api Command
 */
abstract class IncomeApiCommand extends DocumentApiCommand {
    /**
     * @var \Erp\Bundle\DocumentBundle\Authorization\IncomeAuthorization
     */
    protected $authorization = null;

    /**
     * @var \Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery
     */
    protected $domainQuery;

    // /**
    //  * @var \Erp\Bundle\CoreBundle\Domain\CQRS\SimpleCommandHandler
    //  */
    // protected $commandHandler;
}
