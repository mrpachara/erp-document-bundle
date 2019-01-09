<?php
namespace Erp\Bundle\DocumentBundle\Domain\CQRS\Query;

use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 *
 * @author pachara
 *        
 */
interface DocumentQuery
{
    /**
     * Get related documents depend on SystemUser.
     * 
     * @param SystemUser $user
     * @return array
     */
    function getRelatedDocument(SystemUser $user): array;
}

