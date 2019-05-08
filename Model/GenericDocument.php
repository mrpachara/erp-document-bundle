<?php
namespace Erp\Bundle\DocumentBundle\Model;

use Erp\Bundle\DocumentBundle\Entity\Document;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 *
 * @author pachara
 *        
 */
class GenericDocument
{
    /**
     * Id.
     * 
     * @var string
     */
    private $id;
    
    /**
     * Discriminator value.
     * 
     * @var string
     */
    private $dtype;
    
    /**
     * Timestamp.
     *
     * @var \DateTimeImmutable
     */
    private $tstmp;
    
    /**
     * Approved.
     *
     * @var bool
     */
    private $approved;
    
    /**
     * Creator
     *
     * @var SystemUser
     */
    protected $creator;
    
    /**
     * @var Document
     */
    protected $updateOf;
    
    /**
     * Code.
     * 
     * @var string
     */
    private $code;
    
    /**
     * Name.
     * 
     * @var string
     */
    private $name;
    
    /**
     */
    public function __construct(Document $doc, string $dtype = 'document')
    {
        
    }
}

