<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;
use Erp\Bundle\DocumentBundle\Model\PaymentMethodChannelProperties;

/**
 * IncomeFinancePaymentChannel Entity
 */
abstract class IncomeFinancePaymentChannel extends IncomeFinance implements PaymentMethodChannelProperties
{
    /**
     * @var string
     */
    protected $paymentChannel;

    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);
    }

    /**
     * Get paymentChannel
     *
     * @return string
     */
    public function getPaymentChannel()
    {
        return $this->paymentChannel;
    }

    public function setPaymentChannel($paymentChannel)
    {
        $this->paymentChannel = $paymentChannel;

        return $this;
    }
}
