<?php

namespace Elfif\MetaSearch_L5; 

/**
 * Gère l'ajout d'une opération à la requête principale. 
 */
 
class Operation
{

    private $query;

    private $field;

    private $operator;

    private $value;

    private $or;

    /**
     * Operation constructor.
     *
     * @param $query
     * @param $operator
     * @param $field
     * @param $value
     * @param bool $or
     */
    public function __construct($query, $operator, $field, $value, $or = false)
    {
        $this->query = $query;
        $this->operator = $operator;
        $this->field = $field;
        $this->value = $value;
        $this->or = $or;
    }

    public function getQuery()
    {
        call_user_func(array($this, $this->operator), $this->field, $this->or);

        return $this->query;
    }

    /**
     * @param $field
     * @param $operator
     * @param null $operand
     * @param null $or
     */
    private function generateWhereQuery($field, $operator, $operand = null, $or = null)
    {
        switch($field->type){
            case Field::WHERE:
                if (isset($operand) && isset($this->value)) {
                    $this->query->$operator($field->name, $operand, $this->value);
                } elseif (empty($operand) && isset($this->value)) {
                    $this->query->$operator($field->name, $this->value);
                } else {
                    $this->query->$operator($field->name);
                }
                break;
            case Field::HAS:
                if (isset($operand) && isset($this->value)) {
                    $this->query->$operator($field->relation, $operand, $this->value);
                } else {
                    $this->query->$operator($field->relation);
                }
                break;
            case Field::WHEREHAS:
                if ($this->or) {
                    $firstOperator = "orWhereHas";
                    $operator = 'where';
                } else {
                    $firstOperator = 'whereHas';
                }

                if (isset($operand) && isset($this->value)) {
                    $this->query->$firstOperator($field->relation, function($query) use ($field, $operand, $operator){
                        return $query->$operator($field->name, $operand, $this->value);
                    });
                } else {
                    $this->query->$firstOperator($field->relation, function($query) use ($field, $operator){
                        return $query->$operator($field->name);
                    });
                }
                break;
        }
    }

    private function equalsQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '=');
    }

    private function doesNotEqualQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '!=');
    }

    private function isInQuery($field, $or)
    {
        $operator = ($or) ? "orWhereIn" : "whereIn";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNotInQuery($field, $or)
    {
        $operator = ($or) ? "orWhereNotIn" : "whereNotIn";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNullQuery($field, $or)
    {
        $operator = ($or) ? "orWhereNull" : "whereNull";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNotNullQuery($field, $or)
    {
        $operator = ($or) ? "orWhereNotNull" : "whereNotNull";
        $this->generateWhereQuery($field, $operator);
    }

    private function containsQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value.'%';
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotContainQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value.'%';
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function startsWithQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = $this->value.'%';
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotStartWithQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = $this->value.'%';
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function endsWithQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value;
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotEndWithQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value;
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function greaterThanQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '>');
    }

    private function greaterThanOrEqualQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '>=');
    }

    private function lessThanQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '<');
    }

    private function lessThanOrEqualQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '<=');
    }

    private function isTrueQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = true;
        $this->generateWhereQuery($field, $operator, '=');
    }

    private function isFalseQuery($field, $or)
    {
        $operator = ($or) ? "orWhere" : "where";
        $this->value = false;
        $this->generateWhereQuery($field, $operator, '=');
    }

    private function existsQuery($field, $or)
    {
        if ($or){
            $this->query->orHas($field->name);
        } else {
            $this->query->has($field->name);
        }
    }

}