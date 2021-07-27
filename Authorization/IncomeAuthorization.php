<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

class IncomeAuthorization extends AbstractIncomeAuthorization
{
    use \Erp\Bundle\CoreBundle\Authorization\ErpReadonlyAuthorizationTrait;
    use DocumentReadonlyAuthorizationTrait;
}
