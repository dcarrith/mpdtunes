<?php

use Services\Users\Validation as UserValidationService;

class RegisterController extends BaseController {

	function __construct() {

		parent::__construct();

                // Get and merge the theme config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('theme'));

                // Get and merge the analytics config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('analytics'));

                // Get and merge the app config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('app'));

                // Get and merge the paypal config defaults into the main data array 
                $this->data = array_merge($this->data, Configurator::getDefaults('paypal'));

                // Get and merge the recaptcha config defaults into the main data array 
                //$this->data = array_merge($this->data, Configurator::getDefaults('recaptcha'));

                // Get and merge the register config defaults into the main data array 
                $this->data = array_merge($this->data, Configurator::getDefaults('register'));

		// Get and merge the server config defaults into the main data array 
                $this->data = array_merge($this->data, Configurator::getDefaults('site'));

                // Get and merge all the words we need for the Register controller into the main data array     
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("register"));

		// home doesn't need to be ssl encrypted
		$home_link = $this->data['base_protocol'] . $this->data['base_domain'] . "/";

		// we have to override the default so that it links back to home properly
		$this->data['home_link_data_ajax']	= "false";
		$this->data['home_link']		= $home_link;
	}

	public function getIndex() {

		if (Auth::check()) {
                        return Redirect::to('home');
                        exit();
                }

                $first_name		= Request::old('first_name');
                $last_name 		= Request::old('last_name');
                $email 			= Request::old('username');
                $password       	= Request::old('password');
		$password_confirmation 	= Request::old('password_confirmation');

                $this->firephp->log($first_name, "first name");
                $this->firephp->log($last_name, "last name");
                $this->firephp->log($email, "email");
                $this->firephp->log($password, "password");
		$this->firephp->log($password_confirmation, "password_confirmation");
	
		$this->data['first_name'] = $first_name;
		$this->data['last_name'] = $last_name;
		$this->data['username'] = $email;

		$this->data['password'] = $password;
		$this->data['password_confirmation'] = $password_confirmation;
	
                return View::make('register', $this->data);
	}

	public function postIndex() {

		if (Auth::check()) {
                        return Redirect::to('home');
                        exit();
                }

		$first_name     	= Request::get('first_name');
		$last_name      	= Request::get('last_name');
		$email          	= Request::get('username');
		$password       	= Request::get('password');
		$password_confirmation 	= Request::get('password_confirmation');

		$this->firephp->log($this->data, "this->data");

		$this->firephp->log($first_name, "first name");
		$this->firephp->log($last_name, "last name");
		$this->firephp->log($email, "username");
		$this->firephp->log($password, "password");
		$this->firephp->log($password_confirmation, "password_confirmation");

		// Add the csrf before filter to guard against cross-site request forgery
		$this->beforeFilter('csrf');
                
		try {

			$validation = new UserValidationService(Input::all());

			$validation->register();

		} catch (ValidateException $errors) {
			
			//return Redirect::to('posts/'.$id)->withErrors($errors->get());
			return Redirect::to('register')->withErrors($errors->get())->withInput();
		}

		$this->firephp->log("Validation passed", "message");

		$this->data['saved_successfully'] = true;

                        $this->data['saved_successfully_i18n'] = $this->data['saved_successfully'];
                        
			// Default new users to inactive
                        $active = 0;
	
			// Default new users to be normal users (role = 3)
                        $role = 3;

			// See if the paypal payments is enabled for new users
                        $paypal_enabled = $this->data['paypal_enabled'];

                        // We only want to allow the option of open registration if paypal is not enabled
                        if ( !$paypal_enabled ) {

                                $open_registration = $this->data['open_registration'];

                                if ( $open_registration ) {

                                        $active = 1;
                                }
                        }

			$this->firephp->log("Trying to create new user", "message");

			$user = new User;
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->email = $email;
			$user->password = Hash::make($password);
			$user->role_id = $role;
			$user->active = $active;			

			$success = $user->save();

			$this->firephp->log($success, "User created?");

                        if ($success) {

	                        //Session::flash('success', true);

        	                //$this->firephp->log($success, "success");

	                        //$this->data['saved_successfully'] = true;

				$usersPreferences = new UsersPreferences;
				$usersPreferences->user_id = $user->id;
				$usersPreferences->theme_id = 1;
				$usersPreferences->mode = 'streaming';
				$usersPreferences->crossfade = 50;
				$usersPreferences->volume_fade = 50;
				$usersPreferences->language_id = 1;

                                $preferences_saved_successfully = $usersPreferences->save();

				$this->firephp->log($preferences_saved_successfully, "UsersPreferences created?");

                                if (!$preferences_saved_successfully) {

                                        //redirect('/register/error', 'location', 301);
                                }

				// Get and merge the register config defaults into the main data array 
				$this->data = array_merge($this->data, Configurator::getDefaults('register'));

                                $mpd_port 		= $this->data['default_starting_mpd_port'] + $user->id;
                                $mpd_stream_port 	= $this->data['default_starting_mpd_stream_port'] + ($user->id + 1);
                                $station_name 		= $first_name. " ". ((strrpos($last_name, 's') == (strlen($last_name) - 1)) ? $last_name."'" : $last_name."'s") . " stream";
                                $station_description 	= "Be the DJ you've always wanted to be."; 
                                $document_root 		= $this->data['document_root'];
                                $base_domain 		= $this->data['base_domain'];
                                $base_protocol 		= $this->data['base_protocol'];                 
                                $station_url		= $base_protocol.$base_domain.":".$mpd_stream_port."/mpd.ogg";
                                $visibility 		= $this->data['default_user_station_visibility'];

                                $this->firephp->log($mpd_port, "mpd_port");
                                $this->firephp->log($mpd_stream_port, "mpd_stream_port");
                                $this->firephp->log($station_name, "station_name");
                                $this->firephp->log($station_description, "station_description");
                                $this->firephp->log($station_url, "station_url");
                                
				// Create a new station and populate then save it
				$station = new Station;

				// Set the default station icon id
				$station->icon_id = 1;
				$station->name = $station_name;
				$station->description = $station_description;
				$station->url = $station_url;
				$station->url_hash = hash('sha512', $station_url);
				$station->visibility = $visibility;
			
				$station_saved_successfully = $station->save();
				
				/*$station = Station::create( 
								array(	
									'icon_id' => 1,
									'name' => $station_name,
									'description' => $station_description,
									'url' => $station_url,
									'url_hash' => hash('sha512', $station_url),
									'visibility' => $visibility 
								) 
							);*/

				$this->firephp->log($station_saved_successfully, "User's station created?");

				if (!$station_saved_successfully) {

                                        //redirect('/register/error', 'location', 301);
                                
				} else {

					$this->firephp->log($station->id, "station->id");

					$user->station_id = $station->id;
					$user->save();
				}

                                require_once('includes/php/library/config.helper.inc.php');

                                $base_directories = array(	"mpd_dir"       => $this->data['default_base_mpd_dir'],
								"music_dir"     => $this->data['default_base_music_dir'],
								"queue_dir"     => $this->data['default_base_queue_dir'],
								"art_dir"       => $this->data['default_base_cache_art_dir'] );

                                $this->firephp->log($base_directories, "base_directories");

                                $success = false;

                                // this has to be true for general users
                                $anonymize = true;

                                // this setup is not for a master admin
                                $master_setup = false;

                                $user_directories = setup_user_directories($base_directories, $anonymize, $master_setup, $this->firephp);

                                $this->firephp->log($user_directories, "user_directories");

                                if ($user_directories !== false) {

					$mpd_dir                = $user_directories['mpd_dir'];
					$music_dir              = $user_directories['music_dir']; 
					$mpd_server_ip  	= $this->data['default_mpd_server_ip'];
                               
					$audio_local_output_type 	= $this->data['audio_local_output_type'];
					$audio_buffer_size		= $this->data['audio_buffer_size'];
					$buffer_before_play		= $this->data['buffer_before_play'];
					$connection_timeout		= $this->data['connection_timeout'];
					$max_connections		= $this->data['max_connections'];
					$max_playlist_length		= $this->data['max_playlist_length'];
					$max_command_list_size		= $this->data['max_command_list_size'];
					$max_output_buffer_size		= $this->data['max_output_buffer_size'];
 
                                    	$mpd_instance_parameters    = array(	"document_root"			=>$document_root,
										"mpd_dir" 			=>$mpd_dir,
                                                                                "music_dir" 			=>$music_dir,
                                                                                "ip_address" 			=>$mpd_server_ip,
                                                                                "port" 				=>$mpd_port,
                                                                                "http_streaming_port" 		=>$mpd_stream_port,
                                                                                "audio_local_output_type" 	=>$audio_local_output_type,
                                                                                "audio_buffer_size" 		=>$audio_buffer_size,
                                                                                "buffer_before_play" 		=>$buffer_before_play,
                                                                                "connection_timeout" 		=>$connection_timeout,
                                                                                "max_connections" 		=>$max_connections,
                                                                                "max_playlist_length" 		=>$max_playlist_length,
                                                                                "max_command_list_size"		=>$max_command_list_size,
                                                                                "max_output_buffer_size" 	=>$max_output_buffer_size	);

					$mpd_conf_parameters = setup_user_mpd_instance($mpd_instance_parameters, $master_setup, $this->firephp);

                                        $this->firephp->log($mpd_conf_parameters, "mpd_conf_parameters");

                                        if ($mpd_conf_parameters !== false) {

						$mpd_configurations	= array(	"ip_address"	=>$mpd_conf_parameters['ip_address'],
                                                                                        "port"		=>$mpd_conf_parameters['port'],
                                                                                        "stream_port"   =>$mpd_conf_parameters['http_streaming_port'],
                                                                                        "password"	=>$mpd_conf_parameters['password'] );

						$this->firephp->log($mpd_configurations, "mpd_configurations");
                        
						$user_configs = array();
						$user_configs['mpd']['mpd_host']        = $mpd_conf_parameters['ip_address'];
						$user_configs['mpd']['mpd_port']        = $mpd_conf_parameters['port'];
						$user_configs['mpd']['mpd_stream_port'] = $mpd_conf_parameters['http_streaming_port'];
						$user_configs['mpd']['mpd_password']    = $mpd_conf_parameters['password'];
						$user_configs['mpd']['mpd_dir']         = $user_directories['mpd_dir'];

						$user_configs['music']['music_dir']     = $user_directories['music_dir'];
						$user_configs['music']['queue_dir']     = $user_directories['queue_dir'];
						$user_configs['music']['art_dir']       = $user_directories['art_dir'];

						// Retrieve the UsersConfig object associated with the posted_user_id
						$usersConfig = new UsersConfig();

						$usersConfig->user_id = $user->id;

						$usersConfig->config = Crypt::encrypt( serialize( $user_configs ));

						// Save the updated usersConfig object
						$success = $usersConfig->save();

						$this->firephp->log($success, 'success');

						if ( $success ) {

        						Session::flash('success', true);

        						return Redirect::to('/registration/success')->withInput();
						}
                                        }
                                }

                                /*if ($success) {

                                        if ( $paypal_enabled ) {

                                                // redirect to the free beta program information page
                                                //redirect('/paypal/payments', 'refresh');
                                                //redirect('/paypal/payments', 'location', 301);
                                                //exit();

                                        } else {

                                                // redirect to the free beta program information page
                                                //redirect('/login', 'refresh');
                                                //redirect('/login', 'location', 301);
                                                //exit(); 
                                        }

                                } else {

                                        // redirect to the error page
                                        //redirect('/register/error', 'refresh');
                                        //redirect('/register/error', 'location', 301);
                                        //exit();
                                }*/

				//return Redirect::to('login');

                        } else {

                                //$this->data['error_name_i18n']          = $this->lang->line('save_user_account_error_name');
                                //$this->data['error_description_i18n']   = $this->lang->line('save_user_account_error_description');

                                Session::flash('success', false);

                                $this->firephp->log($success, "success");

                                return Redirect::to('register')->withInput();
                        }
	}


	public function success() {

		$this->data['new_users_email'] = Input::old('username');

                return View::make('registrationSuccess', $this->data);
	}
}
