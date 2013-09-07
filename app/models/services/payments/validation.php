<?php

// File: app/models/services/payments/validation.php

namespace Services\Payments;

use Services\Validation as ValidationService;

use Auth;
use Config;
use Langurator;
use Request;
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

                $this->rules = array(	'sandbox_master_account' 	=> 'required',
					'sandbox_api_username' 		=> 'required',
					'sandbox_api_password' 		=> 'required',
					'sandbox_api_signature' 	=> 'required',
					'paypal_master_account' 	=> 'required|email',
					'paypal_api_username' 		=> 'required',
					'paypal_api_password' 		=> 'required',
					'paypal_api_signature' 		=> 'required'	);

                $this->messages = array(	'sandbox_master_account.required' 	=> 'The :attribute is required',
						'sandbox_api_username.required' 	=> 'The :attribute is required',
						'sandbox_api_username.email' 		=> 'The :attribute must be a valid email',
						'sandbox_api_password.required' 	=> 'The :attribute is required',  
						'sandbox_api_signature.required' 	=> 'The :attribute is required',
						'paypal_master_account.required' 	=> 'The :attribute is required',
						'paypal_api_username.required' 		=> 'The :attribute is required',
						'paypal_api_username.email' 		=> 'The :attribute must be a valid email',
						'paypal_api_password.required' 		=> 'The :attribute is required',
						'paypal_api_signature.required' 	=> 'The :attribute is required'	);
	}

	/**
	* Validate the payment configurations before saving
	*
	* @throws ValidateException
	* @return void
	*/
	public function save() {
			
		$this->validate();
	}
}
