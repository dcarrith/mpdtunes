<?php namespace Dcarrith\CIFirePHP;

use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;

class CIFirePHP {

        private static $_dummy;
        private static $_logger;

	public static $name;
	public static $environment;

        function __construct() { }

	public static function setName($name) { 
		self::$name = $name;
	}

	public static function setEnvironment($environment) {
	
		if($environment == "development") {
			self::$_dummy = false;
		} else {
			self::$_dummy = true;
		}

		self::$environment = $environment;
	}

	public static function createLogger() {

                self::$_logger = new Logger(self::$name);
                self::$_logger->pushHandler(new FirePHPHandler()); 
	}

	public static function log($variable, $label) {
				
		if(!isset(self::$_logger)) {

			// trigger a PHP notice that someone tried to use the log function before createLogger was called
			trigger_error("LoggerNotInitialized", E_USER_NOTICE);
        				
			// trigger_error does not stop the function so we must return control here
			return;
		}

		if(!isset(self::$environment)) {
                        
			// trigger a PHP notice that someone tried to use the log function before setting the environment
                        trigger_error("EnvironmentNotInitialized", E_USER_NOTICE);
                                        
                        // trigger_error does not stop the function so we must return control here
                        return;
		}

		if(!self::$_dummy) {

                        if (!is_array($variable)) {
                                $variable = array($variable);
                        }

                        self::$_logger->addInfo($label, $variable);
		}
	}
}

?>
