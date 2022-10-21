<?php

declare(strict_types=1);

namespace Erp\Bundle\DocumentBundle\Model;

interface PaymentMethodPropreties
{
    /**
     * Net total to pay.
     *
     * @return mixed
     */
    function getNetTotal();

    /**
     * Payment Date.
     *
     * @return \DateTimeImmutable
     */
    function getPaymentDate();

    /**
     * Remark of payment method.
     *
     * @return string
     */
    function getRemarkFinance();

    /**
     * Detail of other channel.
     */
    public function getRemarkOther();
}
