<?php
namespace Erp\Bundle\DocumentBundle\Entity;

/**
 * @author Asus
 *
 */
class QuotationDetail extends PurchaseDetail
{

    /**
     *
     * @var RequestForQuotationDetail
     */
    protected $requestForQuotationDetail;

    /**
     * constructor
     *
     * @param Quotation|null $purchase
     */
    public function __construct(Quotation $purchase = null)
    {
        parent::__construct($purchase);
    }

    /**
     * @return \Erp\Bundle\DocumentBundle\Entity\RequestForQuotationDetail
     */
    public function getRequestForQuotationDetail()
    {
        return $this->requestForQuotationDetail;
    }

    /**
     * @param RequestForQuotationDetail $requestForQuotationDetail
     * @return static
     */
    public function setRequestForQuotationDetail(?RequestForQuotationDetail $requestForQuotationDetail)
    {
        $this->requestForQuotationDetail = $requestForQuotationDetail;
        
        return $this;
    }
}
