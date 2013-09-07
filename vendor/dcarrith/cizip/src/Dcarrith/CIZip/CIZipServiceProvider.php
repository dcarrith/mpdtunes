<?php namespace Dcarrith\CIZip;

use Illuminate\Support\ServiceProvider;
use Dcarrith\CIZip\CIZip;

class CIZipServiceProvider extends ServiceProvider {

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
		$this->package('dcarrith/cizip');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['cizip'] = $this->app->share(function($app)
		{
			return new CIZip();
			//return new CIFirePHP($app['url']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cizip');
	}

}
