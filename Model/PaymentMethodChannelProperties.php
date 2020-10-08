<?php
declare(strict_types = 1);

namespace Erp\Bundle\DocumentBundle\Model;

interface PaymentMethodChannelProperties extends PaymentMethodPropreties
{
    function getPaymentChannel();
}

