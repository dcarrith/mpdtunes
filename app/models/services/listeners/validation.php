<?php

// File: app/models/services/listeners/validation.php

namespace Services\Listeners;

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
		Validator::extend('nameisunique', function($attribute, $value, $parameters) {

			$newName = $value;
			$radioStationName = $parameters[0];
			
			$radioStation = RadioStation::where('name', '=', $radioStationName)->first();
			$count = Listener::where('radiostation_id', '=', $radioStation->id)->where('connected', '=', 1)->where('name', '=', $newName)->count();
			$this->validationErrors->add('name', 'This name has already been taken :(');
			
			return $count === 0 ? true : false;
		});

                $this->rules = array(	'session_id' => 'required|alpha_num',
					'name' => 'alpha_dash|max:20|min:2',
					'connected' => 'required|in:0,1' 
		);

                $this->messages = array(	'session_id.required' 			=> 'The :attribute is required',
						'session_id.alpha_num'			=> 'The :attribute must be alpha-numeric',
						'name.alpha_dash'			=> 'The :attribute must be in alpha-dash format',
						'name.nameisunique'			=> 'The :attribute must be unique among all listeners'
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
