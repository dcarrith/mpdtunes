<?php 

class AccountController extends MPDTunesController {

	function __construct() {

		parent::__construct();

		// If this is the demo user, then redirect to home
		if (Session::get('user_id') == 3) {

			return Redirect::to('home');	
		}
		
		// home doesn't need to be ssl encrypted
		$home_link = $this->data['base_protocol'] . $this->data['base_domain'] . "/";

		$this->firephp->log($this->data['base_protocol'], "base_protocol");
		$this->firephp->log($this->data['base_domain'], "base_domain");
		$this->firephp->log($home_link, "home_link");

		// we have to override the default so that it links back to home properly
		$this->data['home_link_data_ajax']	= "false";
		$this->data['home_link']		= $home_link;
	
		// Get and merge all the words we need for the Register controller into the main data array     
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("account"));
	}

	public function getIndex() {

                $first_name		= Request::old('first_name');
                $last_name 		= Request::old('last_name');
                $email 			= Request::old('username');
                $password       	= Request::old('password');
		$password_confirmation 	= Request::old('password_confirmation');

                $this->firephp->log($user_id, "user id");
                $this->firephp->log($first_name, "first name");
                $this->firephp->log($last_name, "last name");
                $this->firephp->log($email, "email");
                $this->firephp->log($password, "password");
		$this->firephp->log($password_confirmation, "password_confirmation");

                if (Session::has('success')) {

                        $this->firephp->log(Session::get('success'), 'successful?');

                        if (Session::get('success')) {

                                $this->data['saved_successfully'] = true;

				$user = User::find(Auth::user()->id);
                                $this->firephp->log($user, 'user');
                                $this->firephp->log($user->first_name, 'first_name');
                                $this->firephp->log($user->last_name, 'last_name');
                                $this->firephp->log($user->email, 'username');
                                $this->firephp->log($user->password, 'password');
                          
                        } else {

                                $this->data['error_occurred_while_saving'] = true;
                        }

                        // Manually remove the success value from the session
                        Session::forget('success');
                
		} else {

			$user = User::find(Auth::user()->id);
			
			$this->data['first_name'] = $user->first_name;
			$this->data['last_name'] = $user->last_name;
			$this->data['username'] = $user->email;
		
			$this->firephp->log($user, 'user');
			$this->firephp->log($user->first_name, 'first_name');
			$this->firephp->log($user->last_name, 'last_name');
			$this->firephp->log($user->email, 'username');
		}

                return View::make('account', $this->data);
	}

	public function postIndex() {

		$user_id 		= Auth::user()->id;
		$first_name     	= Request::get('first_name');
		$last_name      	= Request::get('last_name');
		$email          	= Request::get('username');
		$password       	= Request::get('password');
		$password_confirmation 	= Request::get('password_confirmation');

		$this->firephp->log($user_id, "user id");
		$this->firephp->log($first_name, "first name");
		$this->firephp->log($last_name, "last name");
		$this->firephp->log($email, "username");
		$this->firephp->log($password, "password");
		$this->firephp->log($password_confirmation, "password_confirmation");

		//$this->load->library('user_agent');
		//$this->firephp->log($this->agent->referrer(), "this->agent->referrer()");

		// Add the csrf before filter to guard against cross-site request forgery
		$this->beforeFilter('csrf');

                Validator::extend('awesome', function($attribute, $value, $parameters) {
                        return $value == 'awesome';
                });

                Validator::extend('isknown', function($attribute, $value, $parameters) {

                        $users = User::where('email', '=', $value)->take(1)->get(array('id'))->toArray();

                        $userExists = (count($users) ? true : false);

                        if ($userExists) {

                                return true;
                        }

                        return false;
                });

                $rules = array(
			'first_name' => 'required',
			'last_name' => 'required',
                        'username' => 'required|email|isknown'
                );

                $messages = array(
			'first_name_required' => 'Enter your First Name',
			'last_name_required' => 'Enter your Last Name',
                        'username_required' => 'Enter a username',
                        'username_email' => 'Username is your email address'
                        //'validation.awesome' => 'Username must be awesome'
                );

                if (isset($password) && $password != '') {
	
			$rules = array_merge($rules, array('password' => 'required|confirmed|min:8'));//|iscorrect:'.Input::get('username'));
			$messages = array_merge($messages, array(	'password_required' => 'Enter a password',
                        						'password_confirm' => 'The passwords must match')	);
                }

                //var_dump(Input::get());
                //exit();

                $validation = Validator::make(Input::get(), $rules, $messages);

                if( $validation->fails() ) {

                        //Send the $validation object to the redirected page
                        return Redirect::to('account')->withErrors($validation)->withInput();
                }

                if( $validation->passes() ) {

                        $this->data['saved_successfully'] = true;

                        $this->data['saved_successfully_i18n'] = $this->data['saved_successfully'];
                        
			$user = User::find($user_id);
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->email = $email;
			$user->password = Hash::make($password);
			
			$success = $user->save();

                        if ($success) {

	                        Session::flash('success', true);

        	                $this->firephp->log($success, "success");

	                        $this->data['saved_successfully'] = true;

				return Redirect::to('account')->withInput();

                        } else {

                                //$this->data['error_name_i18n']          = $this->lang->line('save_user_account_error_name');
                                //$this->data['error_description_i18n']   = $this->lang->line('save_user_account_error_description');

                                Session::flash('success', false);

                                $this->firephp->log($success, "success");

                                return Redirect::to('account')->withInput();
                        }
		}
	}
}
