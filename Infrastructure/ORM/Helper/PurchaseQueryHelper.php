<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Helper;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\AliasUniqueGenerator;

/**
 *
 * @author pachara
 *        
 */
class PurchaseQueryHelper
{
    private $gen;
    
    private $docHq;
    
    /**
     */
    public function __construct(AliasUniqueGenerator $gen, DocumentQueryHelper $docHq)
    {
        $this->gen = $gen;
        
        $this->docHq = $docHq;
    }
    
    /**
     * Validating operator must be and/or.
     *
     * @param mixed $operator
     * @throws \InvalidArgumentException
     * @return bool true for string and false for array
     */
    private function operatorValidator($operator): bool
    {
        if(is_string($operator)) {
            if(!in_array($operator, ['and', 'or'])) throw new \InvalidArgumentException("String operator must be 'and' or 'or'.");
            return true;
        } else if(!($operator instanceof \stdClass)) {
            throw new \InvalidArgumentException("Operator must be string or stdClass.");
        }
        
        return false;
    }
    
    /**
     * Add where-clause to querybuilder.
     *
     * @param QueryBuilder $qb
     * @param string $method
     * @param array $whereClause
     * @return QueryBuilder
     */
    private function addWhereClause(QueryBuilder $qb, string $method, $whereClause): QueryBuilder
    {
        return $qb->{$method}($whereClause->expression)
        ->setParameters($whereClause->parameters)
        ;
    }
    
    private function copyWhereClause($target, $source)
    {
        $target->expression = $source->expression;
        $target->parameters = $source->parameters;
    }
    
    /**
     * Apply remains filter of details from the next specific $statusChangedRepos.
     * 
     * @param QueryBuilder $qb
     * @param string $refDocClass Purchase class that change the previous detail, e.g. DocumentBundle:PurchaseOrder
     * @param string $statusChangedClass The class of statusChanged that associates with changed detail (previous detail), e.g. DocumentBundle:PurchaseDetailStatusChanged
     * @param string $refBackName attribute name of statusChanged class that refers to changed detail (previous detail), e.g. purchaseRequestDetail
     * @param string $operator
     * @param string|null $alias optional alias to purchase
     * @param string|null $entityClass optional class of purchase
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyRemainFilter(QueryBuilder $qb, string $refDocClass, string $statusChangedClass, string $refBackName, string $operator, string $alias = null, string $entityClass = null): QueryBuilder
    {
        $isOperator = $this->operatorValidator($operator);
        
        if($alias === null) $alias = $qb->getAllAliases()[0];
        if($entityClass === null) $entityClass = $qb->getRootEntities()[array_search($alias, $qb->getRootAliases())];
        $entityDetailClass = $entityClass.'Detail';
        $refDocDetailClass = $refDocClass.'Detail';
        $existAlias = $this->gen->unique('_exist_');
        $subRefDocAlias = $this->gen->unique('_sub_ref_doc_');
        $subRefDocDetailAlias = $this->gen->unique('_sub_ref_doc_detail_');
        $subStatusChangedAlias = $this->gen->unique('_sub_status_changed_');
        $existDetailAlias = $this->gen->unique('_sub_doc_detail_');
        $existJoinAlias = $this->gen->unique('_sub_join_');
        
        $qbExpr = $qb->expr();
        $subRefDocQb = $qb->getEntityManager()->getRepository($refDocClass)->createQueryBuilder($subRefDocAlias);
        $subRefDocExpr = $subRefDocQb->expr();
        $classMetadataStatusChanged = $subRefDocQb->getEntityManager()->getClassMetadata($statusChangedClass);
        
        $subRefDocQb
            ->innerJoin($refDocDetailClass, $subRefDocDetailAlias, 'WITH', $subRefDocExpr->eq("{$subRefDocAlias}.id", "{$subRefDocDetailAlias}.purchase"))
            ->innerJoin("{$subRefDocDetailAlias}.statusChanged", $subStatusChangedAlias)
//             ->andWhere(
//                 $subRefDocExpr->eq(($classMetadataStatusChanged->hasField('finish') && ($classMetadataStatusChanged->getTypeOfField('finish') === 'boolean'))? "{$subStatusChangedAlias}.finish" : 1, 1)
//             )
        ;
        $this->docHq->applyAliveFilter($subRefDocQb, 'and');
        
        $existQb = $qb->getEntityManager()->getRepository($entityClass)->createQueryBuilder($existAlias);
        $existExpr = $existQb->expr();
        $existQb
            //->innerJoin("{$existAlias}.details", $existDetailAlias)
            ->innerJoin($entityDetailClass, $existDetailAlias, 'WITH', $existExpr->eq("{$existAlias}.id", "{$existDetailAlias}.purchase"))
            ->leftJoin($statusChangedClass, $existJoinAlias, 'WITH', $existExpr->andX(
                $existExpr->eq("{$existJoinAlias}.{$refBackName}", $existDetailAlias), // NOTE may be this condition is not needed (it's same as in-clause)
                $existExpr->in($existJoinAlias,
                    $subRefDocQb->select($subStatusChangedAlias)->andWhere(
                        $subRefDocExpr->eq("{$existJoinAlias}.{$refBackName}", $existDetailAlias)
                    )->getDQL()
                )
            ))
            ->andWhere($existExpr->isNull($existJoinAlias))
            ->setParameters($subRefDocQb->getParameters())
        ;
        
        $whereClauseAlive = (object)[];
        $this->docHq->applyAliveFilter($qb, $whereClauseAlive);
        
        $whereClause = (object)[
            'expression' => $qb->expr()->andX(
                $whereClauseAlive->expression,
                $qb->expr()->exists(
                    $existQb->andWhere($qbExpr->eq($existAlias, $alias))->getDQL()
                )
            ),
            'parameters' => array_merge($whereClauseAlive->parameters, $existQb->getParameters()->toArray()),
        ];
        
        if($isOperator) {
            $method = $operator.'Where';
            $this->addWhereClause($qb, $method, $whereClause);
        } else {
            $this->copyWhereClause($operator, $whereClause);
        }
        return $qb;
    }
}
