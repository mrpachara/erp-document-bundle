<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\CoreBundle\Authorization\AbstractCoreAccountAuthorization as Authorization;

use Erp\Bundle\CoreBundle\Entity\StatusPresentable;

abstract class AbstractDocumentAuthorization extends Authorization
{
    use \Erp\Bundle\CoreBundle\Authorization\ErpUnchangableAuthorizationTrait;

    public function replace(...$args)
    {
        $result = true;
        if (isset($args[0])) {
            if ($result && ($args[0] instanceof StatusPresentable)) {
                $result = $result && $args[0]->updatable();
            }
        }
        return $result;
    }

    public function approve(...$args)
    {
        return call_user_func_array([$this, 'terminate'], $args);
    }

    public function cancel(...$args)
    {
        return call_user_func_array([$this, 'terminate'], $args);
    }

    public function reject(...$args)
    {
        return call_user_func_array([$this, 'terminate'], $args);
    }

    public function terminate(...$args)
    {
        $result = true;

        if (isset($args[0])) {
            if ($result && ($args[0] instanceof StatusPresentable)) {
                $result = $result && $args[0]->deletable();
            }
        }
        return $result;
    }
}
