# Larasearch #

## Overview ##

Larasearch is a package for Laravel 5. It allows you to easily create an Eloquent query that match with a search form. By using a list of keywords on your search form input fields names, Larasearch will automatically create the corresponding query . 
It's more or less a port of activerecord-hackery/meta_search.

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


		$input = Input::all();
        $query = Search::getQuery(Car::getQuery(), $input);
        $cars = $query->get();
		return View::make('cars.index', ['cars' => $cars]);

And that's it, the generateQuery method will create the Query based on the Input content.

## Installation ##

first add the dependency to uyour composer.json file : 

		"require": {
			"elfif/Larasearch" : "*"
		}

and do a composer install.

Then edit your app.php file. Add the service provider for this package : 
		
		'Elfif\LaraSearch\LaraSearchServiceProvider'

and add the facade : 
		
		'Search' => 'Elfif\LaraSearch\Facades\Search'	

Now you are good to go !!

## Syntax ##

Here is a list of all the keywords you can use with LaraSearch, based on their datatypes

All data types

* _equals_ (alias: _eq_) - Just as it sounds.
* _does_not_equal_ (aliases: _ne_, _noteq_) - The opposite of equals, oddly enough.
* _in_ - Takes an array, matches on equality with any of the items in the array.
* _not_in_ (aliases: _ni_, _notin_) - Like above, but negated.
* _is_null_ - The column has an SQL NULL value.
* _is_not_null_ - The column contains anything but NULL.

Strings

* _contains_ (aliases: _like_, _matches_) - Substring match.
* _does_not_contain_ (aliases: _nlike_, _nmatches_) - Negative substring match.
* _starts_with_ (alias: _sw_) - Match strings beginning with the entered term.
* _does_not_start_with_ (alias: _dnsw_) - The opposite of above.
* _ends_with_ (alias: _ew_) - Match strings ending with the entered term.
* _does_not_end_with_ (alias: _dnew_) - Negative of above.

Numbers, dates, and times

* _greater_than_ (alias: _gt_) - Greater than.
* _greater_than_or_equal_to_ (aliases: _gte_, _gteq_) - Greater than or equal to.
* _less_than_ (alias: _lt_) - Less than.
* _less_than_or_equal_to_ (aliases: _lte_, _lteq_) - Less than or equal to.

Booleans

* _is_true_ - Is true. Useful for a checkbox like "only show admin users".
* _is_false_ - The complement of _is_true_.

Non-boolean data types

* _is_present_ - As with _is_true_, useful with a checkbox. Not NULL or the empty string.
* _is_blank_ - Returns records with a value of NULL or the empty string in the column.
