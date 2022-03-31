<?php

namespace Erp\Bundle\DocumentBundle\Authorization;

use Erp\Bundle\DocumentBundle\Domain\CQRS\PurchaseQuery;
use Erp\Bundle\DocumentBundle\Entity\Purchase;

abstract class AbstractPurchaseAuthorization extends AbstractDocumentAuthorization
{
    /**
     * @var PurchaseQuery
     */
    protected $domainQuery;

    /**
     * @var PurchaseQuery
     */
    protected $referenceQuery;

    protected function isUserGrantedByService(PurchaseQuery $service, array $args, array $types): bool
    {
        switch(count($args)) {
            case 0:
                return true;
            case 1:
                /** @var Purchase $item */
                if(
                    (($item = $args[0]) instanceof Purchase) &&
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
        return $this->isUserGrantedByService($this->referenceQuery, $args, $types);
    }
}
