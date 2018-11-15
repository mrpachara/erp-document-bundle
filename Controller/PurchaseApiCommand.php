<?php

namespace Erp\Bundle\DocumentBundle\Controller;

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
}
