<?php

declare(strict_types=1);

namespace Erp\Bundle\DocumentBundle\Model;

interface PaymentMethodSelectableProperties extends PaymentMethodPropreties
{
    /**
     * Allow Bank Cheque.
     */
    public function getBankCheck();

    /**
     * Allow Bank Accounts.
     */
    public function getTransferMoney();

    /**
     * Allow other channel.
     */
    public function getOther();
}
