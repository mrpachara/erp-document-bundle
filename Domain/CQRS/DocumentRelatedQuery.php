<?php
namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 *
 * @author pachara
 *        
 */
interface DocumentRelatedQuery
{
    public function getAllDocument(SystemUser $user): array;
}

