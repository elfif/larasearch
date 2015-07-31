<?php namespace Elfif\LaraSearch;

use Illuminate\Support\ServiceProvider;

class LaraSearchServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
            
	}

	/**
	 * Register the service provider.
	 *
	 * @return voids
	 */
	public function register()
	{
            $this->app['larasearch'] = $this->app->share(function($app)
            {
                return new LaraSearch();
            });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
            return array('larasearch');
	}

}
