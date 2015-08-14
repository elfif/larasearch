<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchTest
 *
 * @author FIF
 */
use Mockery as m;
use Elfif\LaraSearch\LaraSearch as Search;

class SearchTest extends PHPUnit_Framework_TestCase{
    
    public function tearDown()
    {
        m::close();
    }
    
    public function testEqualsQuery(){
        $value = 10;
        $input = ['field_equals' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testEqualsOrEqualsQuery(){
        $value = 10;
        $input = ['field1_or_field2_equals' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field1', $value);
        $queryBuilder->shouldReceive('orWhere')->with('field2', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testDoesNotEqualQuery(){
        $value = 10;
        $input = ['field_does_not_equal' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', '!=', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsInQuery(){
        $values = [10,11,12];
        $input = ['field_is_in' => $values];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('whereIn')->with('field', $values);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsNotInQuery(){
        $values = [10,11,12];
        $input = ['field_is_not_in' => $values];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('whereNotIn')->with('field', $values);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsNullQuery(){
        $input = ['field_is_null'];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsNotNullQuery(){
        $input = ['field_is_not_null'];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('whereNotNull')->with('field');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testContainsQuery(){
        $value = 'some_string';
        $input = ['field_contains' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'like', '%'.$value.'%');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testContainsOrQuery(){
        $value = 'some_string';
        $input = ['field1_or_field2_contains' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field1', 'like', '%'.$value.'%');
        $queryBuilder->shouldReceive('orWhere')->with('field2', 'like', '%'.$value.'%');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testDoesNotContainQuery(){
        $value = 'some_string';
        $input = ['field_does_not_contain' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'not like', '%'.$value.'%');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testStartsWithQuery(){
        $value = 'some_string';
        $input = ['field_starts_with' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'like', $value.'%');
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testDoesNotStartWithQuery(){
        $value = 'some_string';
        $input = ['field_does_not_start_with' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'not like', $value.'%');
        Search::getQuery($queryBuilder, $input);
    }
    
        
    public function testEndsWithQuery(){
        $value = 'some_string';
        $input = ['field_ends_with' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'like', '%'.$value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testDoesNotEndWithQuery(){
        $value = 'some_string';
        $input = ['field_does_not_end_with' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', 'not like', '%'.$value);
        Search::getQuery($queryBuilder, $input);
    }
        
    public function testGreaterThanQuery(){
        $value = 10;
        $input = ['field_greater_than' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', '>', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testGreaterThanOrEqualQuery(){
        $value = 10;
        $input = ['field_greater_than_or_equal' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', '>=', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testLessThanQuery(){
        $value = 10;
        $input = ['field_less_than' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', '<', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testLessThanOrEqualQuery(){
        $value = 10;
        $input = ['field_less_than_or_equal' => $value];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', '<=', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsTrueQuery(){
        $value = true;
        $input = ['field_is_true' => null];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
    public function testIsFalseQuery(){
        $value = false;
        $input = ['field_is_false"' => null];
        $queryBuilder = m::mock('Builder');
        $queryBuilder->shouldReceive('where')->with('field', $value);
        Search::getQuery($queryBuilder, $input);
    }
    
}
