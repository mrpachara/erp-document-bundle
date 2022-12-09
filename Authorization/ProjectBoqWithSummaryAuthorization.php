<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\MasterBundle\Authorization\ProjectBoqAuthorization;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectBoqWithSummaryAuthorization
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var ProjectBoqAuthorization */
    private $projectBoqAuthorization;

    function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ProjectBoqAuthorization $projectBoqAuthorization
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->projectBoqAuthorization = $projectBoqAuthorization;
    }

    public function list(...$args): bool
    {
        return
            $this->projectBoqAuthorization->list(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_MASTER_PROJECT_BUDGET_VALUE_VIEW');
    }

    public function get(...$args): bool
    {
        return
            $this->projectBoqAuthorization->get(...$args) &&
            $this->authorizationChecker->isGranted('ROLE_MASTER_PROJECT_BUDGET_VALUE_VIEW');
    }


    public function listWithoutValue(...$args): bool
    {
        return $this->projectBoqAuthorization->list(...$args);
    }

    public function getWithoutValue(...$args): bool
    {
        return $this->projectBoqAuthorization->get(...$args);
    }
}
