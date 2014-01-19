<?php

// Laravel 4.0.x
//use Illuminate\Routing\Controllers\Controller;
// Laravel 4.1.x
use Illuminate\Routing\Controller;

class BaseController extends Controller {

        protected $data = array();
        protected $firephp;
	protected $zipper;

        //public $restful = TRUE;

        public function __construct() {

		//Cache::flush();

		// Get and merge the environment config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('environment'));

		if( !$this->data['profiling'] )  {

			//Config::set('profiler::profiler', FALSE);
			
			// Turn off debugbar by setting enabled to false
			Config::set('laravel-debugbar::config.enabled', false);	
		}

		$this->data['language'] = Config::get('app.locale');

		$recaptcha_translations = Langurator::getLocalizedWords("recaptcha");
	
		$recaptcha_translations_array = array();
		$recaptcha_translations_array['custom_translations'] = array();
	
		foreach ($recaptcha_translations as $key=>$value) {

			$recaptcha_translations_array[str_replace("_i18n", "", $key)] = $value;
		}

		$this->data['recaptcha_translations'] = json_encode($recaptcha_translations_array);                

                // Initialize the CodeIgniter FirePHP interface to the Monolog package
                $this->firephp = new CIFirePHP();
                $this->firephp->setName("CIFirePHP");
                $this->firephp->setEnvironment($this->data["environment"]);
                $this->firephp->createLogger();

		$this->firephp->log($this->data['recaptcha_translations'], "recaptcha_translations");

		// Initialize the CodeIgniter Zip class for compression
		$this->zipper = new CIZip();

                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("site"));

                // Get and merge the lazyloader config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("lazyloader", $this->firephp));

                // Get and merge the recaptcha config defaults into the main data array 
                $this->data = array_merge($this->data, Configurator::getDefaults('recaptcha'));

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("base"));

		$this->data['section'] = Request::segment(1);

                // We need to check if mpdtunes has been set up properly first (otherwise, there would be no database connection)
                /*if (!file_exists('includes/xml/config.xml')) {
                        
                        // if the database connection (stored encrypted in the config.xml) has not been set up, then redirect to setup
                        Redirect::secure('setup');
                }*/
        }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	/*protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}*/

}
