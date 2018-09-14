<?php
namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;
use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\MasterBundle\Entity\Vendor;

/**
 * PurchaseOrder Entity
 */
class RequestForQuotation extends Purchase
{

    /**
     *
     * @var ArrayCollection
     */
    protected $requestedVendors;

   
    /**
     *
     * @var \DateTimeImmutable
     */
    protected $dueDate;



    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);
        $this->requestedVendors = new ArrayCollection();
    }

    /**
     *
     * @return Vendor[]
     */
    public function getRequestedVendors()
    {
        return $this->requestedVendors->toArray();
    }

    /**
     * Add requestedVendor
     *
     * @param Vendor $vendor
     *
     * @return static
     */
    public function addRequestedVendor(Vendor $vendor)
    {
        if (!$this->requestedVendors->contains($vendor)) {
            $this->requestedVendors[] = $vendor;
        }
        
        return $this;
    }
    
    /**
     * Remove requestedVendor
     *
     * @param Vendor $vendor
     */
    public function removeRequestedVendor(Vendor $vendor)
    {
        $this->requestedVendors->removeElement($vendor);
    }
    


    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

  

}
