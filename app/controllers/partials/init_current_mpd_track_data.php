<?php 

/* 
 *
 * this is a section of code that was common among the home.php and queue.php files (with the 
 * exception of a few lines of code).  I didn't want to have to maintain the separate code.
 *
 */
		//$this->firephp->log($this->data, 'this->data');

		$this->data['data_url'] 	= "";
		$this->data['music_playing']	= false;

		$configs = array();
	
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

		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			$this->firephp->log("MPD is connected and ready", "message");

			$this->MPD->RefreshInfo();

			$this->firephp->log($this->MPD->playlist, "MPD Playlist");

			$current_mpd_state = $this->MPD->state;

			$this->firephp->log($current_mpd_state, "current_mpd_state");

			if ($current_mpd_state == MPD_STATE_PLAYING || $current_mpd_state == MPD_STATE_PAUSED) { 

				$music_playing = true;

				$current_track_id = $this->MPD->current_track_id;

				$this->firephp->log($this->MPD->playlist, "MPD Playlist");
				$this->firephp->log($this->MPD->current_track_id, "this->MPD->current_track_id");

				if ($current_track_id < 0) {

					$current_track_id = 0;
				}

				$current_file = $this->MPD->playlist[$current_track_id]['file'];

				$this->firephp->log($current_file, "current_file");

			  	if (strpos($current_file, "http://") === 0) {

					$urlHash = hash("sha512", $current_file);
					$this->firephp->log($urlHash, "urlHash");

					$station = Station::where('url_hash', '=', $urlHash)->first();

			  		$stream_is_current_track = true;
					$this->firephp->log($stream_is_current_track, "stream_is_current_track?");

			  		$current_album_art = $this->data['default_no_station_icon'];

					if (isset($station)) {

						$this->firephp->log($station->toArray(), "station->toArray()");

						$station_url = $station->url;
						$station_name = $station->name;
						$station_description = ((strlen($station->description) >= 108) ? substr($station->description, 0, 108).'...' : $station->description);
	
						$this->firephp->log($station_url, "station_url");
						$this->firephp->log($station_name, "station_name");
						$this->firephp->log($station_description, "station_description");
		
						$this->firephp->log($station->stationsIcon->toArray(), "station->stationsIcon->toArray()");

						$current_album_art = "/".$station->stationsIcon->baseurl.$station->stationsIcon->filename;
					}

			  		$current_artist = $station_name;
			  		$current_album 	= $station_description;
					$current_track 	= $station_url;
			    
			    	} else {

				  	$current_artist = $this->MPD->playlist[$current_track_id]['Artist'];
				  	$current_album 	= $this->MPD->playlist[$current_track_id]['Album'];
				    	$current_track 	= $this->MPD->playlist[$current_track_id]['Title'];

				  	require_once('includes/php/library/art.inc.php');

					$configs['music_dir'] 				= $this->data['music_dir'];
					$configs['art_dir'] 				= $this->data['art_dir'];
					$configs['document_root'] 			= $this->data['document_root'];
					$configs['default_no_album_art_image'] 		= $this->data['default_no_album_art_image'];

					$current_album_art = "/".get_album_art	(	$current_file, 
											$current_artist, 
											$current_album, 
											$configs	);

					//$this->firephp->log($current_album_art, "current_album_art");

					$current_track_position = $this->MPD->current_track_position;
					$current_track_length 	= $this->MPD->current_track_length;

					$percent_played = number_format((($current_track_position / $current_track_length) * 100), 0);
				}


				$this->firephp->log($current_artist, "current_artist");
				$this->firephp->log($current_album, "current_album");
				$this->firephp->log($current_track, "current_track");
				$this->firephp->log($current_file, "current_file");
				$this->firephp->log($current_album_art, "current_album_art");
				$this->firephp->log($current_track_position, "current_track_position");
				$this->firephp->log($current_track_length, "current_track_length");

			  	$this->data['current_artist']	 	= $current_artist;
			  	$this->data['current_album'] 		= ((strlen($current_album) > 32) ? (substr($current_album, 0, 32).'...') : $current_album);
			  	$this->data['current_track'] 		= ((strlen($current_track) > 32) ? (substr($current_track, 0, 32).'...') : $current_track);
			  	$this->data['current_file'] 		= $current_file;
			  	$this->data['current_album_art']	= $current_album_art;
				$this->data['current_track_position']	= $current_track_position;
				$this->data['current_track_length'] 	= $current_track_length;

				if ((Request::segment(1) == 'home') || (Request::segment(1) == '')) {

					$this->data['track_progress_div_display'] 	= '';
					$this->data['load_progress_div_width'] 		= 'width:100%;';
					$this->data['play_progress_div_width']		= 'width:'.$percent_played.'%;';

					$this->data['current_audio_time'] 		= get_timer_display($current_track_position);
					$this->data['current_track_duration'] 		= get_timer_display($current_track_length);

				} else { // must be the queue page

					if($current_mpd_state == MPD_STATE_PLAYING) {
						
						$this->data['current_state_message'] = "Currently Playing";

					} else { 

						$this->data['current_state_message'] = "Currently Paused";
					}
				}

			} else {

				
				
				if ((Request::segment(1) == 'home') || (Request::segment(1) == '')) {
		
					$this->data['player_playing_div_style'] 	= 'style="display:none;"';
					$this->data['track_progress_div_display'] 	= 'display:none;';

				} else { // must be the queue page

					$this->data['currently_playing_info_div_style'] = 'style="display:none;"';
				}
			}

		}  else {

			if ((Request::segment(1) == 'home') || (Request::segment(1) == '')) {

	        		$this->data['player_playing_div_style']         = 'style="display:none;"';
	            		$this->data['track_progress_div_display']       = 'display:none;';

	        	} else { // must be the queue page

	        		$this->data['currently_playing_info_div_style'] = 'style="display:none;"';
	        	} 
        	}

        	$this->firephp->log($music_playing, "music_playing");

		// if the music is playing, then these config variables will already be set
		if (($music_playing === false) || ($stream_is_current_track === true)) {
	
			$configs['music_dir'] 			= $this->data['music_dir'];
			$configs['art_dir'] 			= $this->data['art_dir'];
			$configs['document_root'] 		= $this->data['document_root'];
			$configs['default_no_album_art_image'] 	= $this->data['default_no_album_art_image'];

			//$this->firephp->log($configs, "configs");
		}

		$configs['queue_dir'] 		 = $this->data['queue_dir'];
		$configs['base_protocol'] 	 = $this->data['base_protocol'];
		$configs['base_domain'] 	 = $this->data['base_domain'];

		$this->firephp->log($configs, "configs");

		$this->firephp->log($this->data, "this->data");

		if ((Request::segment(1) == 'home') || (Request::segment(1) == '')) {

			$mpd_playlist_as_json = get_mpd_playlist_as_json($this->MPD, $configs, $this->firephp);
		}

		$this->data['current_track_playlist_index'] = (isset($current_track_id) ? $current_track_id : 0);

?>
