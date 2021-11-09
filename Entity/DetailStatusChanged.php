<?php

namespace Erp\Bundle\DocumentBundle\Entity;

interface DetailStatusChanged {
    const FINISH = 'FINISH';
    const REMOVED = 'REMOVED';
    const REFERRED = 'REFERRED';
    const SPRITTED = 'SPRITTED';

    /**
     * Get Status changed
     */
    public function getStatusChanged();

    /**
     * Set Status changed
     */
    public function setStatusChanged($statusChanged);
}
