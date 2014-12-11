<?php

class SettingsController extends MPDTunesController {

    	public function __construct() {

        	parent::__construct();

                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("settings"));

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("settings"));
	}

	public function index() {

		// Get the user object for the currently logged in user
                //$user = $this->user;


		// Get the logged in user's preferences
		//$preferences = $user->usersPreferences;

		$this->firephp->log($this->preferences->toJson(), "preferences");

		$this->data['data_url'] = "";

		// if mpd is connected, make sure we have the current volume and crossfade settings
		if ($this->xMPD->isConnected()) {

			$this->data['current_volume'] = $this->xMPD->volume;
			$this->data['current_xfade'] = $this->xMPD->xfade;
			$this->data['current_mixrampdb'] = $this->xMPD->mixrampdb;
			$this->data['current_mixrampdelay'] = $this->xMPD->mixrampdelay;
		}

		// First check to see if all themes are in cache already
		if (!Cache::has('themes')) {

			$this->themes = Cache::rememberForever('themes', function() {

				// Get all available themes
				return Theme::all();
			});

		} else {

			// Retrieve all themes from cache
			$this->themes = Cache::get('themes');
		}

		$this->firephp->log($this->themes->toJson(), "themes");

		$this->data['theme_options'] = array();

		$this->data['selected_theme'] = "";

		foreach($this->themes as $theme) {

			$this->data['theme_options'][$theme->id] = $theme->name;

			if ($theme->id == $this->data['currrent_theme_id']) {

				$this->data['selected_theme'] = $theme->id;
			}
		}

		$this->data['theme_options'][0] = "Create a New Theme...";

		$this->data['selected_mode'] = "streaming";

		if ( $this->data['mode'] == 'remote-control') {

			$this->data['selected_mode'] = "remote-control";

		} else if ( $this->data['mode'] == 'disc-jockey') {

			$this->data['selected_mode'] = "disc-jockey";

		} else {

			$this->data['selected_mode'] = "streaming";
		}

		$this->data['mode_options']['streaming'] 	= "Streaming";
		$this->data['mode_options']['remote-control'] 	= "Remote Control";
		$this->data['mode_options']['disc-jockey'] 	= "Disc Jockey";

		// First check to see if all languages are in cache already
		if (!Cache::has('languages')) {

			$this->languages = Cache::rememberForever('languages', function() {

				// Get all the available languages
				return Language::all();
			});

		} else {

			// Retrieve all languages from cache
			$this->languages = Cache::get('languages');
		}

		$this->firephp->log($this->languages->toJson(), "languages");

		$this->data['language_options'] = array();

		$this->data['selected_language']= "";

		$default_language = $this->language->code;

		foreach($this->languages as $language) {

			$this->data['language_options'][$language->id] = $language->name;

			if ($language->code == $default_language) {

				$this->data['selected_language'] = $language->id;
			}
		}

                $this->firephp->log( $this->data, "this->data");

                // Return the settings view
                return View::make('settings', $this->data);
	}

        public function volume() {

                $this->data['data_url'] = "";

                // if mpd is connected, make sure we have the current volume and crossfade settings
                if ($this->xMPD->isConnected()) {

                        $this->data['current_volume']   = $this->xMPD->volume;
                        $this->data['current_xfade']    = $this->xMPD->xfade;
			$this->data['current_mixrampdb'] = $this->xMPD->mixrampdb;
			$this->data['current_mixrampdelay'] = $this->xMPD->mixrampdelay;
		}

                $this->firephp->log( $this->data, "this->data");

                // Return the volumeCrossfade view
                return View::make('volumeCrossfade', $this->data);
        }

        public function apply() {

                $this->firephp->log( $this->data, "this->data");

                // Return the applySettings view
                return View::make('applySettings', $this->data);
        }

	public function save($what) {

                // Get the user object for the currently logged in user
                $user = Auth::user();
		$user_id = $user->id;

		switch($what) {

			case 'theme':

				$theme_id = Request::get('theme_id');

				if (isset($theme_id) && isset($user_id)) {

                        		// Get the logged in user's preferences
                        		$preferences = $user->preferences;
                        		$preferences->theme_id = $theme_id;
                        		$preferences->save();

					// Clear theme from cache
					Cache::forget('theme'.Session::getId());

					// Clear list of all themes from cache
					Cache::forget('themes');

					// It seems like we have to refresh the preferences so the theme relationship will be updated
					$preferences = UsersPreferences::find($user->id);
                        		$theme = $preferences->theme;

                        		// Echo out the theme as JSON so we can apply it to the client
                        		echo $theme->toJson();
                		}

				break;

                        case 'language':

                                $language_id = Request::get('language_id');

                                if (isset($language_id) && isset($user_id)) {

                                        // Get the logged in user's preferences
                                        $preferences = $user->preferences;
                                        $preferences->language_id = $language_id;
                                        $preferences->save();

					// Clear language from cache
					Cache::forget('language'.Session::getId());

					// Clear list of all languages from cache
					Cache::forget('languages');

					// Get the language code
					$language = Language::find($language_id);
					$language_code = $language->code;

					App::setLocale( $language_code );

					echo 1;
                             	}

                                break;

                        case 'volume':

	                	$volume 	= Request::get('volume');
                		$crossfade      = Request::get('crossfade');
                		$mixrampdb      = Request::get('mixrampdb');
				$mixrampdelay   = Request::get('mixrampdelay');
				$volume_fade    = Request::get('volume_fade');

                        	// if mpd is connected, make sure we have the current volume and crossfade settings
                        	if ($this->xMPD->isConnected()) {

                                	if ($this->data['admin_user']) {

						if ($volume < 0) {

							$volume = 10;
						}

                                        	$this->xMPD->setvol($volume);
                                	}

                                	$this->xMPD->crossfade($crossfade);
		                        $this->xMPD->mixrampdb($mixrampdb);
					$this->xMPD->mixrampdelay($mixrampdelay);
				}

                                // Get the logged in user's preferences
                                $preferences = $user->preferences;
                        	$preferences->crossfade = $crossfade;
                        	$preferences->volume_fade = $volume_fade;
                     		$preferences->save();

                                echo 1;

                                break;

			default:

				break;
		}

		// Clear preferences from cache
		Cache::forget('preferences'.Session::getId());

		// Just in case we had no results above
		echo '';
	}

	public function custom($what) {

		// get all the available colors that can be used when mixing together a new theme
		$theme_colors = ThemesColors::all();

		// default the theme color options to blank (none)
		$this->data['theme_color_options'] = "";

		// default the theme name to blank
		$this->data['theme_name'] = "";

		// only try to generate some options if there are actually some available colors to use
		if (isset($theme_colors) && (count($theme_colors) > 0) && $theme_colors != '') {

			foreach($theme_colors as $theme_color){

				// this is out here in the logic for performance reasons (so we don't have to iterate
				// through the same loop 6 times in the view)
				$this->data['theme_color_options'][$theme_color->letter_code] = $theme_color->name;
			}
		}

		$this->data['icon_color_options'] = array('on'=>'White', 'off'=>'Black');

		$this->firephp->log($this->data, "data");

                // Return the createTheme view
                return View::make('createTheme', $this->data);
	}

	public function create($what) {

                // Get the user object for the currently logged in user
                $user = Auth::user();
		$user_id = $user->id;

		switch($what) {

			case 'theme':

				$icon_color			= Request::get('icon_color');
				$theme_name 			= Request::get('theme_name');
				$bars_letter_code 		= Request::get('bars_letter_code');
				$buttons_letter_code 		= Request::get('buttons_letter_code');
				$body_letter_code 		= Request::get('body_letter_code');
				$controls_letter_code 		= Request::get('controls_letter_code');
				$action_buttons_letter_code 	= Request::get('action_letter_code');
				$active_state_letter_code 	= Request::get('active_state_letter_code');

				$this->firephp->log($icon_color, "icon_color");
				$this->firephp->log($theme_name, "name");
				$this->firephp->log($bars_letter_code, "bars");
				$this->firephp->log($buttons_letter_code, "buttons");
				$this->firephp->log($body_letter_code, "body");
				$this->firephp->log($controls_letter_code, "controls");
				$this->firephp->log($action_buttons_letter_code, "action");
				$this->firephp->log($active_state_letter_code, "active");

				$theme = new Theme();
				$theme->name = $theme_name;
				$theme->creator_id = $user_id;
				$theme->icon = (($icon_color == "Black") ? 'b' : 'w');
				$theme->bars = $bars_letter_code;
				$theme->buttons = $buttons_letter_code;
				$theme->body = $body_letter_code;
				$theme->controls = $controls_letter_code;
				$theme->actions = $action_buttons_letter_code;
				$theme->active = $active_state_letter_code;
				$theme->save();

				$this->firephp->log($theme, "theme object");

				// Get the logged in user's preferences
				$preferences = $user->preferences;

				// Set the user's preferred theme to the newly created one
				$preferences->theme_id = $theme->id;
				$preferences->save();

				// Clear theme from cache
				Cache::forget('theme'.Session::getId());

				// Clear the list of all themes from cache
				Cache::forget('themes');

				// Clear preferences from cache
				Cache::forget('preferences'.Session::getId());

				// It seems like we have to refresh the preferences so the theme relationship will be updated
				//$preferences = UsersPreferences::find($user->id);
				//$theme = $preferences->theme;

				// Echo out the theme as JSON so we can apply it to the client
				echo $theme->toJson();

				/*$theme_id = Request::get('theme_id');

				if (isset($theme_id) && isset($user_id)) {

                        		// Get the logged in user's preferences
                        		$preferences = $user->preferences;
                        		$preferences->theme_id = $theme_id;
                        		$preferences->save();

					// It seems like we have to refresh the preferences so the theme relationship will be updated
					$preferences = UsersPreferences::find($user->id);
                        		$theme = $preferences->theme;

                        		// Echo out the theme as JSON so we can apply it to the client
                        		echo $theme->toJson();
                		}*/

				break;

                        case 'playlist':

                                echo 1;

                                break;

			default:

				break;
		}

		// Just in case we had no results above
		echo '';
	}
}
