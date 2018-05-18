<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

trait DocumentReadonlyAuthorizationTrait
{
    public function approve(...$args)
    {
        return false;
    }

    public function cancel(...$args)
    {
        return false;
    }

    public function reject(...$args)
    {
        return false;
    }

    public function terminate(...$args)
    {
        return false;
    }
}
