<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\Thing;

use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\ProjectBoq;

/**
 * Income Entity
 */
abstract class Income extends Document
{

    /**
     * ownerContactInformation
     *
     * @var string
     */
    protected $ownerContactInformation;

    /**
     * contactOwnerAddress
     *
     * @var string
     */
    protected $contactOwnerAddress;

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
     * total
     *
     * @var string
     */
    protected $total;

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
     * get ownerContactInformation
     *
     * @return string
     */
    public function getOwnerContactInformation()
    {
        return $this->ownerContactInformation;
    }

    /**
     * set ownerContactInformation
     *
     * @param string $ownerContactInformation
     *
     * @return static
     */
    public function setOwnerContactInformation(string $ownerContactInformation)
    {
        $this->ownerContactInformation = $ownerContactInformation;

        return $this;
    }

    /**
     * get contactOwnerAddress
     *
     * @return string
     */
    public function getContactOwnerAddress()
    {
        return $this->contactOwnerAddress;
    }

    /**
     * set contactOwnerAddress
     *
     * @param string $contactOwnerAddress
     *
     * @return static
     */
    public function setContactOwnerAddress(string $contactOwnerAddress)
    {
        $this->contactOwnerAddress = $contactOwnerAddress;

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
     * @param \DateTimeImmutable $deliveryDate
     *
     * @return static
     */
    public function setDeliveryDate(?\DateTimeImmutable $deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
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
     * get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * set total
     * @param string $total
     *
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * get details
     *
     * @return IncomeDetail[]
     */
    public function getDetails()
    {
        return $this->details->toArray();
    }

    /**
     * add detail
     *
     * @param IncomeDetail $detail
     *
     * @return static
     */
    public function addDetail(IncomeDetail $detail)
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
        }

        return $this;
    }

    /**
     * remove detail
     *
     * @param IncomeDetail $detail
     */
    public function removeDetail(IncomeDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    public function setDetails(ArrayCollection $details) {
        $this->details = $details;

        return $this;
    }
}
