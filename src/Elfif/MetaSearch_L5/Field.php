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
class Field {

    /**
     * @string
     * original value
     */
     
    public $fieldStr;
    
    /**
     * Field type
     * defined by constants
     */ 
    
    public $type;
    
    /**
     * @string
     * Field name
     */

    public $name;
    
    /**
     * @string
     * Relation name
     */
     
    public $relation;
     
    public function __construct($fieldStr){
        $this->fieldStr = $fieldStr;
        $this->parseField();
    } 
    
    public function getType(){
        return $this->type;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getRelation(){
        return $this->relation;
    }

    protected function parseField(){
        
        $pos = strrpos($this->fieldStr, ".");
        if ($pos){
            $this->type = self::WHEREHAS;
            $this->relation = substr($this->fieldStr, 0, $pos);
            $this->name = substr($this->fieldStr, $pos+1, strlen($this->fieldStr) - $pos);
            return $this->type;
        }
        
        $pos2 = strpos($this->fieldStr, "_count");
        if ($pos2){
            $this->type = self::HAS;
            $this->relation = substr($this->fieldStr, 0, $pos2);
            return $this->type;
        }
        
        $this->type = self::WHERE;
        $this->name = $this->fieldStr;
        return $this->type;
    }
    
    
    CONST WHERE = 0;
    CONST HAS = 1;
    CONST WHEREHAS = 2;
}