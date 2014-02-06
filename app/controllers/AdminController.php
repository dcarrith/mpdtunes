<?php 

use Services\Users\Validation as UserValidationService;
use Services\UsersConfigs\Validation as UsersConfigsValidationService;
use Services\Payments\Validation as PaymentsValidationService;

class AdminController extends MPDTunesController {
	
	var $paypal_configs = array();

	function __construct() {

		parent::__construct();

		// home doesn't need to be ssl encrypted
		$home_link = $this->data['base_protocol'] . $this->data['base_domain'] . "/";

		// we have to override the default so that it links back to home properly
		$this->data['home_link_data_ajax']	= "false";
		$this->data['home_link']		= $home_link;
	
		// Get and merge all the words we need for the Register controller into the main data array     
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("admin"));
	
		// Get and merge all the words we need for the Register controller into the main data array     
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("account"));
	}

	public function index() {

                /*if ((!$this->session->userdata('logged_in')) || ($this->session->userdata('role_level') != '99'))  {

                        //redirect('home', 'refresh');
                        redirect('/', 'location', 301);
                }*/

		$this->data['site_title'] = Config::get('defaults.base_site_title') . " - Admin";

		return View::make('admin', $this->data);
	}

	public function getAccount() {

		$user_id 		= Auth::user()->id;
		$first_name		= Input::old('first_name');
                $last_name 		= Input::old('last_name');
                $email 			= Input::old('username');
                $password       	= Input::old('password');
		$password_confirmation 	= Input::old('password_confirmation');

		$this->data['user_id']	= $user_id;
		$this->data['first_name'] = $first_name;
		$this->data['last_name'] = $last_name;
		$this->data['username'] = $email;

		$this->data['password'] = $password;
		$this->data['password_confirmation'] = $password_confirmation;
	
		$this->data['saved_successfully'] = '';

                if (Session::has('success')) {

                        if (Session::get('success')) {

                                $this->data['saved_successfully'] = true;

				$user = User::find($user_id);

                        } else {

                                $this->data['error_occurred_while_saving'] = true;
                        }

                        // Manually remove the success value from the session
                        Session::forget('success');
                
		} else {

			$user = User::find($user_id);
			
			$this->data['first_name'] = $user->first_name;
			$this->data['last_name'] = $user->last_name;
			$this->data['username'] = $user->email;
	
			$this->data['password'] = $password;
			$this->data['password_confirmation'] = $password_confirmation;	
		}

                return View::make('account', $this->data);
	}

	public function postAccount() {

		$user_id 		= Auth::user()->id;
		$first_name     	= Request::get('first_name');
		$last_name      	= Request::get('last_name');
		$email          	= Request::get('username');
		$password       	= Request::get('password');
		$password_confirmation 	= Request::get('password_confirmation');
		
		// Add the csrf before filter to guard against cross-site request forgery
		$this->beforeFilter('csrf');
                
		try {
			$validate = new UserValidationService(Input::all());

			$validate->account();

		} catch (ValidateException $errors) {
			
			return Redirect::to('admin/account')->withErrors($errors->get())->withInput();
		}

		$user = User::find($user_id);
		$user->first_name = $first_name;
		$user->last_name = $last_name;
		$user->email = $email;
			
		if (isset($password) && ($password != '')) {
			$user->password = Hash::make($password);
		}			

		$success = $user->save();

		if ($success) {

			Session::flash('success', true);

			$this->data['saved_successfully'] = true;

			return Redirect::to('admin/account')->withInput();
		
		} else {

			Session::flash('success', false);

			return Redirect::to('admin/account')->withInput();
		}
	}

	public function users() {

		$this->data['users'] = User::all();

		return View::make('users', $this->data);
	}
	
	public function getUser() {

		// Default this to null so we can make sure it gets set to something
		$user_account_active_on_off = null; 

		// Get the user_id from the URL
		$user_id = Request::segment(4);
		$this->data['user_id'] = $user_id;

		$user = User::find($user_id);

		$this->data['user'] = $user;

		$usersConfigs = $user->usersConfig->config();

		// First use the value from the active field of the user's database record to detetermine on or off
		$user_account_active_on_off = (($user->active) ? 'on' : 'off');

		$this->data['saved_successfully'] = '';

		if (Session::get('success')) {

			$this->data['saved_successfully'] = true;
                }

		$this->data['mpd_host']                 = Input::old('mpd_host', $usersConfigs['mpd']['mpd_host']);
		$this->data['mpd_port']                 = Input::old('mpd_port', $usersConfigs['mpd']['mpd_port']);
                $this->data['mpd_stream_port']          = Input::old('mpd_stream_port', $usersConfigs['mpd']['mpd_stream_port']);
                $this->data['mpd_password']             = Input::old('mpd_password', $usersConfigs['mpd']['mpd_password']);
                $this->data['mpd_password_confirmation']= Input::old('mpd_password_confirmation', $usersConfigs['mpd']['mpd_password']);
                $this->data['mpd_dir']                  = Input::old('mpd_dir', $usersConfigs['mpd']['mpd_dir']);
                $this->data['music_dir']                = Input::old('music_dir', $usersConfigs['music']['music_dir']);
                $this->data['queue_dir']                = Input::old('queue_dir', $usersConfigs['music']['queue_dir']);
                $this->data['art_dir']                  = Input::old('art_dir', $usersConfigs['music']['art_dir']);

		// If the old input value for user_account_active is set, then use it, otherwise use the value retrieved above
		$user_account_active_on_off = Input::old('user_account_active', $user_account_active_on_off);

		if (!isset($user_account_active_on_off)) {

			$user_account_active_on_off = "off";
		}

                // We need to set up the options to pass to the Form::input for the user account active checkbox
                if ($user_account_active_on_off == "on") {

                        // We will just array_merge this option into the other options being set in the view
                        $this->data['user_account_active_input_options'] = array('checked'=>'checked', 'value'=>'on');

			$this->data['user_account_active'] = 'on';

                } else {

                        // Just setting checked to blank wasn't working
                        $this->data['user_account_active_input_options'] = array('value'=>'off');

			$this->data['user_account_active'] = 'off';
                }
						
		return View::make('user', $this->data);
	}

        public function postUser() {

		// Use the old posted variables instead of querying the database        
		$posted_user_id				= Request::get('user_id');
		$posted_mpd_host			= Request::get('mpd_host');
		$posted_mpd_port 			= Request::get('mpd_port');
		$posted_mpd_stream_port 		= Request::get('mpd_stream_port');
		$posted_mpd_password 			= Request::get('mpd_password');
		$posted_mpd_password_confirmation 	= Request::get('mpd_password_confirmation');
		$posted_mpd_dir 			= Request::get('mpd_dir');
		$posted_music_dir 			= Request::get('music_dir');
		$posted_queue_dir 			= Request::get('queue_dir');
		$posted_art_dir 			= Request::get('art_dir');
		$posted_user_account_active 		= Request::get('user_account_active');

                $user_account_active = (($posted_user_account_active == "on") ? 1 : 0);

                // Add the csrf before filter to guard against cross-site request forgery
                $this->beforeFilter('csrf');

		try {

			$validate = new UsersConfigsValidationService(Input::all());

			$validate->save();

		} catch (ValidateException $errors) {
	
                        //Send the $validation object to the redirected page along with inputs
                        return Redirect::to('/admin/edit/user/'.$posted_user_id)->withErrors($errors->get())->withInput();
		}

		$user = User::find($posted_user_id);
		$user->active = $user_account_active;
		$success = $user->save();

		if ($success) {

			$user_configs = array();
			$user_configs['mpd']['mpd_host']	= $posted_mpd_host;
			$user_configs['mpd']['mpd_port'] 	= $posted_mpd_port;
			$user_configs['mpd']['mpd_stream_port']	= $posted_mpd_stream_port;
			$user_configs['mpd']['mpd_password'] 	= $posted_mpd_password;
			$user_configs['mpd']['mpd_dir']		= $posted_mpd_dir;

			$user_configs['music']['music_dir'] 	= $posted_music_dir;
			$user_configs['music']['queue_dir'] 	= $posted_queue_dir;
			$user_configs['music']['art_dir'] 	= $posted_art_dir;

			// Retrieve the UsersConfig object associated with the posted_user_id
			$usersConfig = UsersConfig::find($posted_user_id);
                        	
			// Serialize and then encrypt the users config	
			$usersConfig->config = Crypt::encrypt( serialize( $user_configs ));
	
			// Save the updated usersConfig object
			$success = $usersConfig->save();

			if ( $success ) {

				Session::flash('success', true);

				return Redirect::to('/admin/edit/user/'.$posted_user_id)->withInput();
			}
		}

		return Redirect::to('/admin/edit/user/'.$posted_user_id)->withInput();
	}

        public function getPayments() {

                // Get and merge all the words we need for the Admin controller's getPayments action into the main data array     
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("payments"));

                // Default this to null so we can make sure it gets set to something
		$sandbox_enabled_on_off = null;

                $user_id = Auth::user()->id;
                $this->data['user_id'] = $user_id;
                $this->firephp->log($user_id, "user_id");

 		$this->data['saved_successfully'] = '';               

		$user = User::find($user_id);
		$this->data['user'] = $user;

		$paypalConfig = PaypalConfig::find($user_id);
		$this->firephp->log($paypalConfig, 'paypalConfig');
               
		if (Session::get('success')) {

			$this->data['saved_successfully'] = true;
		}

		$this->firephp->log($paypalConfig, "paypalConfig");

		if ($paypalConfig) {

        		$paypalConfigs = $paypalConfig->config();
			$this->firephp->log($paypalConfigs, "paypalConfigs");

                        $sandboxConfigs = $paypalConfigs['sandbox'];
                        $paypalConfigs = $paypalConfigs['paypal'];

			$this->firephp->log($sandboxConfigs, "sandboxConfigs");
        		$this->firephp->log($paypalConfigs, "paypalConfigs");
			$this->firephp->log($sandbox_enabled_on_off, "sandbox_enabled_on_off");

			if (!isset($sandbox_enabled_on_off)) {

	        		// First use the enabled value from the saved paypal configs
        			$sandbox_enabled_on_off = (($sandboxConfigs['enabled']) ? "on" : "off");
			}                	

			$this->data['paypal_sandbox_mode']	= Input::old('paypal_sandbox_mode', $sandbox_enabled_on_off);
                	$this->data['sandbox_master_account']   = Input::old('sandbox_master_account', $sandboxConfigs['master_account']);
                	$this->data['sandbox_api_username']     = Input::old('sandbox_api_username', $sandboxConfigs['api_username']);
                	$this->data['sandbox_api_password']     = Input::old('sandbox_api_password', $sandboxConfigs['api_password']);
                	$this->data['sandbox_api_signature']    = Input::old('sandbox_api_signature', $sandboxConfigs['api_signature']);

                	$this->data['paypal_master_account']    = Input::old('paypal_master_account', $paypalConfigs['master_account']);
                	$this->data['paypal_api_username']      = Input::old('paypal_api_username', $paypalConfigs['api_username']);
                	$this->data['paypal_api_password']      = Input::old('paypal_api_password', $paypalConfigs['api_password']);
                	$this->data['paypal_api_signature']     = Input::old('paypal_api_signature', $paypalConfigs['api_signature']);

		} else {

			if (!isset($sandbox_enabled_on_off)) {
			
				$sandbox_enabled_on_off = "off";
			}

                        $this->data['paypal_sandbox_mode']	= Input::old('paypal_sandbox_mode', $sandbox_enabled_on_off);
                        $this->data['sandbox_master_account']   = Input::old('sandbox_master_account', "");
                        $this->data['sandbox_api_username']     = Input::old('sandbox_api_username', "");
                        $this->data['sandbox_api_password']     = Input::old('sandbox_api_password', "");
                        $this->data['sandbox_api_signature']    = Input::old('sandbox_api_signature', "");

                        $this->data['paypal_master_account']    = Input::old('paypal_master_account', "");
                        $this->data['paypal_api_username']      = Input::old('paypal_api_username', "");
                        $this->data['paypal_api_password']      = Input::old('paypal_api_password', "");
                        $this->data['paypal_api_signature']     = Input::old('paypal_api_signature', "");
		}		

		$sandbox_enabled_on_off = Input::old('paypal_sandbox_mode', $sandbox_enabled_on_off);

		if (!isset($sandbox_enabled_on_off)) {

			$sandbox_enabled_on_off = "off";
		}

                // We need to set up the options to pass to the Form::input for the sandbox enabled checkbox
                if ($sandbox_enabled_on_off == "on") {

                        // We will just array_merge this option into the other options being set in the view
                        $this->data['sandbox_enabled_input_options'] = array('checked'=>'checked', 'value'=>'on');
                        $this->data['paypal_sandbox_mode'] = 'on';

                } else {

                        // Just setting checked to blank wasn't working
                        $this->data['sandbox_enabled_input_options'] = array('value'=>'off');
                        $this->data['paypal_sandbox_mode'] = 'off';
                }

                return View::make('adminPayments', $this->data);
        }
        
        public function postPayments() {

		$posted_user_id 	= Request::get( 'user_id');
		$paypal_sandbox_mode    = Request::get( 'paypal_sandbox_mode' );
                $sandbox_enabled 	= (($paypal_sandbox_mode == "on") ? 1 : 0);

		$sandbox_master_account = Request::get( 'sandbox_master_account' );
		$sandbox_api_username   = Request::get( 'sandbox_api_username' );
		$sandbox_api_password   = Request::get( 'sandbox_api_password' );
		$sandbox_api_signature  = Request::get( 'sandbox_api_signature' );
		
		$paypal_master_account  = Request::get( 'paypal_master_account' );
		$paypal_api_username    = Request::get( 'paypal_api_username' );
		$paypal_api_password    = Request::get( 'paypal_api_password' );
		$paypal_api_signature   = Request::get( 'paypal_api_signature' );
                
		$this->firephp->log($paypal_sandbox_mode, "paypal_sandbox_mode");
		$this->firephp->log($sandbox_enabled, "sandbox_enabled");
		$this->firephp->log($sandbox_master_account, "sandbox_master_account");
		$this->firephp->log($sandbox_api_username, "sandbox_api_username");
		$this->firephp->log($sandbox_api_password, "sandbox_api_password");
		$this->firephp->log($sandbox_api_signature, "sandbox_api_signature");
		$this->firephp->log($paypal_master_account, "paypal_master_account");
		$this->firephp->log($paypal_api_username, "paypal_api_username");
		$this->firephp->log($paypal_api_password, "paypal_api_password");
		$this->firephp->log($paypal_api_signature, "paypal_api_signature");

                // Add the csrf before filter to guard against cross-site request forgery
                $this->beforeFilter('csrf');
 
		try {

			$validate = new PaymentsValidationService(Input::all());

			$validate->save();

		} catch (ValidateException $errors) {
			
                        //Send the $errors object to the redirected page along with inputs
                        return Redirect::to('admin/payments')->withErrors($errors->get())->withInput();
		}

		$paypal_configs = array();

		$paypal_configs['sandbox']['enabled']         = $sandbox_enabled;
		$paypal_configs['sandbox']['master_account']  = $sandbox_master_account;
		$paypal_configs['sandbox']['api_username']    = $sandbox_api_username;
		$paypal_configs['sandbox']['api_password']    = $sandbox_api_password;
		$paypal_configs['sandbox']['api_signature']   = $sandbox_api_signature;

		$paypal_configs['paypal']['master_account']   = $paypal_master_account;
		$paypal_configs['paypal']['api_username']     = $paypal_api_username;
		$paypal_configs['paypal']['api_password']     = $paypal_api_password;
		$paypal_configs['paypal']['api_signature']    = $paypal_api_signature;

		$this->firephp->log($paypal_configs, 'paypal_configs');

		$paypalConfigs = PaypalConfig::find($posted_user_id);

		if (!$paypalConfigs) {

			$paypalConfigs = new PaypalConfig();

			$paypalConfigs->user_id = $posted_user_id;
		} 

		//$paypalConfigs->config = create_compress_encrypt_config($paypal_configs, 'paypal', $this->zipper, $this->firephp);
		
		$paypalConfigs->config = Crypt::encrypt( serialize( $paypal_configs ));

		// Save the newly created or updated paypalConfig object
		$success = $paypalConfigs->save();
			
		$this->firephp->log($success, 'success');

		if ($success) {
		
			Session::flash('success', true);

			return Redirect::to('/admin/payments')->withInput();
		}

		return Redirect::to('/admin/payments')->withInput();

        }

        public function delete() {

                if ($this->data['admin_user']) {

                        $user_id = Request::get('user_id');

                        if (isset($user_id) && $user_id != '') {

				require_once('includes/php/library/config.helper.inc.php');

                                $this->firephp->log($user_id, "user_id");

				if ($user_id > 1) {

					$user = User::find($user_id);

					$usersConfig = $user->usersConfig;

					$usersConfigs = $usersConfig->config();

					// Delete the user's config record
					$usersConfig->delete();

                                	$mpd_dir    = $usersConfigs['mpd']['mpd_dir'];
                                	$music_dir  = $usersConfigs['music']['music_dir'];
                                	$queue_dir  = $usersConfigs['music']['queue_dir'];
                                	$art_dir    = $usersConfigs['music']['art_dir'];
	
        	                        $user_directories_destroyed = destroy_user_directories($mpd_dir, $music_dir, $queue_dir, $art_dir, $firephp);

            				$station = $user->station;                    

					$stationIcon = $station->stationsIcon;

					if ($stationIcon->id > 1) {

						$this->firephp->log("Deleting the station icon since it was a custom icon uploaded by the user that is being deleted");
						$stationIcon->delete();
					}

					$owner = $station->owner;

					$station->delete();

					$stationIcons = $user->stationIcons;

					foreach($stationIcons as $stationIcon) {

						if ($stationIcon->id > 1) {
						
							$stationIcon->delete();
						}
					}

					$station = null;

            				$stations = $user->stations;                    
	
					foreach($stations as $station) {
				
						$stationIcon = $station->stationsIcon;
	
						// Delete the stationIcon for the station
						$stationIcon->delete();
						
						$owner = $station->owner;
	
						$creator = $station->creator;
				
						// Delete this station that was added by the user
						$station->delete();	
					}	

					$preferences = $user->preferences;

					// Delete the user's preferences
					$preferences->delete();
	
					$themes = $user->themes;

					foreach($themes as $theme) {

						// Delete this theme that was added by the user
						$theme->delete();	
					}

				} else {
	
					$this->firephp->log("User 1 cannot be deleted", "message");
				}

				// Now finally delete the user record
				$user->delete();
    			}
                }

                return false;
        }
}
