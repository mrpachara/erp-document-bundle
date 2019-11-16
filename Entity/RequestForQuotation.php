<?php
namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;
use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\MasterBundle\Entity\ProjectBoq;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqBudgetType;
use Erp\Bundle\MasterBundle\Entity\Vendor;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\MasterBundle\Entity\Employee;

/**
 * RequestForQuotation Entity
 */
class RequestForQuotation extends Document
{

    /**
     *
     * @var ArrayCollection
     */
    protected $requestedVendors;
    
    /**
     *
     * @var ArrayCollection
     */
    protected $requestForQuotationVendors;
    
    /**
     * vendorContactInformation
     *
     * @var string
     */
    protected $vendorContactInformation;
    
    /**
     * vendorAddress
     *
     * @var string
     */
    protected $vendorAddress;
    
    /**
     * project
     *
     * @var Project
     */
    protected $project;
    
    
    /**
     * requester
     *
     * @var Employee
     */
    protected $requester;
    
    /**
     * contactInformation
     *
     * @var string
     */
    protected $contactInformation;
    
    /**
     * shippingAddress
     * 
     * @var string
     */
    protected $shippingAddress;
    
    /**
     * wantedDate
     *
     * @var \DateTimeImmutable
     */
    protected $wantedDate;
    
    /**
     * wantedDate
     *
     * @var \DateTimeImmutable
     */
    protected $deliveryDate;
    
    /**
     * boq
     *
     * @var ProjectBoq
     */
    protected $boq;
    
    /**
     * budgetType
     *
     * @var ProjectBoqBudgetType
     */
    protected $budgetType;
  
    /**
     * details
     *
     * @var ArrayCollection
     */
    protected $details;
    
    
    /**
     * get vendorContactInformation
     *
     * @return string
     */
    public function getVendorContactInformation()
    {
        return $this->vendorContactInformation;
    }
    
    /**
     * set vendorContactInformation
     *
     * @param string $vendorContactInformation
     *
     * @return static
     */
    public function setVendorContactInformation(string $vendorContactInformation)
    {
        $this->vendorContactInformation = $vendorContactInformation;
        
        return $this;
    }
    
    /**
     * get vendorAddress
     *
     * @return string
     */
    public function getVendorAddress()
    {
        return $this->vendorAddress;
    }
    
    /**
     * set vendorAddress
     *
     * @param string $vendorAddress
     *
     * @return static
     */
    public function setVendorAddress(string $vendorAddress)
    {
        $this->vendorAddress = $vendorAddress;
        
        return $this;
    }
    
    /**
     * get shippingAddress
     *
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
    
    /**
     * set shippingAddress
     *
     * @param string $shippingAddress
     *
     * @return static
     */
    public function setShippingAddress(string $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        
        return $this;
    }
    
    
    /**
     * get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
    
    /**
     * set project
     *
     * @param Project $project
     *
     * @return static
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
        
        return $this;
    }
    
    /**
     * get requester
     *
     * @return Employee
     */
    public function getRequester()
    {
        return $this->requester;
    }
    
    /**
     * set requester
     *
     * @param Employee $requester
     *
     * @return static
     */
    public function setRequester(Employee $requester)
    {
        $this->requester = $requester;
        
        return $this;
    }
    
    
    /**
     * get contactInformation
     *
     * @return string
     */
    public function getContactInformation()
    {
        return $this->contactInformation;
    }
    
    /**
     * set contactInformation
     *
     * @param string $contactInformation
     *
     * @return static
     */
    public function setContactInformation(string $contactInformation)
    {
        $this->contactInformation = $contactInformation;
        
        return $this;
    }
    
    /**
     * get wantedDate
     *
     * @return \DateTimeImmutable
     */
    public function getWantedDate()
    {
        return $this->wantedDate;
    }
    
    /**
     * set wantedDate
     *
     * @param \DateTimeImmutable $wantedDate
     *
     * @return static
     */
    public function setWantedDate(\DateTimeImmutable $wantedDate)
    {
        $this->wantedDate = $wantedDate;
        
        return $this;
    }
    
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }
    
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
        
        return $this;
    }
    
    /**
     * get boq
     *
     * @return ProjectBoq
     */
    public function getBoq()
    {
        return $this->boq;
    }
    
    /**
     * set boq
     *
     * @param ProjectBoq $boq
     *
     * @return static
     */
    public function setBoq(ProjectBoq $boq)
    {
        $this->boq = $boq;
        
        return $this;
    }
    
    /**
     * get budgetType
     *
     * @return ProjectBoqBudgetType
     */
    public function getBudgetType()
    {
        return $this->budgetType;
    }
    
    /**
     * set budgetType
     *
     * @param ProjectBoqBudgetType $budgetType
     *
     * @return static
     */
    public function setBudgetType(ProjectBoqBudgetType $budgetType)
    {
        $this->budgetType = $budgetType;
        
        return $this;
    }
    
    /**
     * get details
     *
     * @return RequestForQuotationDetail[]
     */
    public function getDetails()
    {
        return $this->details->toArray();
    }
    
    /**
     * add detail
     *
     * @param RequestForQuotationDetail $detail
     *
     * @return static
     */
    public function addDetail(RequestForQuotationDetail $detail)
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
        }
        
        return this;
    }
    
    /**
     * remove detail
     *
     * @param RequestForQuotationDetail $detail
     */
    public function removeDetail(RequestForQuotationDetail $detail)
    {
        $this->details->removeElement($detail);
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
    
    
    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);
        $this->requestedVendors = new ArrayCollection();
        $this->requestForQuotationVendors = new ArrayCollection();
    }
    
    /**
     *
     * @return Vendor[]
     */
    public function getRequestForQuotationVendors()
    {
        return $this->requestForQuotationVendors->toArray();
    }
    
    /**
     * Add requestForQuotationVendors
     *
     * @param RequestForQuotationVendor $rqvendor
     *
     * @return static
     */
    public function addRequestForQuotationVendor(RequestForQuotationVendor $rqvendor)
    {
        if (!$this->requestForQuotationVendors->contains($rqvendor)) {
            $this->requestForQuotationVendors[] = $rqvendor;
        }
        
        return $this;
    }
    
    /**
     * Remove requestForQuotationVendors
     *
     * @param RequestForQuotationVendor $rqvendor
     */
    public function removeRequestForQuotationVendor(RequestForQuotationVendor $rqvendor)
    {
        $this->requestForQuotationVendors->removeElement($rqvendor);
    }
  

}
