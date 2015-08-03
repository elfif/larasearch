<?php namespace Elfif\MetaSearch_L5; 

use Illuminate\Support\ServiceProvider;

class MetaSearchServiceProvider extends ServiceProvider {

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
            $this->app['metasearch'] = $this->app->share(function($app)
            {
                return new MetaSearch();
            });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
            return array('metasearch');
	}

}
