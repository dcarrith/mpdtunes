<?php

// File: app/models/services/usersconfigs/validation.php

namespace Services\UsersConfigs;

use Services\Validation as ValidationService;

use Auth;
use Config;
use Langurator;
use Request;
use UsersConfig;
use Validator;

class Validation extends ValidationService {

	/**
	* Create a new validation service instance.
	*
	* @param  array  $input
	* @return void	
	*/
	public function __construct($input)
	{
		parent::__construct($input);
	
		// We will want to make sure that the user entered the correct recaptcha value
		// This can be used with this validation rule: upandrunning:'.$input['mpd_host'],
		// The validation message is as follows: 'mpd_host.upandrunning' => 'Can\'t connect to :attribute',
		Validator::extend('upandrunning', function($attribute, $value, $parameters) {

			$ip = $value;
			$port = $parameters[0];
	
			if ( ip2long( $ip ) !== false ) {

				// We must be validating mpd_host then
			
			} else { 
	
				if (( intval($value) > 0 ) && ( intval($value) <= 65535 )) {
	
					$ip = $parameters[0];
					$port = intval($value);
				}
			}

			return testIpAndPort( $ip, $port );
		});

                $this->rules = array(	'mpd_host' 			=> 'required|ip',
					'mpd_port' 			=> 'required|integer|between:1,65535',
					'mpd_stream_port' 		=> 'required|integer|between:1,65535',
					'mpd_password' 			=> 'required|min:8|confirmed',
					'mpd_password_confirmation' 	=> 'required',
					'mpd_dir' 			=> 'required',
					'music_dir' 			=> 'required',
					'queue_dir' 			=> 'required',
					'art_dir' 			=> 'required'
                );

                $this->messages = array(	'mpd_host.required' 			=> 'The :attribute is required',
						'mpd_host.ip'				=> 'The :attribute must be a valid IP address',
						'mpd_port.required' 			=> 'The :attribute is required',
						'mpd_port.integer'			=> 'The :attribute must be an integer',
						'mpd_port.between'			=> 'The :attribute must be between 1 and 65535',
						'mpd_stream_port.required' 		=> 'The :attribute is required',
						'mpd_stream_port.integer'		=> 'The :attribute must be an integer',
						'mpd_stream_port.between'		=> 'The :attribute must be between 1 and 65535',
						'mpd_password.required' 		=> 'The :attribute is required',
						'mpd_password.min' 			=> 'The :attribute must be at least 8 characters',
						'mpd_password.confirmed' 		=> 'The passwords must match',
						'mpd_password_confirmation.required' 	=> 'The MPD password must be confirmed',
						'mpd_dir.required' 			=> 'The :attribute is required',
						'music_dir.required' 			=> 'The :attribute is required',
						'queue_dir.required' 			=> 'The :attribute is required',
						'art_dir.required' 			=> 'The :attribute is required'
                );
	}

	/**
	* Validate a user's configs before saving them
	*
	* @throws ValidateException
	* @return void
	*/
	public function save() {
	
		$this->validate();
	}
}

/*
 * @return boolean
 * @param  string $link
 * @desc This function tries to get an HTTP Response code 200
 */
function testIpAndPort( $ip, $port ) {

	$socket = @fsockopen( $ip, $port, $errno, $errstr, 30 );

	if (!$socket) {
        
		return false;
        
	} else {
            
		return true;
	}
}
