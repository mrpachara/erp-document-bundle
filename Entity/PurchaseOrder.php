<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Collection\ArrayCollection;
use Erp\Bundle\CoreBundle\Entity\CoreAccount;
use Erp\Bundle\CoreBundle\Entity\Thing;

use Erp\Bundle\MasterBundle\Entity\Vendor;
use Erp\Bundle\MasterBundle\Entity\Project;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\MasterBundle\Entity\ProjectBoq;
use Erp\Bundle\MasterBundle\Entity\ProjectBoqBudgetType;

/**
 * PurchaseOrder Entity
 */
class PurchaseOrder extends Purchase
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
     * @var bool
     */
    protected $productWarranty;

    /**
     * @var string
     */
    protected $productWarrantyCost;

    /**
     * @var bool
     */
    protected $payMethod;

    /**
     * @var int
     */
    protected $creditDay;

    /**
     * @var \DateTimeImmutable
     */
    protected $dueDate;

    /**
     * @var bool
     */
    protected $payTerm;

    /**
     * @var string
     */
    protected $payDeposit;

    /** @var string */
    protected $remarkFinance;

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

    public function getProductWarranty()
    {
        return $this->productWarranty;
    }

    public function setProductWarranty($productWarranty)
    {
        $this->productWarranty = $productWarranty;

        return $this;
    }

    public function getProductWarrantyCost()
    {
        return $this->productWarrantyCost;
    }

    public function setProductWarrantyCost($productWarrantyCost)
    {
        $this->productWarrantyCost = $productWarrantyCost;

        return $this;
    }

    public function getPayMethod()
    {
        return $this->payMethod;
    }

    public function setPayMethod($payMethod)
    {
        $this->payMethod = $payMethod;

        return $this;
    }

    public function getCreditDay()
    {
        return $this->creditDay;
    }

    public function setCreditDay($creditDay)
    {
        $this->creditDay = $creditDay;

        return $this;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getPayTerm()
    {
        return $this->payTerm;
    }

    public function setPayTerm($payTerm)
    {
        $this->payTerm = $payTerm;

        return $this;
    }

    public function getPayDeposit()
    {
        return $this->payDeposit;
    }

    public function setPayDeposit($payDeposit)
    {
        $this->payDeposit = $payDeposit;

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
}
