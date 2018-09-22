<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Listener;

use Erp\Bundle\DocumentBundle\Entity\RequestForQuotation;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Erp\Bundle\DocumentBundle\Entity\RequestForQuotationVendor;

/**
 *
 * @author Asus
 *        
 */
class RequestForQuotationListener
{
    public function preFlush(RequestForQuotation $entity, PreFlushEventArgs $event) {
        //$entity->setThing($entity->getIndividual()->getThing());
        if(empty($entity->getId())){
            /**
             * @var \Erp\Bundle\CoreBundle\Entity\Generator
             */
            $generator = $event->getEntityManager()->getRepository("ErpCoreBundle:Generator")->generator('request-for-quotation');
            $entity->setCode($generator->nextValue(date('Ymd')));
            $entity->setTstmp(new \DateTimeImmutable());
            if($entity->getUpdateOf() !== null) {
                $event->getEntityManager()->remove($entity->getThing());
                $entity->setThing($entity->getUpdateOf()->getThing());
            } else {
                $entity->setName($entity->getCode());
            }
            
            foreach($entity->getDetails() as $detail) {
                $detail->setRequestForQuotation($entity);
            }
            
            foreach($entity->getRequestedVendors() as $vendor) {
                $rqvendor = new RequestForQuotationVendor($vendor);
                $rqvendor->setUuid(\Erp\Bundle\CoreBundle\Util\Uuid::uuidv4());
                $entity->addRequestForQuotationVendor($rqvendor);
            }
        }
    }
}

