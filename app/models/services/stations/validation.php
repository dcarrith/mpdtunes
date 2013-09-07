<?php

// File: app/models/services/stations/validation.php

namespace Services\Stations;

use Services\Validation as ValidationService;

use Auth;
use Config;
use Langurator;
use Request;
use Station;
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
		if (isset($input['station_url'])) {
			
			$input['station_url'] = (( !strpos($input['station_url'], "://")) ? "http://".$input['station_url'] : $input['station_url']);
		}

		parent::__construct($input);

                $this->rules = array(		'station_url' 			=> 'required|url|max:255',
						'station_name' 			=> 'required|max:64',
						'station_description' 		=> 'required|max:128'	);

		$this->messages = array(	'station_url.required' 		=> 'Enter the URL of the stream', 
						'station_url.url' 		=> 'The :attribute must be a valid URL',
						'station_url.max' 		=> 'The :attribute is limited to 255 characters',
						'station_name.required' 	=> 'Enter a name for the station',
    						'station_name.max'	 	=> 'The :attribute is limited to 64 characters',
						'station_description.required'	=> 'Enter a description of the station',
						'station_description.max'	=> 'The :attribute is limited to 128 characters'	);
	}

	/**
	* Validate a station before creating it.
	*
	* @throws ValidateException
	* @return void
	*/
	public function add() {

                //$this->rules['station_url'] .= '|unique:stations,url';

		//$this->messages['station_url.unique'] = 'That URL is already in the system';
			
		$this->validate();
	}

	/**
	* Validate a station before saving it.
	*
	* @throws ValidateException
	* @return void
	*/
	public function edit() {
	
		$this->validate();
	}
}
