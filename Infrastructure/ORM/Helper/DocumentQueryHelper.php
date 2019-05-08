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
     * Validating operator must be and/or.
     * 
     * @param string $operator
     * @throws \InvalidArgumentException
     */
    private function operatorValidator(string $operator)
    {
        if(!in_array($operator, ['and', 'or'])) throw new \InvalidArgumentException("Operator must be 'and' or 'or'.");
    }
    
    /**
     * Apply DQL where for given employee.
     * 
     * @param QueryBuilder $qb
     * @param Employee $requester
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyRequesterFilter(QueryBuilder $qb, Employee $requester, string $operator): QueryBuilder
    {
        $this->operatorValidator($operator);
        
        $method = $operator.'Where';
        $alias = $qb->getRootAliases()[0];
        $paramName = self::prefix.$this->getId();
        return $qb->{$method}(
            $qb->expr()->eq("{$alias}.requester", ":{$paramName}")
        )
        ->setParameter($paramName, $requester);
        ;
    }
    
    /**
     * 
     * @param QueryBuilder $qb
     * @param bool $approved
     * @param string $operator
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyApproveFilter(QueryBuilder $qb, bool $approved, string $operator): QueryBuilder
    {
        $this->operatorValidator($operator);
        
        $alias = $qb->getAllAliases()[0];
        $method = $operator.'Where';
        
        return $qb->{$method}($qb->expr()->eq("{$alias}.approved", ($approved)? 1 : 0));
    }
    
    /**
     * Applying filter for non-terminated and non-replaced documents.
     * 
     * @param QueryBuilder $qb
     * @param string $operator
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyAliveFilter(QueryBuilder $qb, string $operator): QueryBuilder
    {
        $this->operatorValidator($operator);
        
        $alias = $qb->getAllAliases()[0];
        $updatedAlias = $alias."_documentUpdatedBy_".$this->getId();
        $method = $operator.'Where';
        
        return $qb
            ->leftJoin("{$alias}.updatedBys", $updatedAlias, 'WITH', "{$updatedAlias}.terminated IS NULL")
            ->{$method}(
                $qb->expr()->andX(
                    "{$alias}.terminated IS NULL",
                    "{$updatedAlias} IS NULL"
                )
            )
        ;
    }

    /**
     * Applying filter for next process ready documents.
     *
     * @param QueryBuilder $qb
     * @param string $operator
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyActiveFilter(QueryBuilder $qb, string $operator): QueryBuilder
    {
        $this->operatorValidator($operator);
        
        $alias = $qb->getAllAliases()[0];
        $updatedAlias = $alias."_documentUpdatedBy_".$this->getId();
        $transferedAlias = $alias."_documentTransferedBy_".$this->getId();
        $method = $operator.'Where';
        
        return $qb
            ->leftJoin("{$alias}.updatedBys", $updatedAlias, 'WITH', "{$updatedAlias}.terminated IS NULL")
            ->leftJoin("{$alias}.transferedBys", $transferedAlias, 'WITH', "{$transferedAlias}.terminated IS NULL")
            ->{$method}(
                $qb->expr()->andX(
                    "{$alias}.terminated IS NULL",
                    "{$updatedAlias} IS NULL",
                    "{$transferedAlias} IS NULL"
                )
            )
        ;
    }
}

