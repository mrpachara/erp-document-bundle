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
     * @param Quotation|null $quotation
     */
    public function __construct(Quotation $quotation = null)
    {
        parent::__construct($quotation);
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
