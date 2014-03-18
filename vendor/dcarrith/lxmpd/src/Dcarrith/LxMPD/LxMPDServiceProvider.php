<?php namespace Dcarrith\LxMPD;

use Config;
use Illuminate\Support\ServiceProvider;
use Dcarrith\LxMPD\LxMPD;
use Dcarrith\LxMPD\Connection\MPDConnection as MPDConnection;
use Log;

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
			// These three calls to Log::info show the three different ways to access configs
			Log::info( 'LxMPDServiceProvider', array('host' => Config::get('lxmpd::host')));
			Log::info( 'LxMPDServiceProvider', array('port' => $app['config']->get('lxmpd::port')));
			Log::info( 'LxMPDServiceProvider', array('password' => $app['config']['lxmpd::password']));

			// Instantiate a new MPDCOnnection object using the host, port and password set in configs
			$connection = new MPDConnection( Config::get('lxmpd::host'), Config::get('lxmpd::port'), Config::get('lxmpd::password') ); 
	
			// Determine if the connection to MPD is local to the Web Server
			$connection->determineIfLocal();

			// Establish the connection
			$connection->establish();

			// Instantiate a new LxMPD object and inject the connection dependency
			return new LxMPD( $connection );
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
