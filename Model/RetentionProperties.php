<?php
declare(strict_types = 1);

namespace Erp\Bundle\DocumentBundle\Model;

interface RetentionProperties
{
    /**
     * Is retention calculated?
     */
    public function getRetentionFactor();
    
    /**
     * Retention value.
     */
    public function getRetention();
    
    /**
     * Retention cost.
     */
    public function getRetentionCost();
    
    /**
     * Retention total pay.
     */
    public function getRetentionPayTotal();
}

