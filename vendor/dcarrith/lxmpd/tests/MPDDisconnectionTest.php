<?php namespace Orchestra\Testbench\Tests;

use Dcarrith\LxMPD\LxMPD; 
use Illuminate\Support\Facades\Config;

class MPDDisconnectionTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

	// Initialize the connection variables we'll need
	$this->host = Config::get('lxmpd::host');
	$this->port = Config::get('lxmpd::port');
	$this->password = Config::get('lxmpd::password');

	$this->LxMPD = new LxMPD( $this->host, $this->port, $this->password );

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
     * Test running migration.
     *
     * @test
     */
    public function testConnectingToMPD()
    {

	// Try to connect to MPD with the credentials retrieved from config during setup
	$this->LxMPD->connect();

	$result = $this->LxMPD->disconnect();

	// Check if the result is true
        $this->assertEquals(true, $result);	
    }
}
