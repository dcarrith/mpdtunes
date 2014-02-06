<?php namespace Dcarrith\LxMPD;

use Illuminate\Support\ServiceProvider;
use Dcarrith\LxMPD\LxMPD;

class LxMPDServiceProvider extends ServiceProvider {

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
		$this->package('dcarrith/lxmpd');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['lxmpd'] = $this->app->share(function($app)
		{
			return new LxMPD($app['host'], $app['port'], $app['password']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('lxmpd');
	}

}
