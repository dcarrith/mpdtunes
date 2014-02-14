<?php namespace Orchestra\Testbench\Tests;

use Dcarrith\LxMPD\LxMPD; 
use Illuminate\Support\Facades\Config;

class SetMPDPropertiesTest extends \Orchestra\Testbench\TestCase
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

	// Instantiate a new LxMPD object using the host, port and password parameters
	$this->LxMPD = new LxMPD( $this->host, $this->port, $this->password );

	// Connect to MPD with the credentials retrieved from config during setup
	$this->LxMPD->connect();
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
		'Config' => 'Illuminate\Support\Facades\Config',
 		'LxMPD' => 'Dcarrith\LxMPD\LxMPD',
	);
    }

    /**
     * Test turning on repeat
     *
     * @test
     */
    public function turnRepeatOnTest()
    {

	$repeat = 1;
	
	// Try to turn on repeat
	$this->LxMPD->repeat($repeat);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if repeat is equal to what we set it to above
        $this->assertEquals($repeat, $status['repeat']);
    }

    /**
     * Test turning off repeat
     *
     * @test
     */
    public function turnRepeatOffTest()
    {

	$repeat = 0;
	
	// Try to turn on repeat
	$this->LxMPD->repeat($repeat);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if repeat is equal to what we set it to above
        $this->assertEquals($repeat, $status['repeat']);
    }

    /**
     * Test turning on random
     *
     * @test
     */
    public function turnRandomOnTest()
    {

	$random = 1;
	
	// Try to turn on random
	$this->LxMPD->random($random);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if random is equal to what we set it to above
        $this->assertEquals($random, $status['random']);
    }


    /**
     * Test turning off random
     *
     * @test
     */
    public function turnRandomOffTest()
    {

	$random = 0;
	
	// Try to turn on random
	$this->LxMPD->random($random);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if random is equal to what we set it to above
        $this->assertEquals($random, $status['random']);
    }

    /**
     * Test turning on single
     *
     * @test
     */
    public function turnSingleOnTest()
    {

	$single = 1;
	
	// Try to turn on single
	$this->LxMPD->single($single);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if single is equal to what we set it to above
        $this->assertEquals($single, $status['single']);
    }

    /**
     * Test turning off single
     *
     * @test
     */
    public function turnSingleOffTest()
    {

	$single = 0;
	
	// Try to turn on single
	$this->LxMPD->single($single);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if single is equal to what we set it to above
        $this->assertEquals($single, $status['single']);
    }

    /**
     * Test turning on consume
     *
     * @test
     */
    public function turnConsumeOnTest()
    {

	$consume = 1;
	
	// Try to turn on consume
	$this->LxMPD->consume($consume);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if consume is equal to what we set it to above
        $this->assertEquals($consume, $status['consume']);
    }

    /**
     * Test turning consume off
     *
     * @test
     */
    public function turnConsumeOffTest()
    {

	$consume = 0;
	
	// Try to turn on consume
	$this->LxMPD->consume($consume);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if consume is equal to what we set it to above
        $this->assertEquals($consume, $status['consume']);
    }

    /**
     * Test setting the volume
     *
     * @test
     */
    /*public function setVolumeTest()
    {
	// Default it to four
	$volume = 4;
	
	// Try to set the volume
	$this->LxMPD->volume($volume);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if volume is equal to what we set it to above
        $this->assertEquals($volume, $status['volume']);

	// Increment volume
	$volume++;
	
	// Try to set the volume
	$this->LxMPD->volume($volume);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if volume is equal to what we set it to above
        $this->assertEquals($volume, $status['volume']);
    }*/

    /**
     * Test setting the crossfade
     *
     * @test
     */
    public function setCrossfadeTest()
    {
	// Default it to five
	$crossfade = 5;
	
	// Try to set the crossfade
	$this->LxMPD->crossfade($crossfade);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if crossfade is equal to what we set it to above
        $this->assertEquals($crossfade, $status['xfade']);

	// Increment crossfade
	$crossfade++;
	
	// Try to set the crossfade
	$this->LxMPD->crossfade($crossfade);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if crossfade is equal to what we set it to above
        $this->assertEquals($crossfade, $status['xfade']);
    }

    /**
     * Test setting the mixrampdb
     *
     * @test
     */
    public function setMixRampDBTest()
    {
	// Default it to six
	$mixrampdb = 6;
	
	// Try to set the mixrampdb
	$this->LxMPD->mixrampdb($mixrampdb);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if mixrampdb is equal to what we set it to above
        $this->assertEquals($mixrampdb, $status['mixrampdb']);

	// Increment mixrampdb
	$mixrampdb++;

	// Try to set the mixrampdb
	$this->LxMPD->mixrampdb($mixrampdb);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if mixrampdb is equal to what we set it to above
        $this->assertEquals($mixrampdb, $status['mixrampdb']);
    }

    /**
     * Test setting the mixrampdelay
     *
     * @test
     */
    public function setMixRampDelayTest()
    {
	// Default it to seven
	$mixrampdelay = 7;
	
	// Try to set the mixrampdelay
	$this->LxMPD->mixrampdelay($mixrampdelay);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if mixrampdelay is equal to what we set it to above
        $this->assertEquals($mixrampdelay, $status['mixrampdelay']);

	// Increment mixrampdelay
	$mixrampdelay++;
	
	// Try to set the mixrampdelay
	$this->LxMPD->mixrampdelay($mixrampdelay);

	// Retrieve the stats so we can check the values
	$status = $this->LxMPD->status();

	// Check if mixrampdelay is equal to what we set it to above
        $this->assertEquals($mixrampdelay, $status['mixrampdelay']);
    }
}
