<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Listener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Erp\Bundle\DocumentBundle\Entity\Revenue;

class RevenueListener {

  public function preFlush(Revenue $entity, PreFlushEventArgs $event) {
    //$entity->setThing($entity->getIndividual()->getThing());
    if(empty($entity->getId())){
      /**
       * @var \Erp\Bundle\CoreBundle\Entity\Generator
       */
      $generator = $event->getEntityManager()->getRepository("ErpCoreBundle:Generator")->generator('revenue');
      $entity->setCode($generator->nextValue(date('Ymd')));
      $entity->setTstmp(new \DateTimeImmutable());
      if($entity->getUpdateOf() !== null) {
        $event->getEntityManager()->remove($entity->getThing());
        $entity->setThing($entity->getUpdateOf()->getThing());
      } else {
        $entity->setName($entity->getCode());
      }

      foreach($entity->getDetails() as $detail) {
        $detail->setIncome($entity);
      }
    }
  }
}
