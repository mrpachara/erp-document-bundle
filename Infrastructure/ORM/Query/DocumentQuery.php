<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Query;

use Erp\Bundle\DocumentBundle\Domain\CQRS\Query\DocumentQuery as QueryInterface;
use Erp\Bundle\DocumentBundle\Infrastructure\ORM\Helper\DocumentQueryHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Erp\Bundle\DocumentBundle\Entity\Document;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\CoreAccountQueryHelper;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\ErpQueryHelper;

/**
 *
 * @author pachara
 *        
 */
class DocumentQuery implements QueryInterface
{
    /**
     * @var EntityRepository
     */
    private $repos;
    
    private $docHq;
    
    private $accHq;
    
    private $erpHq;
    
    /**
     */
    public function __construct(
        RegistryInterface $doctrine,
        DocumentQueryHelper $docHq,
        CoreAccountQueryHelper $accHq,
        ErpQueryHelper $erpHq
    )
    {
        $this->repos = $doctrine->getRepository(Document::class);
        
        $this->docHq = $docHq;
        $this->accHq = $accHq;
        $this->erpHq = $erpHq;
    }

    function getRelatedDocument(SystemUser $user): array
    {
        $qb = $this->repos->createQueryBuilder('_doc');
        $qb
            ->where($qb->expr()->in("_doc.thing",
                $this->repos->createQueryBuilder('_doc_thing')
                    ->select('_thing')
                    ->join('_doc_thing.thing', '_thing')
                    ->where($qb->expr()->eq('_doc_thing.creator', ':creator'))
                ->getDQL()
            ))
            ->setParameter('creator', $user)
        ;
        
        dump($qb->getDQL());
            
        return $qb->getQuery()->getResult();
    }
}

