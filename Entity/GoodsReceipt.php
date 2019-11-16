<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;


/**
 * GoodsReceipt Entity
 */
class GoodsReceipt extends Purchase
{

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
     * Last GoodsReceipt
     * @var GoodsReceipt
     */
    protected $lastGoodsReceipt = null;
    
    public function setLastGoodsReceipt($lastGoodsReceipt)
    {
        $this->lastGoodsReceipt = $lastGoodsReceipt;
        
        return $this;
    }

    public function updatable()
    {
        if(!parent::updatable()) return false;
        // TODO: must check null too
        if($this->lastGoodsReceipt !== null && $this->lastGoodsReceipt !== $this) return false;
        
        return true;
    }
}
