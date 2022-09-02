<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\Thing;

use Erp\Bundle\MasterBundle\Entity\Vendor;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\ProjectBoq;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqBudgetType;

/**
 * Purchase Entity
 */
abstract class Purchase extends Document
{
    /**
     * vendor
     *
     * @var Vendor
     */
    protected $vendor;

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
     * shippingAddress
     *
     * @var string
     */
    protected $shippingAddress;

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
     * wantedDate
     *
     * @var \DateTimeImmutable
     */
    protected $wantedDate;

    /**
     * startDate
     *
     * @var \DateTimeImmutable
     */
    protected $startDate;

    /**
     * finishDate
     *
     * @var \DateTimeImmutable
     */
    protected $finishDate;

    /**
     * deliveryDate
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
     * document total
     *
     * @var string
     */
    protected $docTotal;

    /**
     * details
     *
     * @var ArrayCollection
     */
    protected $details;

    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);

        $this->details = new ArrayCollection();
    }

    /**
     * get vendor
     *
     * @return Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * set vendor
     *
     * @param Vendor $vendor
     *
     * @return static
     */
    public function setVendor(?Vendor $vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

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
     * @param \DateTimeImmutable $startDate
     *
     * @return static
     */
    public function setStartDate(?\DateTimeImmutable $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeImmutable $startDate
     *
     * @return static
     */
    public function setFinishDate(?\DateTimeImmutable $finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getFinishDate()
    {
        return $this->finishDate;
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
     * get document total
     *
     * @return string
     */
    public function getDocTotal()
    {
        return $this->docTotal;
    }

    /**
     * set document total
     *
     * @param string $docTotal
     *
     * @return static
     */
    public function setDocTotal($docTotal)
    {
        $this->docTotal = $docTotal;

        return $this;
    }

    /**
     * get details
     *
     * @return PurchaseDetail[]
     */
    public function getDetails()
    {
        return $this->details->toArray();
    }

    /**
     * add detail
     *
     * @param PurchaseDetail $detail
     *
     * @return static
     */
    public function addDetail(PurchaseDetail $detail)
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
        }

        return $this;
    }

    /**
     * remove detail
     *
     * @param PurchaseDetail $detail
     */
    public function removeDetail(PurchaseDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    public function setDetails(ArrayCollection $details)
    {
        $this->details = $details;

        return $this;
    }
}
