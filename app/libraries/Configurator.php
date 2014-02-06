<?php

class Configurator {

        private static function getAnalyticsDefaults() {

                $defaults = array();

                $defaults['ga_property_id']                   = Config::get('analytics.ga_property_id');
                $defaults['google_site_verification_code']    = Config::get('analytics.google_site_verification_code');

                return $defaults;
        }

	private static function getAppDefaults() {

		$defaults = array();

                $defaults['mode'] 				= Config::get('defaults.default_mode');
                $defaults['crossfade'] 				= Config::get('defaults.default_crossfade');
                $defaults['mixrampdb']	 			= Config::get('defaults.default_mixrampdb');
                $defaults['mixrampdelay'] 			= Config::get('defaults.default_mixrampdelay');
                $defaults['volume_fade'] 			= Config::get('defaults.default_volume_fade');
                $defaults['default_page_transition'] 		= Config::get('defaults.default_page_transition');
                $defaults['default_dialog_transition'] 		= Config::get('defaults.default_dialog_transition');
                $defaults['default_alert_transition'] 		= Config::get('defaults.default_alert_transition');
                $defaults['default_home_transition']		= Config::get('defaults.default_home_transition');

		// TODO: move these to a config file if they are still used
                $defaults['home_link_data_ajax'] 		= "true";
                $defaults['home_link']				= "/";

		$defaults['default_no_album_art_image']		= Config::get('defaults.default_no_album_art_image');
		$defaults['default_no_station_icon']		= Config::get('defaults.default_no_station_icon');

                // This is so we can decide which templates to generate for lazyloading the different list items
                $defaults['show_album_count_bubbles']		= Config::get('defaults.show_album_count_bubbles');
                $defaults['show_album_track_count_bubbles']	= Config::get('defaults.show_album_track_count_bubbles');
                $defaults['show_playlist_track_count_bubbles']	= Config::get('defaults.show_playlist_track_count_bubbles');

		$defaults['show_albums_total_length']		= Config::get('defaults.show_albums_total_length');
		$defaults['show_album_tracks_length']		= Config::get('defaults.show_album_tracks_length');
		$defaults['show_playlist_tracks_length']	= Config::get('defaults.show_playlist_tracks_length');
		$defaults['show_playlists_total_length']	= Config::get('defaults.show_playlists_total_length');
	
		return $defaults;
	}

        private static function getConfigDefaults($firephp) {

                $defaults = array();

                $configDefaults = Config::get('defaults');
                $firephp->log($configDefaults, "configDefaults");

                foreach ($configDefaults as $key=>$value) {

                        $defaults[$key] = $value;
                }

                return $defaults;
        }

        private static function getEnvironmentDefaults() {

                $defaults = array();

                // Which environment are we running in 
                $defaults['environment']		= Config::get('server.environment');

                // Whether or not to show debug output
                $defaults['debug'] 			= Config::get('defaults.debug');

                // Whether or not to show profiler info
                $defaults['profiling'] 			= Config::get('defaults.profiling');

                return $defaults;
        }

        public static function getGenresDefaults() {

                $defaults = array();

                $defaults['site_title']			= Config::get('server.base_site_title') . " - Genres";

                return $defaults;
        }

        private static function getLazyloaderDefaults($firephp) {

                $defaults = array();
                
		$defaults['show_albums_total_length'] 			= Config::get('defaults.show_albums_total_length');
                $defaults['show_album_tracks_length'] 			= Config::get('defaults.show_album_tracks_length');
                $defaults['show_playlist_tracks_length']		= Config::get('defaults.show_playlist_tracks_length');
                $defaults['show_playlists_total_length'] 		= Config::get('defaults.show_playlists_total_length');
                $defaults['default_num_genres_to_display'] 		= Config::get('defaults.default_num_genres_to_display');
                $defaults['default_num_artists_to_display'] 		= Config::get('defaults.default_num_artists_to_display');
                $defaults['default_num_albums_to_display'] 		= Config::get('defaults.default_num_albums_to_display');
                $defaults['default_num_playlist_tracks_to_display'] 	= Config::get('defaults.default_num_playlist_tracks_to_display');
                $defaults['default_num_queue_tracks_to_display']	= Config::get('defaults.default_num_queue_tracks_to_display');
                $defaults['default_num_playlists_to_display'] 		= Config::get('defaults.default_num_playlists_to_display');
                $defaults['default_num_stations_to_display'] 		= Config::get('defaults.default_num_stations_to_display');

                // This is so we can decide which templates to generate for lazyloading the different list items
                $defaults['show_album_count_bubbles']           	= Config::get('defaults.show_album_count_bubbles');
                $defaults['show_album_track_count_bubbles']     	= Config::get('defaults.show_album_track_count_bubbles');
                $defaults['show_playlist_track_count_bubbles']  	= Config::get('defaults.show_playlist_track_count_bubbles');

                return $defaults;
        }

	private static function getPaypalDefaults() {

		$defaults = array();

		$defaults['paypal_enabled']	    = Config::get('paypal.paypal_enabled');
		$defaults['open_registration']	    = Config::get('paypal.open_registration');

		$defaults['paypal_sandbox']         = Config::get('paypal.paypal_sandbox');
                $defaults['paypal_proxy_host']      = Config::get('paypal.paypal_proxy_host');
                $defaults['paypal_proxy_port']      = Config::get('paypal.paypal_proxy_port');
                $defaults['paypal_use_proxy']       = Config::get('paypal.paypal_use_proxy');
                $defaults['paypal_version']         = Config::get('paypal.paypal_version');
                $defaults['paypal_return_url']      = Config::get('paypal.paypal_checkout_return_url');
                $defaults['paypal_cancel_url']      = Config::get('paypal.paypal_checkout_cancel_url');

                if ($defaults['paypal_sandbox']) {

                        $defaults['paypal_api_endpoint_url']        = Config::get('paypal.paypal_sandbox_api_endpoint_url');
                        $defaults['paypal_url']                     = Config::get('paypal.paypal_sandbox_url');
                        $defaults['paypal_dg_url']                  = Config::get('paypal.paypal_sandbox_dg_url');

                        $defaults['paypal_api_username']            = Config::get('paypal.paypal_sandbox_api_username');
                        $defaults['paypal_api_password']            = Config::get('paypal.paypal_sandbox_api_password');
                        $defaults['paypal_api_signature']           = Config::get('paypal.paypal_sandbox_api_signature');

                } else {

                        $defaults['paypal_api_endpoint_url']        = Config::get('paypal.paypal_api_endpoint_url');
                        $defaults['paypal_url']                     = Config::get('paypal.paypal_url');
                        $defaults['paypal_dg_url']                  = Config::get('paypal.paypal_dg_url');

                        $defaults['paypal_api_username']            = Config::get('paypal.paypal_api_username');
                        $defaults['paypal_api_password']            = Config::get('paypal.paypal_api_password');
                        $defaults['paypal_api_signature']           = Config::get('paypal.paypal_api_signature');
                }

                // BN Codeis only applicable for partners
                //$defaults['paypal_bn_code'] = Config::get('paypal.paypal_bn_code');
	
		return $defaults;
	}

	private static function getPlayerDefaults() {

		$defaults = array();

		// Initialize variables to hold some of the settings page defaults
                $defaults['popupMenuId']              	= "settingsPopupMenu";
                $defaults['selected_language']        	= "1";
                $defaults['selected_theme']           	= "1";
	
		// Initialize variables to hold track player progress styles
                $defaults['player_playing_div_style'] 	= '';
                $defaults['track_progress_div_display'] = '';
                $defaults['load_progress_div_width']    = 'width:100%;';
                $defaults['play_progress_div_width']    = 'width:0%;';

		// Initialize the variables to hold track position data
                $defaults['current_audio_time']       	= "-:--";
                $defaults['current_track_duration']   	= "-:--";
                $defaults['current_track_position']     = "-:--";
                $defaults['current_track_length']       = "-:--";

		// Initialize variables to hold current track data
                $defaults['current_track_id']         	= null;
                $defaults['current_artist']           	= "";
                $defaults['current_album']            	= "";
                $defaults['current_track']            	= "";
                $defaults['current_file']             	= "";
                $defaults['current_album_art']        	= Config::get('defaults.default_no_album_art_image');              
 
		// Initialize variables to hold music playing data
                $defaults['percent_played']           	= 0;
                $defaults['music_playing']            	= false;
                $defaults['stream_is_current_track']  	= false;
	
		// Initialize the json playlist variable
                $defaults['json_playlist']            	= "";
		
		return $defaults;
	}

        public static function getPlaylistsDefaults() {

                $defaults = array();

                $defaults['site_title']                         = Config::get('defaults.base_site_title') . " - Playlists";
                $defaults['show_playlist_track_count_bubbles']  = Config::get('defaults.show_playlist_track_count_bubbles');

                return $defaults;
        }

        public static function getQueueDefaults() {

                $defaults = array();

                $defaults['site_title']                 = Config::get('server.base_site_title') . " - Queue";

                return $defaults;
        }
	
        private static function getRecaptchaDefaults() {

		$defaults = array();                        

		$defaults['recaptcha_private_key']	= Config::get('recaptcha.recaptcha_private_key');
		$defaults['recaptcha_public_key']	= Config::get('recaptcha.recaptcha_public_key');
                $defaults['recaptcha_lang']		= Config::get('recaptcha.language');

		return $defaults;
	}
        
        public static function getRegisterDefaults() {

                $defaults = array();

                $defaults['default_starting_mpd_port']          = Config::get('defaults.default_starting_mpd_port');
                $defaults['default_starting_mpd_stream_port']   = Config::get('defaults.default_starting_mpd_stream_port');
		$defaults['default_mpd_server_ip']		= Config::get('defaults.default_mpd_server_ip');
                $defaults['audio_local_output_type']            = Config::get('defaults.audio_local_output_type');
                $defaults['audio_buffer_size']                  = Config::get('defaults.audio_buffer_size');
                $defaults['buffer_before_play']                 = Config::get('defaults.buffer_before_play');
                $defaults['connection_timeout']                 = Config::get('defaults.connection_timeout');
                $defaults['max_connections']                    = Config::get('defaults.max_connections');
                $defaults['max_playlist_length']                = Config::get('defaults.max_playlist_length');
                $defaults['max_command_list_size']              = Config::get('defaults.max_command_list_size');
                $defaults['max_output_buffer_size']             = Config::get('defaults.max_output_buffer_size');
                $defaults['default_base_mpd_dir']               = Config::get('defaults.default_base_mpd_dir');
                $defaults['default_base_music_dir']             = Config::get('defaults.default_base_music_dir');
                $defaults['default_base_queue_dir']             = Config::get('defaults.default_base_queue_dir');
                $defaults['default_base_cache_art_dir']         = Config::get('defaults.default_base_cache_art_dir');
                $defaults['default_user_station_visibility']    = Config::get('defaults.default_user_station_visibility');

                return $defaults;
        }

        public static function getSettingsDefaults() {

                $defaults = array();

                $defaults['site_title'] = Config::get('server.base_site_title') . " - Settings";

                return $defaults;
        }
	
        private static function getSiteDefaults() {

                $defaults = array();

                $defaults['base_protocol'] 	= Config::get('server.base_protocol');
                $defaults['secure_protocol']  	= Config::get('server.secure_protocol');
                $defaults['base_domain']      	= Config::get('server.base_domain');
                $defaults['site_title']       	= Config::get('server.base_site_title');
		$defaults['document_root']      = Config::get('server.document_root');

                return $defaults;
        }

        public static function getStationsDefaults() {

                $defaults = array();

                $defaults['site_title'] = Config::get('server.base_site_title') . " - Stations";

                return $defaults;
        }    

        private static function getThemeDefaults(){

                $defaults = array();

                $defaults['theme_id']         = Config::get('defaults.default_theme_id');
                $defaults['theme_bars']       = Config::get('defaults.default_theme_bars');
                $defaults['theme_buttons']    = Config::get('defaults.default_theme_buttons');
                $defaults['theme_body']       = Config::get('defaults.default_theme_body');
                $defaults['theme_controls']   = Config::get('defaults.default_theme_controls');
                $defaults['theme_action']     = Config::get('defaults.default_theme_action');
                $defaults['theme_active']     = Config::get('defaults.default_theme_active');
                $defaults['theme_alert']      = Config::get('defaults.default_theme_alert');
                $defaults['theme_icon_class'] = Config::get('defaults.default_theme_icon_class');

                return $defaults;
        }

	public static function getTracksDefaults() {
	
		$defaults = array();

                $defaults['site_title'] 		= Config::get('server.base_site_title') . " - Tracks";
	
		return $defaults;
	}
	
	public static function getUploaderDefaults() {

		$defaults = array();

		$defaults['site_title'] 		= Config::get('server.base_site_title') . " - Music Uploader";
		$defaults['default_base_uploads_dir'] 	= Config::get('defaults.default_base_uploads_dir');	
	
		return $defaults;
	}

	public static function getUsersDefaults($firephp=null) {

		$user = Auth::user();	

		$usersConfig = null;
		$configs = null;

		if (!Cache::has('usersConfig'.Session::getId())) {

			$usersConfig = Cache::rememberForever('usersConfig'.Session::getId(), function() use (&$user) {

		        	// Return the usersConfig object for the user
        			return $user->usersConfig;
			});
	
		} else {

			// Retrieve the usersConfig from cache			
			$usersConfig = Cache::get('usersConfig'.Session::getId());
		}

		if (!Cache::has('configs'.Session::getId())) {

			$configs = Cache::rememberForever('configs'.Session::getId(), function() use (&$usersConfig) {

		        	// Return the config associative array for the user that contains the MPD and MUSIC related configs
        			return $usersConfig->config();
			});
	
		} else {

			// Retrieve the configs from cache			
			$configs = Cache::get('configs'.Session::getId());
		}

		if (isset($firephp)) {

        		$firephp->log($configs, "configs");
		}

		$defaults = array();

        	// loop through all of the constants categories from the config.xml and set the configs
        	foreach ($configs as $category) {

                	foreach ($category as $key=>$value){

				$defaults[$key] = $value;
                	}
                }

		return $defaults;
	}

	public static function getDefaults($type="all", $firephp=null) {

		switch($type) {
		
			case 'app':
				return self::getAppDefaults();
				break;
			case 'analytics':
				return self::getAnalyticsDefaults();
				break;
			case 'defaults':
				return self::getConfigDefaults($firephp);
				break;
			case 'environment':
				return self::getEnvironmentDefaults();
				break;
			case 'genres':
				return self::getGenresDefaults();
				break;
			case 'lazyloader':
				return self::getLazyloaderDefaults($firephp);
				break;
			case 'paypal':
				return self::getPaypalDefaults();
				break;
			case 'player':
				return self::getPlayerDefaults();
				break;
			case 'playlists':
				return self::getPlaylistsDefaults();
				break;
			case 'queue':
				return self::getQueueDefaults();
				break;
			case 'recaptcha':
				return self::getRecaptchaDefaults();
				break;
			case 'register':
				return self::getRegisterDefaults();
				break;
			case 'site':
				return self::getSiteDefaults();
				break;
			case 'stations':
				return self::getStationsDefaults();
				break;
                        case 'settings':
                                return self::getSettingsDefaults();
                                break;
			case 'theme':
				return self::getThemeDefaults();
				break;
			case 'tracks':
				return self::getTracksDefaults();
				break;
			case 'tracks':
				return self::getTracksDefaults();
				break;
			case 'uploader':
				return self::getUploaderDefaults();
				break;
			case 'user':
				return self::getUsersDefaults();
				break;
			default:
				return array_merge(	self::getAnalyticsDefaults(),
							self::getAppDefaults(),
							self::getEnvironmentDefaults(),
							self::getLazyloaderDefaults($firephp),
							self::getPlayerDefaults(),
							self::getRecaptchaDefaults(),
							self::getSiteDefaults(),
							self::getThemeDefaults(),
							self::getUsersDefaults($firephp)	);
				break;
		}
	}
}
