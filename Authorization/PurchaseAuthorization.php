<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class PurchaseAuthorization extends AbstractPurchaseAuthorization
{
    use \Erp\Bundle\CoreBundle\Authorization\ErpReadonlyAuthorizationTrait;
    use DocumentReadonlyAuthorizationTrait;
}
