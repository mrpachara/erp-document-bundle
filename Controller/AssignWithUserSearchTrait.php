<?php

namespace Erp\Bundle\DocumentBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait AssignWithUserSearchTrait {
    abstract protected function getUser();
    abstract protected function tryGrant($callbacks);

    protected function assignWithUserSearchRule($queryParams, $rules)
    {
        $withUser = $this->tryGrant($rules);

        if($withUser instanceof AccessDeniedException) {
            throw new AccessDeniedException("Access denied!!!");
        }

        if(!empty($withUser)) {
            $queryParams['document-with-user'] = [
                'user' => $this->getUser(),
                'types' => $withUser,
            ];
        }

        return $queryParams;
    }
}
