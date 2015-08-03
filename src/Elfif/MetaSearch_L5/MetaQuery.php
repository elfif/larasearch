<?php namespace Elfif\MetaSearch_L5; 

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
class metaQuery {
   
    private $key;
    private $fields;
    private $fieldStr;
    private $value;
    private $operator;
    private $query;

        
    public function __construct($pKey, $pValue, $pQuery) {
        $this->key = $pKey;
        $this->value = $pValue;
        $this->query = $pQuery;
    }
    
    private function setOperator() {
        foreach($this->keywords as $this->keyword){
            if ($this->str_ends_with($this->key, $this->keyword)){
                $this->operator = $this->keyword;
                $this->fieldStr = str_replace($this->keyword, '', $this->key);
            }
        }
    }
    
    private function setFields(){
        $this->fields = explode( self::OU, $this->fieldStr);
//        var_dump($this->key);
//        var_dump($this->operator);
//        var_dump($this->value);
//        var_dump($this->fields);
        
        
    }
    
    public function getQuery(){
        $this->addQuery();
        return $this->query;
    }
    
    
    
    private function addQuery(){
        $this->setOperator();
        $this->setFields();
        
        if (isset($this->operator) && isset($this->fields)){
            foreach($this->fields as $index=>$field){
                $or = ( $index ? true : false );
                call_user_func(array($this, $this->functions[$this->operator]), $field, $or);
            }
        }
    }
    
    private function equalsQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, $this->value);
        } else {
            $this->query->where($field, $this->value);
        }
    }

    private function doesNotEqualQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, "!=", $this->value);
        } else {
            $this->query->where($field, "!=", $this->value);
        }
    }

    private function isInQuery($field, $or){
        if ($or) {
            $this->query->orWhereIn($field, $this->value);
        } else {
            $this->query->whereIn($field, $this->value);
        }
    }

    private function isNotInQuery($field, $or){
        if ($or) {
            $this->query->orWhereNotIn($field, $this->value);
        } else {
            $this->query->whereNotIn($field, $this->value);
        }
    }

    private function isNullQuery($field){
        if ($or) {
            $this->query->orWhereNull($field);
        } else {
            $this->query->whereNull($field);
        }
    }

    private function isNotNullQuery($field){
        if ($or) {
            $this->query->orWhereNotNull($field);
        } else {
            $this->query->whereNotNull($field);
        }
    }

    private function containsQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'like', '%'.$this->value.'%');
        } else {
            $this->query->where($field, 'like', '%'.$this->value.'%');
        }
    }

    private function doesNotContainQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'not like', '%'.$this->value.'%');
        } else {
            $this->query->where($field, 'not like', '%'.$this->value.'%');
        }
    }

    private function startsWithQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'like', $this->value.'%');
        } else {
            $this->query->where($field, 'like', $this->value.'%');
        }
    }

    private function doesNotStartWithQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'not like', $this->value.'%');
        } else {
            $this->query->where($field, 'not like', $this->value.'%');
        }
    }

    private function endsWithQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'like', '%'.$this->value);
        } else {
            $this->query->where($field, 'like', '%'.$this->value);
        }
    }

    private function doesNotEndWithQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, 'not like', '%'.$this->value);
        } else {
            $this->query->where($field, 'not like', '%'.$this->value);
        }
    }

    private function greaterThanQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, '>', $this->value);
        } else {
            $this->query->where($field, '>', $this->value);
        }
    }

    private function greaterThanOrEqualQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, '>=', $this->value);
        } else {
            $this->query->where($field, '>=', $this->value);
        }
    }

    private function lessThanQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, '<', $this->value);
        } else {
            $this->query->where($field, '<', $this->value);
        }
    }

    private function lessThanOrEqualQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, '<=', $this->value);
        } else {
            $this->query->where($field, '<=', $this->value);
        }
    }

    private function isTrueQuery($field, $or){
        if ($or) {
            $this->query->orWere($field, true);
        } else {
            $this->query->where($field, true);
        }
    }

    private function isFalseQuery($field, $or){
        if ($or) {
            $this->query->orWhere($field, false);
        } else {
            $this->query->where($field, false);
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
    CONST DOES_NOT_EQUAL = '_does_not_equals';
    CONST IS_IN = '_is_in';
    CONST IS_NOT_IN = '_is_not_in';
    CONST IS_NULL = '_is_null';
    CONST IS_NOT_NULL = '_is_not_null';
    CONST CONTAINS = '_contains';
    CONST DOES_NOT_CONTAIN = '_does_not_contains';
    CONST STARTS_WITH = '_starts_with';
    CONST ENDS_WITH = '_ends_with';
    CONST DOES_NOT_START_WITH = '_does_not_start_with';
    CONST DOES_NOT_END_WITH = '_does_not_end_with';
    CONST GREATER_THAN = '_greater_than';
    CONST GREATER_THAN_OR_EQUAL = '_greater_than_or_equal';
    CONST LESS_THAN = '_less_than';
    CONST LESS_THAN_OR_EQUAL = '_less_than_or_equal';
    CONST IS_TRUE = '_is_true';
    CONST IS_FALSE = '_is_false';
    
    private $keywords = array(
        self::EQUALS, 
        self::DOES_NOT_EQUAL,
        self::IS_IN,
        self::IS_NOT_IN,
        self::IS_NULL,
        self::IS_NOT_NULL,
        self::CONTAINS,
        self::DOES_NOT_CONTAIN,
        self::STARTS_WITH,
        self::ENDS_WITH,
        self::DOES_NOT_START_WITH,
        self::DOES_NOT_END_WITH,
        self::GREATER_THAN_OR_EQUAL,
        self::GREATER_THAN,
        self::LESS_THAN_OR_EQUAL,
        self::LESS_THAN,
        self::IS_TRUE,
        self::IS_FALSE
    );
    
    private $functions = array(
      self::EQUALS => 'equalsQuery',
      self::DOES_NOT_EQUAL => 'doesNotEqualQuery',
      self::IS_IN => 'isInQuery',
      self::IS_NOT_IN => 'isNotInQuery',
      self::IS_NULL =>'isNullQuery',
      self::IS_NOT_NULL =>'isNotNullQuery',
      self::CONTAINS =>'containsQuery',
      self::DOES_NOT_CONTAIN =>'doesNotContainQuery',
      self::STARTS_WITH =>'startsWithQuery',
      self::ENDS_WITH =>'endsWithQuery',
      self::DOES_NOT_START_WITH =>'doesNotStartWithQuery',
      self::DOES_NOT_END_WITH =>'doesNotEndWithQuery',
      self::GREATER_THAN_OR_EQUAL =>'greaterThanOrEqualQuery',
      self::GREATER_THAN =>'greaterThanQuery',
      self::LESS_THAN_OR_EQUAL =>'lessThanOrEqualQuery',
      self::LESS_THAN =>'lessThanQuery',
      self::IS_TRUE =>'isTrueQuery',
      self::IS_FALSE =>'isFalseQuery'
    );
    
}
