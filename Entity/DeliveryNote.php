<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\CoreBundle\Entity\Thing;

/**
 * DeliveryNote Entity
 */
class DeliveryNote extends Income
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
     * Alias of getDocTotal().
     * For backward compatible.
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->getDocTotal();
    }

    /**
     * Alias of setDocTotal().
     * For backward compatible.
     *
     * @param string $total
     *
     * @return static
     */
    public function setTotal($total)
    {
        return $this->setDocTotal($total);

        return $this;
    }
}
