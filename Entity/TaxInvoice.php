<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;

/**
 * TaxInvoice Entity
 */
class TaxInvoice extends Income
{
    /**
     * @var string
     */
    protected $discount;
    
    /**
     * @var string
     */
    protected $costItemTotal; // total - discount
    
    /**
     * @var bool
     */
    protected $vatFactor;    // is vat?
    
    /**
     * @var bool
     */
    protected $vatIncluded;  // is vat include?
    
    /**
     * @var string
     */
    protected $vat;
    
    /**
     * @var string
     */
    protected $vatCost;
    
    /**
     * @var string
     */
    protected $excludeVat;   // exclude vat
    
    /**
     * @var string
     */
    protected $docTotal;     // include vat
    
    /**
     * @var string
     */
    protected $tax;
    
    /**
     * @var bool
     */
    protected $taxFactor;
    
    /**
     * @var string
     */
    protected $taxCost;
    
    /**
     * @var string
     */
    protected $payTotal;
    
    /**
     * @var string
     */
    protected $retention;
    
    /**
     * @var bool
     */
    protected $retentionFactor;
    
    /**
     * @var string
     */
    protected $retentionCost;
    
    /**
     * @var string
     */
    protected $retentionPayTotal;
    
    /**
     * @var string
     */
    protected $remarkFinance;
    
    /**
     * @var string
     */
    protected $netTotal;
    
    
    /**
     * @var \DateTimeImmutable
     */
    protected $paymentDate;
    
    /**
     * @var string
     */
    protected $paymentChannel;
    
    
    /**
     * @var string
     */
    protected $remarkOther;
    
    
    /**
     * constructor
     *
     * @param Thing|null $thing
     */
    public function __construct(Thing $thing = null)
    {
        parent::__construct($thing);
    }
    
    public function getDiscount()
    {
        return $this->discount;
    }
    
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        
        return $this;
    }
    
    public function getCostItemTotal()
    {
        return $this->costItemTotal;
    }
    
    public function setCostItemTotal($costItemTotal)
    {
        $this->costItemTotal = $costItemTotal;
        
        return $this;
    }
    
    public function getVatFactor()
    {
        return $this->vatFactor;
    }
    
    public function setVatFactor($vatFactor)
    {
        $this->vatFactor = $vatFactor;
        
        return $this;
    }
    
    public function getVatIncluded()
    {
        return $this->vatIncluded;
    }
    
    public function setVatIncluded($vatIncluded)
    {
        $this->vatIncluded = $vatIncluded;
        
        return $this;
    }
    
    public function getVat()
    {
        return $this->vat;
    }
    
    public function setVat($vat)
    {
        $this->vat = $vat;
        
        return $this;
    }
    
    public function getVatCost()
    {
        return $this->vatCost;
    }
    
    public function setVatCost($vatCost)
    {
        $this->vatCost = $vatCost;
        
        return $this;
    }
    
    public function getExcludeVat()
    {
        return $this->excludeVat;
    }
    
    public function setExcludeVat($excludeVat)
    {
        $this->excludeVat = $excludeVat;
        
        return $this;
    }
    
    public function getDocTotal()
    {
        return $this->docTotal;
    }
    
    public function setDocTotal($docTotal)
    {
        $this->docTotal = $docTotal;
        
        return $this;
    }
    
    public function getTax()
    {
        return $this->tax;
    }
    
    public function setTax($tax)
    {
        $this->tax = $tax;
        
        return $this;
    }
    
    public function getTaxFactor()
    {
        return $this->taxFactor;
    }
    
    public function setTaxFactor($taxFactor)
    {
        $this->taxFactor = $taxFactor;
        
        return $this;
    }
    
    public function getTaxCost()
    {
        return $this->taxCost;
    }
    
    public function setTaxCost($taxCost)
    {
        $this->taxCost = $taxCost;
        
        return $this;
    }
    
    public function getPayTotal()
    {
        return $this->payTotal;
    }
    
    public function setPayTotal($payTotal)
    {
        $this->payTotal = $payTotal;
        
        return $this;
    }
    
    /**
     * Get the value of Remark Finance
     *
     * @return string
     */
    public function getRemarkFinance()
    {
        return $this->remarkFinance;
    }
    
    /**
     * Set the value of Remark Finance
     *
     * @param mixed remarkFinance
     *
     * @return static
     */
    public function setRemarkFinance($remarkFinance)
    {
        $this->remarkFinance = $remarkFinance;
        
        return $this;
    }
    
    public function getRetention()
    {
        return $this->retention;
    }
    
    public function setRetention($retention)
    {
        $this->retention = $retention;
        
        return $this;
    }
    
    public function getRetentionFactor()
    {
        return $this->retentionFactor;
    }
    
    public function setRetentionFactor($retentionFactor)
    {
        $this->retentionFactor = $retentionFactor;
        
        return $this;
    }
    
    public function getRetentionCost()
    {
        return $this->retentionCost;
    }
    
    public function setRetentionCost($retentionCost)
    {
        $this->retentionCost = $retentionCost;
        
        return $this;
    }
    
    public function getRetentionPayTotal()
    {
        return $this->retentionPayTotal;
    }
    
    public function setRetentionPayTotal($retentionPayTotal)
    {
        $this->retentionPayTotal = $retentionPayTotal;
        
        return $this;
    }
    
    public function getNetTotal()
    {
        return $this->netTotal;
    }
    
    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;
        
        return $this;
    }
    
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }
    
    public function setPaymentDate(\DateTimeImmutable $paymentDate)
    {
        $this->paymentDate = $paymentDate;
        
        return $this;
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
    
    
    public function getRemarkOther()
    {
        return $this->remarkOther;
    }
    
    public function setRemarkOther($remarkOther)
    {
        $this->remarkOther = $remarkOther;
        
        return $this;
    }
    
    
}
