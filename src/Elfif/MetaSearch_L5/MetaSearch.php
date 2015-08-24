<?php namespace Elfif\MetaSearch_L5; 

/**
 * Description of metaSearch
 *
 * @author pmichel
 */



class MetaSearch {
    
    private $formData;
    private $query;

    public function setQuery($query){
        $this->query = $query;
    }
    
    public function setFormData($formData){
        $this->formData = $formData;
    }   

    public static function getQuery($modelQuery, $formData){
        $s = new MetaSearch();
        dd($formData);
        $s->setQuery($modelQuery);
        $s->setFormData($formData);
        return $s->generateQuery();
    }

    public function generateQuery(){
        dd($this->formData);
        foreach($this->formData as $key => $value){
            $laraQuery = new MetaQuery($key, $value, $this->query);
            $this->query = $laraQuery->getQuery();
        }
        return $this->query;
    }
}