<?php

class HomeController extends MPDTunesController {	

	public function index($clear=false) {
	
                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("player"));

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("home"));

		$this->data['data_url'] 	= "";
		$this->data['music_playing']	= false;
	
		$current_track_id 		= 0;
		$current_artist 		= "";
		$current_album 			= "";
		$current_track 			= "";
		$current_file 			= "";
		$current_album_art		= "";
		$current_track_position 	= "-:--";
		$current_track_length 		= "-:--";
		$percent_played 		= 0;
		$music_playing 			= false;
		$stream_is_current_track	= false;

		// Default repeat to off
		$this->data['repeat'] = 0;
		$this->data['shuffle'] = 0;

		if ($this->xMPD->isConnected()) {
			
			$this->xMPD->RefreshInfo();

			// This is so we can determine whether or not to hightlight the repeat button as active or not
			$this->data['repeat'] = $this->xMPD->repeat;
			
			// This is so we can determine whether or not to hightlight the shuffle button as active or not
			$this->data['shuffle'] = $this->xMPD->random;

			$current_mpd_state = $this->xMPD->state;	
			
			if ($current_mpd_state == "play" || $current_mpd_state == "pause") { 

				$music_playing = true;

				$current_track_id = $this->xMPD->current_track_id;

				////$this->firephp->log($this->xMPD->playlist, "xMPD Playlist");
				////$this->firephp->log($this->xMPD->current_track_id, "this->xMPD->current_track_id");

				if ($current_track_id < 0) {

					$current_track_id = 0;
				}

				$current_file = $this->xMPD->playlist[$current_track_id]['file'];

				////$this->firephp->log($current_file, "current_file");

			  	if (strpos($current_file, "http://") === 0) {

					$urlHash = hash("sha512", $current_file);

					$station = null;

					$is_a_users_station = false;

					// http://demo.mpdtunes.com:16604/mpd.ogg
					$matches = array();
 
					$mpdogg = preg_match('/mpd\.ogg/i', $current_file);

					// get host name from URL
					preg_match('@^(?:http://|https://)?([^/]+)@i', $current_file, $matches);
					$domain_port = $matches[1];

					$stream_domain = current(explode(":", $domain_port));

					// If the fqdn of the stream url is the same as the site's base_domain and the mpd.ogg exists, then
					// we'll assume that this is a user's station
					if ( ($mpdogg == 1) && ($this->data['base_domain'] == $stream_domain) ) {
					
						$is_a_users_station = true;
					}

					if (!Cache::has($urlHash."_".($is_a_users_station ? "null" : $this->user->id))) {
	
						$station = Cache::rememberForever($urlHash."_".($is_a_users_station ? "null" : $this->user->id), function() use ($urlHash) {
            		
							$result = Station::where( 'url_hash', '=', $urlHash )->where( function( $query ) {
                					
								$query->where('creator_id', '=', $this->user->id)->orWhere( function ( $query ) {
										
									$query->whereNull('creator_id');
								});
            						});
							
							return $result->first();
						});
	
					} else {
	
						$station = Cache::get($urlHash."_".($is_a_users_station ? "null" : $this->user->id));
					}

			  		$stream_is_current_track = true;

			  		$current_album_art = $this->data['default_no_station_icon'];

					$station_url = "";
					$station_name = "";
					$station_description = "";

					if (isset($station)) {

						$station_url = $station->url;
						$station_name = $station->name;
						$station_description = ((strlen($station->description) >= 108) ? substr($station->description, 0, 108).'...' : $station->description);
	
						$stationsIcon = null;

						if (!Cache::has($urlHash."_".($is_a_users_station ? "null" : $this->user->id)."_icon")) {

							$stationsIcon = Cache::rememberForever($urlHash."_".($is_a_users_station ? "null" : $this->user->id)."_icon", function() use ($station) {
	
								return $station->stationsIcon;
							});
		
						} else {
			
							$stationsIcon = Cache::get($urlHash."_".($is_a_users_station ? "null" : $this->user->id)."_icon");
						}

						$current_album_art = "/".$stationsIcon->baseurl.$stationsIcon->filename;
					}

			  		$current_artist = $station_name;
			  		$current_album 	= $station_description;
					$current_track 	= $station_url;
			    
			    	} else {

				  	$current_artist = $this->xMPD->playlist[$current_track_id]['Artist'];
				  	$current_album 	= $this->xMPD->playlist[$current_track_id]['Album'];
				    	$current_track 	= $this->xMPD->playlist[$current_track_id]['Title'];

					$current_album_art = "/".$this->getAlbumArt( $current_file, $current_artist, $current_album );

					$current_track_position = $this->xMPD->current_track_position;
					$current_track_length 	= $this->xMPD->current_track_length;

					$percent_played = number_format((($current_track_position / $current_track_length) * 100), 0);
				}

				//$this->firephp->log($current_artist, "current_artist");
				//$this->firephp->log($current_album, "current_album");
				//$this->firephp->log($current_track, "current_track");
				//$this->firephp->log($current_file, "current_file");
				//$this->firephp->log($current_album_art, "current_album_art");
				//$this->firephp->log($current_track_position, "current_track_position");
				//$this->firephp->log($current_track_length, "current_track_length");

			  	$this->data['current_artist']	 	= $current_artist;
			  	$this->data['current_album'] 		= ((strlen($current_album) > 32) ? (substr($current_album, 0, 32).'...') : $current_album);
			  	$this->data['current_track'] 		= ((strlen($current_track) > 32) ? (substr($current_track, 0, 32).'...') : $current_track);
			  	$this->data['current_file'] 		= $current_file;
			  	$this->data['current_album_art']	= $current_album_art;
				$this->data['current_track_position']	= $current_track_position;
				$this->data['current_track_length'] 	= $current_track_length;

				$this->data['track_progress_div_display'] 	= '';
				$this->data['load_progress_div_width'] 		= 'width:100%;';
				$this->data['play_progress_div_width']		= 'width:'.$percent_played.'%;';

				$this->data['current_audio_time'] 		= get_timer_display($current_track_position);
				$this->data['current_track_duration'] 		= get_timer_display($current_track_length);

			} else {
	
				$this->data['player_playing_div_style'] 	= 'style="display:none;"';
				$this->data['track_progress_div_display'] 	= 'display:none;';

			}

		}  else {

	        	$this->data['player_playing_div_style']         = 'style="display:none;"';
	            	$this->data['track_progress_div_display']       = 'display:none;';
        	}

		$playlistName 		= "current";
		$tracksListedSoFar 	= 0;
		$tracksToRetrieve 	= "all";

		// This will be the final array to json_encode and set as the json_playlist for the javascript init variables
		$queue = array('tracks' => array());

		// Only try to retrieve the list of tracks from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			$queue['tracks'] = $this->getPlaylistTracks($playlistName, $tracksListedSoFar, $tracksToRetrieve, "sync");
		}


		// We don't need the count
		unset($queue['tracks']['count']);

		$this->firephp->log($queue, "current tracks in the queue");

		$this->data['current_track_playlist_index'] = (isset($current_track_id) ? $current_track_id : 0);

		// JSON encode the current playlist tracks so we can pass it to the javascript
		$this->data['json_playlist'] = json_encode($queue);

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
	
        	//$this->firephp->log($languages, "languages");

        	$this->data['language_options'] = array();

        	$this->data['selected_language']= "";

        	$default_language = $this->language->code;

        	foreach($this->languages as $language) {

            		$this->data['language_options'][$language->id] = $language->name;

            		if ($language->code == $default_language) {
                    
                		$this->data['selected_language'] = $language->id;
            		}
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
		
        	$this->data['theme_options'] = array();

        	$this->data['selected_theme'] = "";

        	foreach($this->themes as $theme) {

            		$this->data['theme_options'][$theme->id] = $theme->name;

            		if ($theme->id == $this->data['currrent_theme_id']) {
                    
                		$this->data['selected_theme'] = $theme->id;
            		}
        	}

        	$this->data['theme_options'][0] = "Create a New Theme...";

		return View::make('home', $this->data);
	}
}
