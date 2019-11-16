<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\CoreAccount;
use Erp\Bundle\CoreBundle\Entity\Thing;

use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * Document Entity
 */
abstract class Document extends CoreAccount implements DocumentStatus
{
    /**
     * tstmp
     *
     * @var \DateTimeImmutable
     */
    protected $tstmp;

    /**
     * approved
     *
     * @var bool
     */
    protected $approved;

    /**
     * creator
     *
     * @var SystemUser
     */
    protected $creator;

    /**
     * @var static
     */
    protected $updateOf;

    /**
     * @var ArrayCollection
     */
    protected $updatedBys;

    /**
     * @var Document
     */
    protected $transferOf;

    /**
     * @var ArrayCollection
     */
    protected $transferedBys;

    /**
     * @var TerminatedDocument
     */
    protected $terminated;

    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);

        $this->updatedBys = new ArrayCollection();
        $this->transferedBys = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getTstmp()
    {
        return $this->tstmp;
    }

    /**
     * set tstmp
     *
     * @param \DateTimeImmutable $tstmp
     *
     * @return static
     */
    public function setTstmp(\DateTimeImmutable $tstmp)
    {
        $this->tstmp = $tstmp;

        return $this;
    }

    /**
    * {@inheritDoc}
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * set approved
     *
     * @param bool $approved
     *
     * @return static
     */
    public function setApproved(bool $approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
    * {@inheritDoc}
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * set creator
     *
     * @param SystemUser $creator
     *
     * @return static
     */
    public function setCreator(SystemUser $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
    * {@inheritDoc}
     */
    public function getUpdateOf()
    {
        return $this->updateOf;
    }

    /**
     * set updateOf
     *
     * @param static $updateOf
     *
     * @return static
     */
    public function setUpdateOf(Document $updateOf)
    {
        $this->updateOf = $updateOf;

        return $this;
    }

    /**
    * {@inheritDoc}
     */
    public function getUpdatedBys()
    {
        return $this->updatedBys->toArray();
    }

    /**
     * add updatedBy
     *
     * @param static $updatedBy
     *
     * @return static
     */
    public function addUpdatedBy(Document $updatedBy)
    {
        if (!$this->updatedBys->contains($updatedBy)) {
            $this->updatedBys[] = $updatedBy;
        }

        return $this;
    }

    /**
     * remove updatedBy
     *
     * @param static $updatedBy
     */
    public function removeUpdatedBy(Document $updatedBy)
    {
        $this->updatedBys->removeElement($updatedBy);
    }

    /**
    * {@inheritDoc}
     */
    public function getTransferOf()
    {
        return $this->transferOf;
    }

    /**
     * set transferOf
     *
     * @param Document $transferOf
     *
     * @return static
     */
    public function setTransferOf(Document $transferOf)
    {
        $this->transferOf = $transferOf;

        return $this;
    }

    /**
    * {@inheritDoc}
     */
    public function getTransferedBys()
    {
        return $this->transferedBys->toArray();
    }

    /**
     * add transferedBy
     *
     * @param Document $transferedBy
     *
     * @return static
     */
    public function addTransferedBy(Document $transferedBy)
    {
        if (!$this->transferedBys->contains($transferedBy)) {
            $this->transferedBys[] = $transferedBy;
        }

        return $this;
    }

    /**
     * remove transferedBy
     *
     * @param Document $transferedBy
     */
    public function removeTransferedBy(Document $transferedBy)
    {
        $this->transferedBys->removeElement($transferedBy);
    }

    public function getTransferedBy()
    {
        foreach($this->getTransferedBys() as $doc) {
            if ($doc->getTerminated() === null) {
                return $doc;
            }
        }
        
        return null;
    }
    
    /**
     * get terminated
     *
      * @return TerminatedDocument
     */
    public function getTerminated()
    {
        return $this->terminated;
    }

    /**
     * set terminated
     *
     * @param TerminatedDocument $terminated
     *
     * @return static
     */
    public function setTerminated(TerminatedDocument $terminated)
    {
        $this->terminated = $terminated;

        return $this;
    }

    public function updatable()
    {
        if ($this->getTerminated() !== null) {
            return false;
        }

        foreach ($this->getUpdatedBys() as $doc) {
            if ($doc->getTerminated() === null) {
                return false;
            }
        }

        foreach ($this->getTransferedBys() as $doc) {
            if ($doc->getTerminated() === null) {
                return false;
            }
        }

        return true;
    }

    public function deletable()
    {
        return $this->updatable();
    }

    protected function getUpdateBy()
    {
        foreach ($this->getUpdatedBys() as $doc) {
            if ($doc->getTerminated() === null) {
                return $doc;
            }
        }

        return null;
    }

    public function getStatus()
    {
        $status = null;

        if($this->getTerminated() !== null) {
            $status = $this->getTerminated()->getType();
        } else if($this->getUpdateBy() !== null) {
            $status = 'REPLACED BY '.$this->getUpdateBy()->getCode();
        } else if(!$this->getApproved()) {
            $status = 'DRAFT';
        }

        return $status;
    }
    
    public function getStatusType()
    {
        $status = [
            'type' => null,
            'message' => null,
        ];
        
        if($this->getTerminated() !== null) {
            $user = $this->getTerminated()->getCreator();
            $status['type'] = $this->getTerminated()->getType();
            $status['message'] = sprintf("%s by %s", ucfirst(strtolower($status['type'])), ($user)? $user->getName() : 'unknown');
        } else if(($doc = $this->getUpdateBy()) !== null) {
            $status['type'] = 'REPLACED';
            $status['message'] = sprintf("%s by %s", ucfirst(strtolower($status['type'])), $doc->getCode());
        } else if(($doc = $this->getTransferedBy()) !== null) {
            $status['type'] = 'TRANSFERED';
            $status['message'] = sprintf("%s by %s", ucfirst(strtolower($status['type'])), $doc->getCode());
        } else if(!$this->getApproved()) {
            $status['type'] = 'DRAFT';
            $status['message'] = 'Draft';
        } else {
            $user = $this->getCreator();
            $status['type'] = 'APPROVED';
            $status['message'] = sprintf("%s by %s", ucfirst(strtolower($status['type'])), ($user)? $user->getName() : 'unknown');
        }
        
        return $status;
    }
}
