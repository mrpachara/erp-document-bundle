<?php
declare(strict_types = 1);

namespace Erp\Bundle\DocumentBundle\Model;

interface PaymentProperties
{
    /**
     * Sum of costItem.
     */
    function getTotal();
    
    /**
     * Discount
     */
    function getDiscount();
    
    /**
     * total - discount
     */
    function getCostItemTotal();
    
    /**
     * Is vat calculated?
     */
    function getVatFactor();
    
    /**
     * Is vat included in costItemTotal?
     */
    function getVatIncluded();
    
    /**
     * vat value.
     */
    function getVat();
    
    /**
     * vat cost.
     */
    function getVatCost();
    
    /**
     * price without vat.
     */
    function getExcludeVat();
    
    /**
     * price with vat.
     */
    function getDocTotal();
    
    /**
     * Is tax calculated?
     */
    function getTaxFactor();
    
    /**
     * tax value.
     */
    function getTax();
        
    /**
     * tax cost.
     * excludeVat * tax
     */
    function getTaxCost();
    
    /**
     * Total pay.
     * excludeVat - taxCost + vatCost
     */
    function getPayTotal();
}

