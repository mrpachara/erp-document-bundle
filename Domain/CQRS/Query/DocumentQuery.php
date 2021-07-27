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
     * Get tracking related documents depend on SystemUser.
     * 
     * @param SystemUser $user
     * @return array
     */
    function getRelatedDocumentTracking(SystemUser $user): array;
    
    /**
     * Get next related documents depend on SystemUser.
     * 
     * @param SystemUser $user
     * @return array
     */
    function getRelatedDocumentNext(SystemUser $user): array;
}

