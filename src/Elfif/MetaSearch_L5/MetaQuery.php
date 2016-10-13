<?php

namespace Elfif\MetaSearch_L5; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of metaQuery
 *
 * @author FIF
 */
class MetaQuery {
   
   
   /**
    * @string
    * initial form key 
    */

    private $key;
    
    /**
     * @array
     * array of fields to test, in case there is a "or" in the key
     */ 
    
    private $fields;
    
    /**
     * @string
     * fields to test, separated by "or", used to create the $fields array
     */ 
    
    private $fieldStr;
    
    /**
     * value to test the fields against 
     */
     
    private $value;
    
    /**
     * @string
     * operator extracted from the $key value
     */
     
    private $operator;
    
    /**
     * @Eloquent\Builder
     * Query Builder where the condition is going to be added
     */
     
    private $query;
    
    /**
     * @boolean
     * Tell if we are in a oredCase ....
     */
     
    private $oredCase;

        
    public function __construct($pKey, $pValue, $pQuery) {
        $this->key = $pKey;
        $this->value = $pValue;
        $this->query = $pQuery;
    }
    
    private function setOperator() {
        foreach($this->keywords as $keyword){
            if ($this->str_ends_with($this->key, $keyword)){
                $this->operator = $keyword;
                $this->fieldStr = str_replace($keyword, '', $this->key);
            }
        }
    }
    
    private function setFields(){
        $fields = explode( self::OU, $this->fieldStr);
        foreach($fields as $fieldStr){
            $this->fields[] = new Field($fieldStr);    
        }
        
        $this->oredCase = false;
        if (count($fields) > 1){
            $this->oredCase = true;
        }
        
    }
    
    
    public function getQuery(){
        $this->addQuery();
        return $this->query;
    }
    
    
    
    private function addQuery(){
        $this->setOperator();
        $this->setFields();

        if (isset($this->operator) && isset($this->fields)){
            if ($this->oredCase)
            {
                $fields = $this->fields;
                $operator = $this->functions[$this->operator];
                $value = $this->value;
                $this->query->where(function($query) use ($fields, $operator){
                    foreach($fields as $index=>$field){    
                        $or = ( $index ? true : false );
                        $op = new Operation($query, $operator, $field, $value, $or);
                        $query = $op->getQuery();        
                    }
                });
            } else {
                $op = new Operation($this->query, $this->functions[$this->operator], $this->fields[0], $this->value);
                $this->query = $op->getQuery();
            }     
        }
    }

    private function generateWhereQuery($field, $operator, $operand=null, $or = null){

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
            
            $firstOperator = ($or) ? "orWhereHas" : "whereHas" ;
            
            if (isset($operand) && isset($this->value)) {
                $this->query->$firstOperator($field->relation, function($query) use ($field, $operand){
                    return $query->$operator($field->name, $operand, $this->value);
                });    
            } else {
                $this->query->$firstOperator($field->relation, function($query) use ($field){
                    return $query->$operator($field->name);
                });    
            }
        }
    }
    
    
    private function equalsQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '=');
    }

    private function doesNotEqualQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '!=');
    }

    private function isInQuery($field, $or){
        $operator = ($or) ? "orWhereIn" : "whereIn";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNotInQuery($field, $or){
        $operator = ($or) ? "orWhereNotIn" : "whereNotIn";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNullQuery($field, $or){
        $operator = ($or) ? "orWhereNull" : "whereNull";
        $this->generateWhereQuery($field, $operator);
    }

    private function isNotNullQuery($field, $or){
        $operator = ($or) ? "orWhereNotNull" : "whereNotNull";
        $this->generateWhereQuery($field, $operator);
    }

    private function containsQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value.'%';
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotContainQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value.'%';
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function startsWithQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = $this->value.'%';
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotStartWithQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = $this->value.'%';
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function endsWithQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value;
        $this->generateWhereQuery($field, $operator, 'like');
    }

    private function doesNotEndWithQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = '%'.$this->value;
        $this->generateWhereQuery($field, $operator, 'not like');
    }

    private function greaterThanQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '>');

    }

    private function greaterThanOrEqualQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '>=');
    }

    private function lessThanQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '<');
    }

    private function lessThanOrEqualQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->generateWhereQuery($field, $operator, '<=');
    }

    private function isTrueQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = true;
        $this->generateWhereQuery($field, $operator, '=');
    }

    private function isFalseQuery($field, $or){
        $operator = ($or) ? "orWhere" : "where";
        $this->value = false;
        $this->generateWhereQuery($field, $operator, '=');
    }
    
    
    private function existsQuery($field, $or){
        if ($or){
            $this->query->orHas($field->name);
        } else {
            $this->query->has($field->name);
        }
    }
    
    
    private function str_ends_with($haystack, $needle)
    {
        $pos = strpos($haystack, $needle);
        if (!$pos){
            return false;
        }
        return $pos + strlen($needle) === strlen($haystack);
    }
    
    CONST OU = '_or_';
    CONST EQUALS = '_equals';
    CONST EQUALS_SHORT = '_eq';
    CONST DOES_NOT_EQUAL = '_does_not_equals';
    CONST DOES_NOT_EQUAL_SHORT = '_noteq';
    CONST IS_IN = '_is_in';
    CONST IS_NOT_IN = '_is_not_in';
    CONST IS_NULL = '_is_null';
    CONST IS_NOT_NULL = '_is_not_null';
    CONST CONTAINS = '_contains';
    CONST DOES_NOT_CONTAIN = '_does_not_contains';
    CONST STARTS_WITH_SHORT = '_sw';
    CONST ENDS_WITH_SHORT = '_ew';
    CONST STARTS_WITH = '_starts_with';
    CONST ENDS_WITH = '_ends_with';
    CONST DOES_NOT_START_WITH = '_does_not_start_with';
    CONST DOES_NOT_END_WITH = '_does_not_end_with';
    CONST DOES_NOT_START_WITH_SHORT = '_not_sw';
    CONST DOES_NOT_END_WITH_SHORT = '_not_ew';
    CONST GREATER_THAN = '_greater_than';
    CONST GREATER_THAN_OR_EQUAL = '_greater_than_or_equal';
    CONST LESS_THAN = '_less_than';
    CONST LESS_THAN_OR_EQUAL = '_less_than_or_equal';
    CONST GREATER_THAN_SHORT= '_gt';
    CONST GREATER_THAN_OR_EQUAL_SHORT = '_gteq';
    CONST LESS_THAN_SHORT= '_lt';
    CONST LESS_THAN_OR_EQUAL_SHORT = '_lteq';
    CONST IS_TRUE = '_is_true';
    CONST IS_FALSE = '_is_false';
    CONST EXISTS = '_exists';
    
    private $keywords = array(
        self::EQUALS, 
        self::DOES_NOT_EQUAL,
        self::EQUALS_SHORT, 
        self::DOES_NOT_EQUAL_SHORT,
        self::IS_IN,
        self::IS_NOT_IN,
        self::IS_NULL,
        self::IS_NOT_NULL,
        self::CONTAINS,
        self::DOES_NOT_CONTAIN,
        self::STARTS_WITH,
        self::ENDS_WITH,
        self::STARTS_WITH_SHORT,
        self::ENDS_WITH_SHORT,
        self::DOES_NOT_START_WITH,
        self::DOES_NOT_END_WITH,
        self::DOES_NOT_START_WITH_SHORT,
        self::DOES_NOT_END_WITH_SHORT,
        self::GREATER_THAN_OR_EQUAL_SHORT,
        self::GREATER_THAN_SHORT,
        self::LESS_THAN_OR_EQUAL_SHORT,
        self::LESS_THAN_SHORT,
        self::GREATER_THAN_OR_EQUAL,
        self::GREATER_THAN,
        self::LESS_THAN_OR_EQUAL,
        self::LESS_THAN,
        self::IS_TRUE,
        self::IS_FALSE,
        self::EXISTS
    );
    
    private $functions = array(
      
      self::EQUALS_SHORT => 'equalsQuery',
      self::DOES_NOT_EQUAL_SHORT => 'doesNotEqualQuery',
      self::EQUALS => 'equalsQuery',
      self::DOES_NOT_EQUAL => 'doesNotEqualQuery',
      self::IS_IN => 'isInQuery',
      self::IS_NOT_IN => 'isNotInQuery',
      self::IS_NULL =>'isNullQuery',
      self::IS_NOT_NULL =>'isNotNullQuery',
      self::CONTAINS =>'containsQuery',
      self::DOES_NOT_CONTAIN =>'doesNotContainQuery',
      self::STARTS_WITH_SHORT =>'startsWithQuery',
      self::ENDS_WITH_SHORT =>'endsWithQuery',
      self::DOES_NOT_START_WITH_SHORT =>'doesNotStartWithQuery',
      self::DOES_NOT_END_WITH_SHORT =>'doesNotEndWithQuery',
      self::STARTS_WITH =>'startsWithQuery',
      self::ENDS_WITH =>'endsWithQuery',
      self::DOES_NOT_START_WITH =>'doesNotStartWithQuery',
      self::DOES_NOT_END_WITH =>'doesNotEndWithQuery',
      self::GREATER_THAN_OR_EQUAL_SHORT =>'greaterThanOrEqualQuery',
      self::GREATER_THAN_SHORT =>'greaterThanQuery',
      self::LESS_THAN_OR_EQUAL_SHORT =>'lessThanOrEqualQuery',
      self::LESS_THAN_SHORT=>'lessThanQuery',
      self::GREATER_THAN_OR_EQUAL =>'greaterThanOrEqualQuery',
      self::GREATER_THAN =>'greaterThanQuery',
      self::LESS_THAN_OR_EQUAL =>'lessThanOrEqualQuery',
      self::LESS_THAN =>'lessThanQuery',
      self::IS_TRUE =>'isTrueQuery',
      self::IS_FALSE =>'isFalseQuery',
      self::EXISTS => 'existsQuery'
    );
    
}

