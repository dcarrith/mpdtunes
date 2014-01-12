<?php

define('NO_ALBUM_ART_MD5', '74ec2ed1b5856df36c21263a7ab47f3d');
define('NO_ALBUM_ART_MD52', 'd89199131765f24bafae77ab8685f58a');

class MPDTunesController extends BaseController {

    	function __construct() {

		parent::__construct();

		$this->data['demo_user_id'] = 3;

		// If not logged in, then redirect to login
                /*if (!Auth::check()) {
                        return Redirect::to('login');
                        exit();
                }*/

		// Auth::user() is already called once in the before auth filter, so we can call it again
		// here without causing another query to the database
		$this->user = Auth::user();


		//Cache::flush();	


		if (!Cache::has('role'.Session::getId())) {

			$this->role = Cache::rememberForever('role'.Session::getId(), function() {
	
				return $this->user->role;
			});
	
		} else {
			
			$this->role = Cache::get('role'.Session::getId());
		}
	
		// Check to see if role level is 99 and if so, then we know this is a master admin
		$this->data['admin_user'] = (($this->role->level == 99) ? true : false);

		$this->data['logged_in'] = true;
		$this->data['user_id'] = $this->user->id;

		$this->firephp->log($this->data['user_id'], "this data user_id");

                // Get and merge all the config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('all', $this->firephp));

		//$this->firephp->log($this->data, 'this->data');

		// Get the UserPreferences for the authenticated user

		if (!Cache::has('preferences'.Session::getId())) {

			$this->preferences = Cache::rememberForever('preferences'.Session::getId(), function() {
	
				return $this->user->preferences;
			});
	
		} else {
			
			$this->preferences = Cache::get('preferences'.Session::getId());
		}
		
		if ($this->preferences) {
 
			if (!Cache::has('theme'.Session::getId())) {

				$this->theme = Cache::rememberForever('theme'.Session::getId(), function() {
	
					return $this->preferences->theme;
				});
	
			} else {
			
				$this->theme = Cache::get('theme'.Session::getId());
			}

			$this->data['theme_id'] = $this->theme->id;

			$this->data['currrent_theme_id'] = $this->theme->id;

			// Header and footer bars, navigation control bars, and main player controls section
			$this->data['theme_bars'] = $this->theme->bars;

			// Buttons and any links that are used to navigate throughout the application
			$this->data['theme_buttons'] = $this->theme->buttons;

			// Main background for the different pages
			$this->data['theme_body'] = $this->theme->body;

			// Play, pause, next and previous controls
			$this->data['theme_controls'] = $this->theme->controls;

			// Buttons that should stand out (matches the below ui-btn-active theme by default...but it doesn't have to)
			$this->data['theme_action'] = $this->theme->actions;

			// See below for an explanation of this setting
			$this->data['theme_active'] = $this->theme->active;

			// default mode is streaming
			$this->data['mode'] = $this->preferences->mode;

			// default crossfade value is 5
			$this->data['crossfade'] = $this->preferences->crossfade;

			// default volume fade is also 5
			$this->data['volume_fade'] = $this->preferences->volume_fade;

			// user's preferred language 
			if (!Cache::has('language'.Session::getId())) {

				$this->language = Cache::rememberForever('language'.Session::getId(), function() {
	
					return $this->preferences->language;
				});
	
			} else {
			
				$this->language = Cache::get('language'.Session::getId());
			}

			$this->data['language_code'] = $this->language->code;
	
			// Set the language for the application
			App::setLocale( $this->language->code );
	
        	        // Get and merge all the words we need for the base controller into the main data array
        	        $this->data = array_merge($this->data, Langurator::getLocalizedWords("base"));
		}
		
		// default current_volume_fade to whatever was the default or in the user preferences
		$this->data['current_volume_fade'] = $this->data['volume_fade'];

		/* It doesn't look like the MPD object can be cached
		
		// First check to see if there is already an MPD object in cache
		if (!Cache::has('mpd')) {

			// Get the variables we need to pass into the closure
			$mpd_host = $this->data['mpd_host'];
			$mpd_port = $this->data['mpd_port'];
			$mpd_password = $this->data['mpd_password'];

			$this->MPD = Cache::rememberForever('mpd', function() use ($mpd_host, $mpd_port, $mpd_password) {

				require_once($this->data['document_root'].'includes/php/classes/mpd.class.php');

        		        // Instantiate the MPD object to be used by the derived controllers             
	        	        return new mpd( $mpd_host, $mpd_port, $mpd_password );
			});

			//var_dump($this->MPD);
			//exit();
	
		} else {
		
			//Cache::forget('mpd');
	
			// Retrieve the MPD object from cache
			$this->MPD = Cache::get('mpd');

			//var_dump($this->MPD);
			//exit();
		}
              
		require_once($this->data['document_root'].'includes/php/library/mpd.inc.php');
 		*/

 		require_once($this->data['document_root'].'includes/php/classes/mpd.class.php');
                require_once($this->data['document_root'].'includes/php/library/mpd.inc.php');
			        
		// Instantiate the MPD object to be used by the derived controllers 
		$this->MPD = new mpd(   $this->data['mpd_host'],
                        	       	$this->data['mpd_port'],
					$this->data['mpd_password']	);

		//$home_link = $this->data['base_protocol'] . $this->data['base_domain'] . "/home";	
		$home_link = "/home";
		$this->firephp->log($home_link, "home_link");
		$this->data['home_link_data_ajax']	= "true";
		$this->data['home_link']		= $home_link;
	}

	public function confirmDelete() {

		$this->data['item_type'] = Request::get('item_type');
		$this->data['item_name'] = Request::get('item_name');
		$this->data['item_id'] = Request::get('item_id');
	
		// replace the %item% and %item_value% placeholders with the items to be confirmed for deletion
		$this->data['are_you_sure_i18n'] = str_replace("%item_type%", $this->data['item_type'], $this->data['are_you_sure_i18n']);
		$this->data['are_you_sure_i18n'] = str_replace("%item_name%", $this->data['item_name'], $this->data['are_you_sure_i18n']);
		$this->data['note_gone_forever_i18n'] = str_replace("%item_type%", $this->data['item_type'], $this->data['note_gone_forever_i18n']);

		$this->firephp->log($this->data, "data");

		return View::make('confirmDelete', $this->data);
	}
}
