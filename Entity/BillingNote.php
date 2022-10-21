<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;
use Erp\Bundle\DocumentBundle\Model\PaymentMethodSelectableProperties;

/**
 * BillingNote Entity
 */
class BillingNote extends IncomeFinance implements PaymentMethodSelectableProperties
{
    /**
     * @var bool
     */
    protected $bankCheck;

    /**
     * @var string[]
     */
    protected $transferMoney;

    /**
     * @var bool
     */
    protected $other;

    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);
        $this->transferMoney = [];
    }

    public function getBankCheck()
    {
        return $this->bankCheck;
    }

    public function setBankCheck($bankCheck)
    {
        $this->bankCheck = $bankCheck;

        return $this;
    }
    /**
     * Get transferMoney
     *
     * @return string[]
     */
    public function getTransferMoney()
    {
        if (null === $this->transferMoney) {
            $this->transferMoney = [];
        }
        return $this->transferMoney;
    }

    /**
     * Get transferMoney
     *
     * @param string[] $transferMoney
     *
     * @return BillingNote
     */
    public function setTransferMoney(array $transferMoney)
    {
        $this->transferMoney = $transferMoney;

        return $this;
    }


    public function getOther()
    {
        return $this->other;
    }

    public function setOther($other)
    {
        $this->other = $other;

        return $this;
    }
}
