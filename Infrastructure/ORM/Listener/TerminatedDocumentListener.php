<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Listener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Erp\Bundle\DocumentBundle\Entity\TerminatedDocument;

use Erp\Bundle\CoreBundle\Domain\CQRS\GeneratorQuery;

class TerminatedDocumentListener {

  public function preFlush(TerminatedDocument $entity, PreFlushEventArgs $event) {
    //$entity->setThing($entity->getIndividual()->getThing());
    if(empty($entity->getId())){
      $entity->setTstmp(new \DateTimeImmutable());
    }
  }
}
