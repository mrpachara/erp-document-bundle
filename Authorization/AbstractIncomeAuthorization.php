<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\DocumentBundle\Domain\CQRS\IncomeQuery;
use Erp\Bundle\DocumentBundle\Entity\Income;

abstract class AbstractIncomeAuthorization extends AbstractDocumentAuthorization
{
    /**
     * @var IncomeQuery
     */
    protected $domainQuery;

    /**
     * @var IncomeQuery
     */
    protected $referenceQuery;

    protected function isUserGrantedByService(IncomeQuery $service, array $args, array $types): bool
    {
        switch (count($args)) {
            case 0:
                return true;
            case 1:
                /** @var Income $item */
                if (
                    (($item = $args[0]) instanceof Income) &&
                    ($user = $this->security->getUser())
                ) {
                    return !empty($service->findWithUser(
                        $item->getId(),
                        $user,
                        $types
                    ));
                } else {
                    return false;
                }
            case 2:
                // TODO: check for old and new, [$old, $new]
                return true;
            default:
                return false;
        }
    }

    protected function isUserGranted(array $args, array $types): bool
    {
        return $this->isUserGrantedByService($this->domainQuery, $args, $types);
    }

    protected function isUserReferenceGranted(array $args, array $types): bool
    {
        /** @var Income $item */
        if ((count($args) > 0) && ($item = $args[0]) instanceof Income) {
            /** @var Income $referedItem */
            $referedItem = $item->getTransferOf();
            if (!empty($referedItem)) {
                $args[0] = $referedItem;
            } else {
                array_shift($args);
            }
        }
        return $this->isUserGrantedByService($this->referenceQuery, $args, $types);
    }
}
