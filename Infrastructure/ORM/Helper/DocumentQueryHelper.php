<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Helper;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\MasterBundle\Entity\Employee;

/**
 *
 * @author pachara
 *        
 */
class DocumentQueryHelper
{
    const prefix = '_document_helper';
    
    private $id;
    
    /**
     */
    public function __construct()
    {
        $this->id = 0;
    }
    
    private function getId(): int
    {
        return $this->id++;
    }
    
    /**
     * Apply DQL where for given employee.
     * 
     * @param QueryBuilder $qb
     * @param Employee $requester
     * @return QueryBuilder
     */
    public function applyRequesterFilter(QueryBuilder $qb, Employee $requester): QueryBuilder
    {
        $alias = $qb->getRootAliases()[0];
        $paramName = self::prefix.$this->getId();
        return $qb->orWhere(
            $qb->expr()->eq("{$alias}.requester", ":{$paramName}")
        )
        ->setParameter($paramName, $requester);
        ;
    }
    
}

