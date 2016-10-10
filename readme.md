# Metasearch_l5 #

## Overview ##

Metasearch is a package for Laravel 5. It allows you to easily create an Eloquent query that match with a search form. By using a list of keywords on your search form input fields names, Metasearch_l5 will automatically create the corresponding query . 
It's more or less a port of activerecord-hackery/meta_search. Right n now i'm using it a lot in Rest web services, as it allows great flexibility when you need some search capabilities.

Here is a simple example :
 
first the search form :

    {{ Form::open(['url' => URL::route('cars.search')]) }}
	    {{ Form::label('year : ') }}
	    {{ Form::text('year_equals') }}

		{{ Form::label('minumum horsepower : ') }}
	    {{ Form::text('horsepower_greater_than') }}

		{{ Form::label('maximum horsepower : ') }}
	    {{ Form::text('horsepower_less_than') }}

	    {{ Form::submit('submit') }}
    {{ Form::close() }}

Then in your CarsController :


		$input = Request::all();
        $query = Search::getQuery(Car::getQuery(), $input);
        $cars = $query->get();
		return view('cars.index', ['cars' => $cars]);

And that's it, the getQuery method will create the Query based on the Input content.

## Installation ##

first add the dependency to uyour composer.json file : 

		"require": {
			"elfif/MetaSearch_L5" : "*"
		}

and do a composer install.

Then edit your app.php file. Add the service provider for this package : 
		
		'Elfif\MetaSearch\MetaSearchServiceProvider'

and add the facade : 
		
		'Search' => 'Elfif\MetaSearch\Facades\Search'	

Now you are good to go !!

## Syntax ##

Here is a list of all the keywords you can use with LaraSearch, based on their datatypes

### All data types

* _equals_ (alias: _eq_) - Just as it sounds.
* _does_not_equal_ (alias : _noteq) - The opposite of equals, oddly enough.
* _is_in_ - Takes an array, matches on equality with any of the items in the array.
* _is_not_in_ - Like above, but negated.
* _is_null_ - The column has an SQL NULL value.
* _is_not_null_ - The column contains anything but NULL.

### Strings

* _contains - Substring match.
* _does_not_contain - Negative substring match.
* _starts_with (alias: _sw) - Match strings beginning with the entered term.
* _does_not_start_with (alias: _dnsw) - The opposite of above.
* _ends_with (alias: _ew) - Match strings ending with the entered term.
* _does_not_end_with (alias: _dnew) - Negative of above.

### Numbers, dates, and times

* _greater_than (alias: _gt) - Greater than.
* _greater_than_or_equal_to (alias: _gteq) - Greater than or equal to.
* _less_than (alias: _lt) - Less than.
* _less_than_or_equal_to (alias: _lteq) - Less than or equal to.

### Booleans

* _is_true - Is true. Useful for a checkbox like "only show admin users".
* _is_false - The complement of _is_true.

### Non-boolean data types

* _is_present - As with _is_true, useful with a checkbox. Not NULL or the empty string.
* _is_blank - Returns records with a value of NULL or the empty string in the column.

### ORed conditions

If you'd like to match on one of several possible columns, you can do this:

		{{ Form::label('name : ') }}
	    {{ Form::text('name_or_brand_equals') }}
	    
Just keep in mind the condition will be the same for all columns.	    


### Querying relation existence

##### Simple case

You can also check for a relation existence using the keyword "exist" like that.
Let's say we have a relation called accessories in our car model : 

		public function accessories(){
			return $this->hasMany('App\Accessory');
		}
		
We can request that relation's existence using the keyword "_exists" (or _ex in short) this way :

		{{ Form::label('has accessories : ') }}
		{{ Form::checkbox('accessories_exists') }}
		
If the checkbox is checked it will add that condition to the query builder

		->has('accessories');
		
##### With a count		
		
You may also specify an operator and count to further customize the query using the keyword "_count" (or _co_ in short) plus a keyword to define the condition

		{{ Form::label('has accessories : ') }}
		{{ Form::text('accessories_count_greater_than') }}
		
Will translate into :

		->has('accessories', '>=', $value);
		
		
##### Full condition

If you need even more control over your conditions regarding a relation you can use that notation, with a dot between the relation and the field's name, followed by a condition

		{{ Form::label('accessories : ') }}
		{{ Form::text('accessories.type_contains') }}
		
It will translate into : 

		->whereHas('accessories', function($query){
			return $query->where('type', 'like', '%'.$value.'%');
		});

