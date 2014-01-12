<?php

class QueueController extends MPDTunesController {

        public function __construct() {

                parent::__construct();

                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("queue"));

                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("player"));

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("queue"));

	}

	public function index() {
		
		// lazy loading variables
		$default_num_queue_tracks_to_display = $this->data['default_num_queue_tracks_to_display'];
		
		$items_to_retrieve = $default_num_queue_tracks_to_display;

		$queue_tracks_listed_so_far_session = 0;

		$allSessionVariables = Session::all();
		$this->firephp->log($allSessionVariables, "allSessionVariables");

		// if returning to the queue page from the a deeper page (for example, the create playlist dialog), then we want to pickup where we left off
		if (Session::has('queue_tracks_listed_so_far')) {

			$queue_tracks_listed_so_far_session = Session::get('queue_tracks_listed_so_far');

			$this->firephp->log($queue_tracks_listed_so_far_session, 'queue_tracks_listed_so_far_session');

			if ($queue_tracks_listed_so_far_session > $default_num_queue_tracks_to_display) {

				$default_num_queue_tracks_to_display = $queue_tracks_listed_so_far_session;
			}

		} else { // if the queue_tracks_listed_so_far session variable hasn't been set yet, then set it

			Session::put('queue_tracks_listed_so_far', $default_num_queue_tracks_to_display);
			$queue_tracks_listed_so_far_session = $default_num_queue_tracks_to_display;
		}

		$listed_so_far = $queue_tracks_listed_so_far_session;
		$this->data['listed_so_far'] = $listed_so_far;	

		// end lazy loading variables

		$this->firephp->log($items_to_retrieve, 'items_to_retrieve');
		$this->firephp->log($listed_so_far, 'listed_so_far');
		$this->firephp->log($default_num_queue_tracks_to_display, 'default_num_queue_tracks_to_display');

		$this->data['currently_playing_info_div_style'] = "";
		$this->data['current_state_message'] 		= "";

		// include the current track determination code that's common among home.php and queue.php
		include('partials/init_current_mpd_track_data.php');

	        $mpd_playlist_as_json = get_mpd_playlist_as_json($this->MPD, $configs, $this->firephp, 0, $items_to_retrieve, 0, $this->user);
                
		$this->data['current_track_playlist_index'] = (isset($current_track_id) ? $current_track_id : 0);

		$this->data['json_playlist'] = ""; 

		$this->data['tracks'] = array();

		if ($mpd_playlist_as_json != ''){

			$this->data['json_playlist'] = $mpd_playlist_as_json;

			//$this->firephp->log($mpd_playlist_as_json, 'mpd_playlist_as_json');

			$decoded_json_playlist = json_decode($mpd_playlist_as_json, true);

			$tracks = $decoded_json_playlist['tracks'];
			
			$this->firephp->log($tracks, 'tracks');
			//$this->firephp->log($decoded_json_playlist, 'decoded_json_playlist');

			$tracks_count = count($tracks);

			if ($tracks_count > $default_num_queue_tracks_to_display) {
				
				// if we have lazy loading enabled, then we need to set tracks_count to default_num_queue_tracks_to_display
				$tracks_count = $default_num_queue_tracks_to_display;
			}

			$this->firephp->log($tracks_count, 'tracks_count');

			for($i=0; $i<$tracks_count; $i++) {

				$track_ra = explode("/", $tracks[$i]['file']);

				$numbered_track_title = str_replace(".mp3", "", $track_ra[count($track_ra)-1]);

			  	if (strpos($tracks[$i]['file'], "http://") !== 0) {

					$tracks[$i]['title'] = $numbered_track_title;
				}

				$track_ra = explode("/", $tracks[$i]['url']);

				$symbolic_link_filename = $track_ra[count($track_ra)-1];
	
				$tracks[$i]['filename'] = $symbolic_link_filename;				
			}

			$this->firephp->log($tracks, "tracks");
	
			$this->data['tracks'] = $tracks;
		}

		$this->data['playlist_index'] = 0;

		// this is used by the scroll down button in the footer
		$this->data['section'] = "queue";

		return View::make('queue', $this->data);
	}

	public function more() {

		// If there is a callback specified then JSONP must be in use, which would mean it is GET data
		//$callback = Request::get('callback');
		//$this->firephp->log($callback, 'callback');

		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			$this->MPD->RefreshInfo();

	        /*if ( $callback != "" ) {

	        	// If using JSONP, then it must be GET
				$queue_tracks_listed_so_far = $this->input->get('retrieved');
				$queue_tracks_to_retrieve 	= $this->input->get('retrieve');
				$playlist_index_offset 		= $this->input->get('offset');

	        } else {*/

	        	// Traditional AJAX will use POST
			$queue_tracks_listed_so_far 	= Request::get('retrieved');
			$queue_tracks_to_retrieve 	= Request::get('retrieve');
			$playlist_index_offset 		= Request::get('offset');
			//}

			$taphold_then_drag_to_reorder = $this->data['taphold_then_drag_to_reorder_i18n'];

			$queue_tracks_count = 0;
			$queue_tracks_retrieved = 0;

			$this->firephp->log($queue_tracks_listed_so_far, 'queue_tracks_listed_so_far');
			$this->firephp->log($queue_tracks_to_retrieve, 'queue_tracks_to_retrieve');
			$this->firephp->log($playlist_index_offset, 'playlist_index_offset');

			$default_num_queue_tracks_to_display = $this->data['default_num_queue_tracks_to_display'];
			$this->firephp->log($default_num_queue_tracks_to_display, 'default_num_queue_tracks_to_display');

	                $allSessionVariables = Session::all();
        	        $this->firephp->log($allSessionVariables, "allSessionVariables");

			$queue_tracks_listed_so_far_session = Session::get('queue_tracks_listed_so_far');

			if ($queue_tracks_listed_so_far_session > $default_num_queue_tracks_to_display) {

				$default_num_queue_tracks_to_display = $queue_tracks_listed_so_far_session;
			}

			$queue_tracks_listed_so_far = $default_num_queue_tracks_to_display;

			$this->firephp->log($default_num_queue_tracks_to_display, 'default_num_queue_tracks_to_display');

			$queue_tracks 				= array();
			$queue_tracks_li_elements_ra 		= array();
			$queue_tracks_li_elements_html 		= "";
			$queue_tracks_li_elements_as_json	= "";

			$more_queue_tracks_json = "";

			$configs['music_dir'] 					= $this->data['music_dir'];
			$configs['art_dir'] 					= $this->data['art_dir'];
			$configs['document_root'] 				= $this->data['document_root'];
			$configs['default_no_album_art_image'] 			= $this->data['default_no_album_art_image'];
			$configs['queue_dir'] 		 			= $this->data['queue_dir'];
			$configs['base_protocol'] 	 			= $this->data['base_protocol'];
			$configs['base_domain'] 	 			= $this->data['base_domain'];

			$this->firephp->log($configs, "configs");

			$this->firephp->log($this->data, "this->data");

			// this is for the lazy loading of the queue
			$mpd_playlist_as_json = get_mpd_playlist_as_json($this->MPD, $configs, $this->firephp, $queue_tracks_listed_so_far, $queue_tracks_to_retrieve, $playlist_index_offset, $this->user);

			$json_playlist = ""; 

			$playlist_index = $queue_tracks_listed_so_far;

			$queue_tracks_retrieved = 0;

			if ($mpd_playlist_as_json != ''){

				$json_playlist = $mpd_playlist_as_json;

				//$this->firephp->log($mpd_playlist_as_json, 'mpd_playlist_as_json');

				$decoded_json_playlist = json_decode($mpd_playlist_as_json, true);

				$queue_tracks_retrieved = count($decoded_json_playlist['tracks']);

				//$this->firephp->log($decoded_json_playlist, 'decoded_json_playlist');

				foreach($decoded_json_playlist['tracks'] as $playlist_track) {
						
					$track_ra = explode("/", $playlist_track['file']);

					$numbered_track_title = str_replace(".mp3", "", $track_ra[ count( $track_ra )-1 ]);

			  		if (strpos($playlist_track['file'], "http://") !== 0) {

						$playlist_track['title'] = $numbered_track_title;
					}

	        			$symbolic_link_filename = sha1($track_ra[count($track_ra)-1]);

					//$queue_tracks_li_elements_html .= '<li class=\\"ui-li-has-thumb queued_track'.$playlist_index.'\\"><a href=\\"\\" data-icon=\\"none\\" onclick=\\"bump_track('.$playlist_index.');\\" class=\\"ui-link-inherit\\" title=\\"Bump to top of queue\\"><img src=\\"'.$playlist_track['art'].'\\" class=\\"ui-li-thumb album-art-img\\" /><h3 class=\\"ui-li-heading\\">'.str_replace('"', '\\"', $playlist_track['title']).'</h3></a><a href=\\"\\" data-icon=\\"delete\\" onclick=\\"remove_queued_track('.$playlist_index.', \''.urlencode(str_replace('"', '\\\\\\"', $playlist_track['title'])).'\', \''.$symbolic_link_filename.'\');\\" class=\\"ui-li-link-alt ui-btn ui-btn-up-'.$this->data['theme_buttons'].' remove_queued_track\\" data-theme=\\"'.$this->data['theme_buttons'].'\\" title=\\"Remove from queue\\">Remove from queue</a></li>';
					
					$queue_tracks_li_elements_as_json .= '{ "id":"queueTrack_'.$playlist_index.'", "playlist_index":"'.$playlist_index.'", "href" : "", "art":"'.$playlist_track['art'].'", "title":"'.str_replace('"', '\\"', $playlist_track['title']).'", "escaped_title":"'.str_replace("'", "\\\'", str_replace('"', '\\"', $playlist_track['title'])).'", "theme_buttons":"'.$this->data['theme_buttons'].'", "theme_icon_class":"'.$this->data['theme_icon_class'].'", "symbolic_link_filename":"'.$symbolic_link_filename.'", "time":"'.get_timer_display( $playlist_track['time'] ).'", "move_tooltip":"'.$taphold_then_drag_to_reorder.'" },';

					$playlist_index++;
				}
			}

			$queue_tracks_li_elements_as_json = rtrim($queue_tracks_li_elements_as_json, ",");

			//$more_queue_tracks_json = '{ "data" : [{ "count" : "'.$queue_tracks_retrieved.'", "html" : "' . $queue_tracks_li_elements_html . '" } ] }';
			$more_queue_tracks_json = '{ "data" : [{ "count" : "'.$queue_tracks_retrieved.'", "json" : [' . $queue_tracks_li_elements_as_json . '] } ] }';

		} else {

			$more_queue_tracks_json = '{ "data" : [{ "count" : "'.$$queue_tracks_retrieved.'", "html" : "" } ] }';
		}

		/*if ( $callback != "" ) {

			$more_queue_tracks_json = $callback . "(" . $more_queue_tracks_json . ")";
		}*/

		$this->firephp->log(($queue_tracks_listed_so_far + $queue_tracks_retrieved), "putting queue_tracks_listed_so_far");
		Session::put('queue_tracks_listed_so_far', ($queue_tracks_listed_so_far + $queue_tracks_retrieved));

		// echo out the HTML for the next set of artist li elements
		echo $more_queue_tracks_json;
	}
}
