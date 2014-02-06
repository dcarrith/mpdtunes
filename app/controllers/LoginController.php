<?php

use Services\Users\Validation as UserValidationService;

class LoginController extends BaseController {

        function __construct() {

                parent::__construct();

                /*$this->data['logged_in'] = true;
                $this->data['user_id'] = Auth::user()->id;
                $this->data['json_playlist'] = "";
                $this->data['current_track_position'] = 0;*/
        }

	public function getLogin() { 

                if (Auth::check()) {
                        return Redirect::to('home');
                        exit();
                }

		// Get and merge the theme defaults into the main data array
		$this->data = array_merge($this->data, Configurator::getDefaults("theme"));

		// Get and merge the theme defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("app"));		

		// Get and merge the theme defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("analytics")); 

		// Get and merge all the words we need for the login controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("login"));

                $email = Request::old('username');
                $password = Request::old('password');

                $this->firephp->log($email, "email");
                $this->firephp->log($password, "password");

		$this->data['username'] = $email;
		$this->data['password'] = $password;
	
		//$this->firephp->log( $this->data, "this->data");

		// Return the login view 
		return View::make('login', $this->data);
	}

	public function postLogin() {

		// Add the csrf before filter to guard against cross-site request forgery
		$this->beforeFilter('csrf');

		try {
			$validation = new UserValidationService(Input::all());

			$validation->login();

		} catch (ValidateException $errors) {
			
			return Redirect::to('login')->withErrors($errors->get())->withInput();
		}
 
		$credentials = array('email' =>Input::get('username'), 'password' =>Input::get('password'));

		if (Auth::attempt($credentials)) {

               		$this->data = array_merge($this->data, Configurator::getDefaults('users', $this->firephp));

			// Spawn an instance of MPD so we know it's running
			$path_to_mpd_binary = Config::get('defaults.default_path_to_mpd_binary');
			
			$output = array();
			$resultCode = array();
			// exec(escapeshellcmd('rm -f -v').' '.escapeshellarg($symbolic_link).' 2>&1', $output, $resultCode);
	
			// Now spawn a new instance of mpd
			$execResult = exec($path_to_mpd_binary.' '.ltrim($this->data['mpd_dir'], "/").'mpd.conf 2>&1', $output, $resultCode);
	
			$this->firephp->log($output, "output from trying to start mpd");
			$this->firephp->log($resultCode, "resultCode from trying to start mpd");

			//exit();

			return Redirect::to('home');
			
		} else {
			
			return Redirect::to('login')->with('login_errors', true)->withInput();
		}
	}
}
