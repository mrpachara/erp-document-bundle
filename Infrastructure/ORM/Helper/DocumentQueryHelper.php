<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Helper;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\AliasUniqueGenerator;

/**
 *
 * @author pachara
 *
 */
class DocumentQueryHelper
{
    private $gen;

    /**
     */
    public function __construct(AliasUniqueGenerator $gen)
    {
        $this->gen = $gen;
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
     * @param mixed $whereClause
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
     * Apply DQL where for given employee.
     *
     * @param QueryBuilder $qb
     * @param Employee $requester
     * @param mixed $operator
     * @param string|null $alias optional alias to document
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyRequesterFilter(QueryBuilder $qb, Employee $requester, $operator, string $alias = null): QueryBuilder
    {
        $isOperator = $this->operatorValidator($operator);

        if($alias === null) $alias = $qb->getRootAliases()[0];
        $paramName = $this->gen->unique('_requester_');

        $whereClause = (object)[
            'expression' => $qb->expr()->eq("{$alias}.requester", ":{$paramName}"),
            'parameters' => [$paramName => $requester],
        ];

        if($isOperator) {
            $method = $operator.'Where';
            $this->addWhereClause($qb, $method, $whereClause);
        } else {
            $this->copyWhereClause($operator, $whereClause);
        }
        return $qb;
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param bool $approved
     * @param mixed $operator
     * @param string|null $alias optional alias to document
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyApproveFilter(QueryBuilder $qb, bool $approved, $operator, string $alias = null): QueryBuilder
    {
        $isOperator = $this->operatorValidator($operator);

        if($alias === null) $alias =  $qb->getRootAliases()[0];

        $whereClause = (object)[
            'expression' => $qb->expr()->eq("{$alias}.approved", ($approved)? 1 : 0),
            'parameters' => [],
        ];

        if($isOperator) {
            $method = $operator.'Where';
            $this->addWhereClause($qb, $method, $whereClause);
        } else {
            $this->copyWhereClause($operator, $whereClause);
        }
        return $qb;
    }

    /**
     * Applying filter for non-terminated and non-replaced documents.
     *
     * @param QueryBuilder $qb
     * @param mixed $operator
     * @param string|null $alias optional alias to document
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyAliveFilter(QueryBuilder $qb, $operator, string $alias = null): QueryBuilder
    {
        $isOperator = $this->operatorValidator($operator);

        if($alias === null) $alias = $qb->getRootAliases()[0];
        $updatedAlias = $this->gen->unique('_documentUpdatedBy_');

        $qb
            ->leftJoin("{$alias}.updatedBys", $updatedAlias, 'WITH', "{$updatedAlias}.terminated IS NULL")
        ;

        $whereClause = (object)[
            'expression' => $qb->expr()->andX(
                "{$alias}.terminated IS NULL",
                "{$updatedAlias} IS NULL"
            ),
            'parameters' => [],
        ];

        if($isOperator) {
            $method = $operator.'Where';
            $this->addWhereClause($qb, $method, $whereClause);
        } else {
            $this->copyWhereClause($operator, $whereClause);
        }
        return $qb;
    }

    /**
     * Applying filter for next process ready documents.
     *
     * @param QueryBuilder $qb
     * @param mixed $operator
     * @param string|null $alias optional alias to document
     * @throws \InvalidArgumentException
     * @return QueryBuilder
     */
    public function applyActiveFilter(QueryBuilder $qb, $operator, string $alias = null): QueryBuilder
    {
        $isOperator = $this->operatorValidator($operator);

        if($alias === null) $alias =  $qb->getRootAliases()[0];
        $transferedAlias = $this->gen->unique('_documentTransferedBy_');
        $method = $operator.'Where';

        $whereClauseAlive = (object)[];
        $this->applyAliveFilter($qb, $whereClauseAlive)
            ->leftJoin("{$alias}.transferedBys", $transferedAlias, 'WITH', "{$transferedAlias}.terminated IS NULL")
        ;

        $whereClause = (object)[
            'expression' => $qb->expr()->andX(
                $whereClauseAlive->expression,
                "{$transferedAlias} IS NULL"
            ),
            'parameters' => $whereClauseAlive->parameters,
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
