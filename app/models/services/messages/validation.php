<?php

// File: app/models/services/messages/validation.php

namespace Services\Messages;

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

                $this->rules = array('content' => 'required|max:1500|min:1');

                $this->messages = array();
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
