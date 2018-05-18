<?php

namespace Erp\Bundle\DocumentBundle\Domain\CQRS;

use Erp\Bundle\CoreBundle\Domain\CQRS\CoreAccountQuery;

use Erp\Bundle\DocumentBundle\Entity\Document;

/**
 * Document Query (CQRS)
 */
interface DocumentQuery extends CoreAccountQuery {
    public function origin(Document $doc);
}
