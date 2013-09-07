<?php namespace Dcarrith\CIFirePHP;

use Illuminate\Support\ServiceProvider;
use Dcarrith\CIFirePHP\CIFirePHP;

class CIFirePHPServiceProvider extends ServiceProvider {

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
		$this->package('dcarrith/cifirephp');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['cifirephp'] = $this->app->share(function($app)
		{
			return new CIFirePHP($app['name'], $app['environment']);
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
		return array('cifirephp');
	}

}
