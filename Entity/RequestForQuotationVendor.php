<?php
namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\MasterBundle\Entity\Vendor;

class RequestForQuotationVendor extends DocumentObjectValue {
    
    /**
     * @var Vendor
     */
    protected $vendor;
    
    /**
     *
     * @var \DateTimeImmutable
     */
    protected $expiresDate;
    
    /**
     * 
     * @var string
     */
    protected $uuid;

    public function getVendor()
    {
        return $this->vendor;
    }

    public function getExpiresDate()
    {
        return $this->expiresDate;
    }

    public function setExpiresDate($expiresDate)
    {
        $this->expiresDate = $expiresDate;
        
        return $this;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        
        return $this;
    }
    
    public function __construct(Vendor $vendor = null)
    {
        
        $this->vendor = $vendor;
    }

}