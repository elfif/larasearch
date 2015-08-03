<?php namespace Elfif\LaraSearch;

/**
 * Description of metaSearch
 *
 * @author pmichel
 */



class LaraSearch {
    
    private $formData;
    private $query;

    public function setQuery($query){
        $this->query = $query;
    }
    
    public function setFormData($formData){
        $this->formData = $formData;
    }   

    public static function getQuery($modelQuery, $formData){
        $s = new LaraSearch();
        $s->setQuery($modelQuery);
        $s->setFormData($formData);
        return $s->generateQuery();
    }

    public function generateQuery(){

        foreach($this->formData as $key => $value){
            $laraQuery = new laraQuery($key, $value, $this->query);
            $this->query = $laraQuery->getQuery();
        }
        return $this->query;
    }
}