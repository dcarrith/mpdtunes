<?php namespace Orchestra\Testbench\Tests;

use Dcarrith\LxMPD\LxMPD; 
use Dcarrith\LxMPD\Connection\MPDConnection;
use Illuminate\Support\Facades\Config;

class MPDIsLocalTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // uncomment to enable route filters if your package defines routes with filters
        // $this->app['router']->enableFilters();

        // create an artisan object for calling migrations
        //$artisan = $this->app->make('artisan');

        // call migrations for packages upon which our package depends, e.g. Cartalyst/Sentry
        // not necessary if your package doesn't depend on another package that requires
        // running migrations for proper installation
        /* uncomment as necessary
	$artisan->call('migrate', [
	'--database' => 'testbench',
	'--path' => '../vendor/cartalyst/sentry/src/migrations',
	]);
	*/

        // call migrations that will be part of your package, assumes your migrations are in src/migrations
        // not neccessary if your package doesn't require any migrations to be run for
        // proper installation
        /* uncomment as neccesary
	$artisan->call('migrate', [
	'--database' => 'testbench',
	'--path' => 'migrations',
	]);
	*/

        // call migrations specific to our tests, e.g. to seed the db
        /*$artisan->call('migrate', array(
            '--database' => 'testbench',
            '--path' => '../tests/migrations',
        ));*/
    }

    /**
     * Define environment setup.
     *
     * @param Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // reset base path to point to our package's src directory
        $app['path.base'] = __DIR__ . '/../src';

	
    }

    /**
     * Get package providers. At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return array(
            //'Cartalyst\Sentry\SentryServiceProvider',
            //'YourProject\YourPackage\YourPackageServiceProvider', 
 		'Dcarrith\LxMPD\LxMPDServiceProvider',
	);
    }

    /**
     * Get package aliases. In a normal app environment these would be added to
     * the 'aliases' array in the config/app.php file. If your package exposes an
     * aliased facade, you should add the alias here, along with aliases for
     * facades upon which your package depends, e.g. Cartalyst/Sentry
     *
     * @return array
     */
    protected function getPackageAliases()
    {
        return array(
            //'Sentry' => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
            //'YourPackage' => 'YourProject\YourPackage\Facades\YourPackage',
		'Config' => 'Illuminate\Support\Facades\Config',
 		'LxMPD' => 'Dcarrith\LxMPD\LxMPD',
	);
    }

    /**
     * Test that the local loopback is determined to be local
     *
     * @test
     */
    public function testLoopbackIsLocal()
    {
	// Initialize the connection variables we'll need
	$this->port = Config::get('lxmpd::port');
	$this->password = Config::get('lxmpd::password');
	
	// Test that determine is local returns true for 127.0.0.1
	$connection = new MPDConnection( "127.0.0.1", $this->port, $this->password );

	// Determine if the connection is local
	$connection->determineIfLocal();

	// Check if the connection was marked as local
	$result = $connection->local;

	// Check if the result is true
        $this->assertEquals(true, $result);	
    }

    /**
     * Test that localhost is determined to be local
     *
     * @test
     */
    public function testLocalhostIsLocal()
    {
	// Initialize the connection variables we'll need
	$this->port = Config::get('lxmpd::port');
	$this->password = Config::get('lxmpd::password');

	// Test that determine is local returns true for localhost
	$connection = new MPDConnection( "localhost", $this->port, $this->password );
	
	// Determine if the connection is local
	$connection->determineIfLocal();

	// Check if the connection was marked as local
	$result = $connection->local;

	// Check if the result is true
        $this->assertEquals(true, $result);	
    }

    /**
     * Test running migration.
     *
     * @test
     */
    public function testSameIPIsLocal()
    {
	// Initialize the connection variables we'll need
	$this->port = Config::get('lxmpd::port');
	$this->password = Config::get('lxmpd::password');

	$hostname = getHostName();
	$ip = getHostByName( $hostname );

	// Test that determine is local returns true for the ip of the server
	$connection = new MPDConnection( $ip, $this->port, $this->password );

	$connection->establish();

	// Determine if the connection is local
	$connection->determineIfLocal();

	// Check if the connection was marked as local
	$result = $connection->local;

	// Check if the result is true
        $this->assertEquals(true, $result);	
    }
}
