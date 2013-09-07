<?php

// File: app/models/services/users/validation.php

namespace Services\Users;

use Services\Validation as ValidationService;

use Auth;
use Config;
use Request;
use User;
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

		$this->firephp->log($input, "input");
	
		// We will want to make sure that the user entered the correct recaptcha value
		Validator::extend('recaptchered', function($attribute, $value, $parameters) {

			require_once('includes/php/modules/recaptcha/recaptchalib.php');

			$recaptcha_private_key 		= Config::get('recaptcha.recaptcha_private_key');
			$remote_address 		= Request::getClientIp();
			$recaptcha_challenge_field 	= Request::get('recaptcha_challenge_field');
			$recaptcha_response_field	= Request::get('recaptcha_response_field');

			// Recaptcha Looks for the POST to confirm 
			$resp = recaptcha_check_answer($recaptcha_private_key, $remote_address, $recaptcha_challenge_field, $recaptcha_response_field);

			if ($resp->is_valid) {
	
				$this->firephp->log($resp->is_valid, "resp->is_valid");	
				$this->firephp->log($recaptcha_private_key, "recaptcha_private_key");
				$this->firephp->log($remote_address, "remote_ip_address");
				$this->firephp->log($recaptcha_challenge_field, "recaptcha_challenge_field");
				$this->firephp->log($recaptcha_response_field, "recaptcha_response_field");

				return true;

			}
					
			$this->firephp->log($resp->is_valid, "resp->is_valid");	
			$this->firephp->log($recaptcha_private_key, "recaptcha_private_key");
			$this->firephp->log($remote_address, "remote_ip_address");
			$this->firephp->log($recaptcha_challenge_field, "recaptcha_challenge_field");
			$this->firephp->log($recaptcha_response_field, "recaptcha_response_field");

			return false;
		});

                Validator::extend('isunique', function($attribute, $value, $parameters) {

			$this->firephp->log("checking if entered username isunique", "message");
			$this->firephp->log($value, "value");

			$users = array();

			if (isset($parameters)) {

				$this->firephp->log($parameters, "parameters");

				$users = User::where('email', '=', $value)->where('id', '!=', $parameters[0])->take(1)->get(array('id'))->toArray();
			
			} else {
                        
				$users = User::where('email', '=', $value)->take(1)->get(array('id'))->toArray();
			}

                        $userExists = (count($users) ? true : false);

                        if (!$userExists) {

				$this->firephp->log($userExists, "userExists");
                                return true;
			}

                        return false;
                });

		Validator::extend('isknown', function($attribute, $value, $parameters) {

			$users = User::where('email', '=', $value)->take(1)->get(array('id'))->toArray();

			$userExists = (count($users) ? true : false);

			if ($userExists) {

				return true;
			}

			return false;
		});

                Validator::extend('isactive', function($attribute, $value, $parameters) {

                	$users = User::where('email', '=', $value)->take(1)->get(array('active'))->toArray();

                        $userExists = (count($users) ? true : false);

                        if ($userExists) {

                        	$user = $users[0];

                                if ($user['active']) {

					//var_dump($user);
					//exit();
                                	return true;
				}
			}

			return false;
		});

		$user_id = 0;
	
		if (null !== Auth::user()) {

			$user_id = Auth::user()->id;
		}

		$this->firephp->log($user_id, "user_id");

                $this->rules = array(		'first_name' 				=> 'required',
						'last_name' 				=> 'required',
                        			'username' 				=> 'required|email|isunique:'.$user_id	);

                $this->messages = array(	'first_name.required' 			=> 'Enter your first name',
						'last_name.required' 			=> 'Enter your last name',
                        			'username.required' 			=> 'Enter your email to be your username',
                        			'username.email' 			=> 'Username should be your email address'	);

                if (isset($this->input['password']) && $this->input['password'] != '') {
		
			$this->rules = array_merge($this->rules, array(		'password' => 'required|confirmed|min:8',
										'password_confirmation' => 'required|min:8')	);

			$this->messages = array_merge($this->messages, array(	'password.required' => 'Enter a password',
 	                      							'password.confirmed' => 'The passwords must match',
										'password_confirmation.required' => 'Enter the password again to confirm' )	);
                } else {

			if ($user_id == 0) {
	
				$this->rules = array_merge($this->rules, array(		'password' => 'required|confirmed|min:8'	));

				$this->messages = array_merge($this->messages, array(	'password.required' => 'Enter a password',
                	        							'password.confirmed' => 'The passwords must match'	));
			}
		}

		if ($user_id == 0) {
	        
		        if (isset($this->input['password_confirmation']) && $this->input['password_confirmation'] != '') {

				$this->rules = array_merge($this->rules, array(		'password' => 'required|confirmed|min:8',
											'password_confirmation' => 'required|min:8')	);

				$this->messages = array_merge($this->messages, array(	'password.required' => 'Enter a password',
                        								'password.confirmed' => 'The passwords must match',
											'password_confirmation.required' => 'Enter the password again to confirm' )	);

			} else {

				// Since password_confirmation is blank, we need to remove the confirmed rule from the password field
				$this->rules['password'] = 'required|min:8';

				$this->rules = array_merge($this->rules, array(		'password_confirmation' => 'required|min:8'	));

				// Since password_confirmation is blank, we need to remove the message for password.confirmed
				unset($this->messages['password.confirmed']);

				$this->messages = array_merge($this->messages, array(	'password_confirmation.required' => 'Enter the password again to confirm'	));
			}
		}
	}

	/**
	* Validate a user registration before saving it.
	*
	* @throws ValidateException
	* @return void
	*/
	public function register() {

		// The registration form also needs to validate the recaptcha form
                $this->rules['recaptcha_response_field'] = 'required|recaptchered';

                $this->messages['recaptcha_response_field.required'] = 'Enter the recapthca value';
	        $this->messages['recaptcha_response_field.recaptchered'] = 'The recaptcha value was incorrect';

		$this->validate();
	}

	/**
	* Validate a user logging in
	*
	* @throws ValidateException
	* @return void
	*/
	public function login() {
        	
		$this->rules = array(	'username' => 'required|email|isknown|isactive',
					'password' => 'required|min:8'	); 
       
		$this->messages = array(	'username.required' 	=> 'Enter a :attribute', 
						'username.email' 	=> 'The :attribute is your email address',
						'username.isknown' 	=> 'That :attribute is not in the system',
						'username.isactive' 	=> 'That account is inactive',
    						'password.required' 	=> 'Enter a :attribute',
						'password.min'		=> 'That :attribute is too short'	);

		$this->validate();
	}

	/**
	* Validate the admin user account form before saving it.
	*
	* @throws ValidateException
	* @return void
	*/
	public function account() {
		
		$this->validate();
	}
}


 
/*
		// This goes in the validation function that does the validating
		Validator::resolver(function($translator, $data, $rules, $messages) {

			return new CustomValidator($translator, $data, $rules, $messages);
		});


class CustomValidator extends Illuminate\Validation\Validator {
	
	public function validateRecaptchered($attribute, $value, $parameters) {

		require_once('includes/php/modules/recaptcha/recaptchalib.php');

                // Initialize the CodeIgniter FirePHP interface to the Monolog package
                $this->firephp = new CIFirePHP();
                $this->firephp->setName("CIFirePHP");
                $this->firephp->setEnvironment(Config::get("server.environment"));
                $this->firephp->createLogger();

		$recaptcha_private_key 		= Config::get('recaptcha.recaptcha_private_key');
		$remote_address 		= Request::getClientIp();
		$recaptcha_challenge_field 	= Request::get('recaptcha_challenge_field');
		$recaptcha_response_field	= Request::get('recaptcha_response_field');

		// Recaptcha Looks for the POST to confirm 
		$resp = recaptcha_check_answer($recaptcha_private_key, $remote_address, $recaptcha_challenge_field, $recaptcha_response_field);
	
		// If if the user's authentication is valid, echo "success" to the Ajax
		if ($resp->is_valid) {
	
			$this->firephp->log($resp->is_valid, "resp->is_valid");	
			$this->firephp->log($recaptcha_private_key, "recaptcha_private_key");
			$this->firephp->log($remote_address, "remote_ip_address");
			$this->firephp->log($recaptcha_challenge_field, "recaptcha_challenge_field");
			$this->firephp->log($recaptcha_response_field, "recaptcha_response_field");
		
			return true;
		}
					
		$this->firephp->log($resp->is_valid, "resp->is_valid");	
		$this->firephp->log($recaptcha_private_key, "recaptcha_private_key");
		$this->firephp->log($remote_address, "remote_ip_address");
		$this->firephp->log($recaptcha_challenge_field, "recaptcha_challenge_field");
		$this->firephp->log($recaptcha_response_field, "recaptcha_response_field");

		return false;
	}
}
*/

?>
