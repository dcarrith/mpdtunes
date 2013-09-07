<?php 

class TracksController extends MPDTunesController {

	public function __construct() {

		parent::__construct();

                // Get and merge the tracks page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("tracks"));
	}

	public function index() {

		$data['site_title'] = $this->data['site_title'];

		$default_num_tracks_to_display = $this->data['default_num_tracks_to_display'];
		$tracks_so_far = 0;
		$this->data['tracks_so_far'] = $tracks_so_far;

                // Get and merge all the words we need for the Tracks controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("tracks"));
        	
		$this->data['data_url'] = "";

		$encoded_playlist_name 	= NULL;
		$encoded_artist_name 	= NULL;
		$encoded_album_name 	= NULL;

		if (Request::segment(1) == 'playlist') {

			$encoded_playlist_name 	= Request::segment(2);
			
		} else if ((Request::segment(1) == 'artist') && (Request::segment(3) == 'album')) {

			$encoded_artist_name 	= Request::segment(2);
			$encoded_album_name 	= Request::segment(4);
		
		} else {
			
			// they're already null	
		}

		if (isset($encoded_playlist_name) && ($encoded_playlist_name != '')) {

			// if returning to the artists page from a deeper page, then check to see if we need to show more artists
			if (Session::get('tracks_listed_so_far')) {

				$tracks_listed_so_far_session = Session::get('tracks_listed_so_far');

				if ($tracks_listed_so_far_session > $default_num_tracks_to_display) {

					$default_num_tracks_to_display = $tracks_listed_so_far_session;
				}

			} else { // if the artists_listed_so_far session variable hasn't been set yet, then set it

				Session::put('tracks_listed_so_far', $default_num_tracks_to_display);
			}

			$this->data['default_num_tracks_to_display'] = $default_num_tracks_to_display;
		}

		require_once('includes/php/library/art.inc.php');

		$this->data['heading_name'] = '';

		$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : '' } ] }";

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			$artist_name = $this->data['artist_name'];

			$configs = array();

			$configs['music_dir']			= $this->data['music_dir'];
			$configs['art_dir']			= $this->data['art_dir'];
			$configs['document_root']		= $this->data['document_root'];
			$configs['default_no_album_art_image']	= $this->data['default_no_album_art_image'];

			$this->firephp->log($configs, "configs");

			if (isset($encoded_album_name) && ($encoded_album_name != '')) {

				$artist_name = urldecode(urldecode($encoded_artist_name));
				$this->data['artist_name'] 		= $artist_name;
				$this->data['encoded_artist_name']	= $encoded_artist_name;

				$this->firephp->log($encoded_album_name, "encoded_album_name");

				$album_name = urldecode(urldecode($encoded_album_name));
				$this->data['album_name'] 		= $album_name;
				$this->data['encoded_album_name']	= $encoded_album_name;

				// Adding addslashes around the artist_name and album_name because single quotes in the album name were causing a javascript error 
				$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : 'album', 'artist_name' : '".addslashes( $artist_name )."', 'album_name' : '".addslashes( $album_name )."' } ] }";

				$this->data['heading_name'] 		= $album_name;

				$first_song = $this->MPD->GetOneTrack("album", $album_name);

			    	$this->data['album_art_file'] = Request::root()."/".get_album_art(	$first_song, 
													$artist_name, 
													$album_name, 
													$configs,
													$this->firephp	);

				$this->firephp->log($album_name, "album_name");

				$tracks = $this->MPD->GetTracks("album", $album_name);

				$this->firephp->log($tracks, "tracks");

				$this->data['tracks'] 	= $tracks;

				$track_filenames 	= array();

				$tracks_count 		= count($tracks);

				// let's pass this to the view so we don't have to count twice
				$this->data['tracks_count'] = $tracks_count;

				$total_length = 0;

				for($i=0; $i<$tracks_count; $i++){
					
					$track_filenames[] = $tracks[$i][1];

					$total_length += $tracks[$i][2];
				}

				$this->data['total_length'] 		= get_timer_display($total_length);
				$this->data['tracksUlId'] 		= "albumTracks";
				$this->data['tracksPageId'] 		= "albumTracksPage";
				$this->data['popupMenuId'] 		= "albumTrackPopupMenu";
				$this->data['dataNameAttribute'] 	= 'data-album-name="'.$$album_name.'"';

				$playlists = $this->MPD->GetPlaylists();

				foreach($playlists as $playlist) {

					$this->data['playlists'][]	= array('playlist'=>$playlist);

					$this->firephp->log($playlist, "playlist");
				}

				//$this->data['serialized_tracks'] = urlencode(serialize($track_filenames));

			} else if (isset($encoded_playlist_name) && ($encoded_playlist_name != '')) {

				$playlist_name = urldecode(urldecode($encoded_playlist_name));

				$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : 'playlist', 'playlist_name' : '".$playlist_name."' } ] }";

				$this->data['playlist_name'] 		= $playlist_name;
				$this->data['encoded_playlist_name'] 	= $encoded_playlist_name;
				$this->data['heading_name'] 		= $playlist_name;

				$tracks = $this->MPD->GetTracks("playlist", $playlist_name);

				$this->firephp->log($tracks, "tracks");
				
				$track_filenames 	= array();
				$tracks_count 		= count($tracks);

				if ($tracks_count > $default_num_tracks_to_display) {
				
					// if we have lazy loading enabled, then we need to set albums_count to default_num_albums_to_display
					$tracks_count = $default_num_tracks_to_display;
				}

				// let's pass this to the view so we don't have to count twice
				$this->data['tracks_count'] = $tracks_count;

				for($i=0; $i<$tracks_count; $i++){

					$this->firephp->log($tracks[$i], "track");

					$trackra = explode("/", $tracks[$i][1]);

					$this->firephp->log($trackra, "trackra");

					$song = $tracks[$i][1];

					// store an array of the songs in the playlist to serialize them later
					$track_filenames[] = $song;

					$artist_name = $trackra[0];
					$album_name = $trackra[1];
				    	$album_art_file = Request::root()."/".get_album_art	(	$song, 
													$artist_name, 
													$album_name, 
													$configs,
													$this->firephp	);

					$tracks[$i][2] = 0;
					$tracks[$i][3] = $album_art_file;
				}

				$this->data['tracks'] = $tracks;

				$this->data['tracksUlId']		= "playlistTracks";
				$this->data['tracksPageId']		= "playlistTracksPage";
				$this->data['popupMenuId'] 		= "playlistTrackPopupMenu";
				$this->data['dataNameAttribute']	= 'data-playlist-name="'.$playlist_name.'"';

				//$this->data['serialized_tracks'] = urlencode(serialize($track_filenames));

			} else {
				
				// what else?
			}
		}

		$this->firephp->log($this->data, "data");
	
		return View::make('tracks', $this->data);
	}

	public function more() {

		$playlist_name = $this->input->post('param_one');
		$this->firephp->log($playlist_name, "playlist_name");

		$encoded_playlist_name = urlencode($playlist_name);
		
		$add_this_song_to_queue_i18n = $this->lang->line('add_this_song_to_queue');
		$taphold_then_drag_to_reorder_i18n = $this->lang->line('taphold_then_drag_to_reorder');

		$tracks_listed_so_far = $this->input->post('retrieved');
		$tracks_to_retrieve = $this->input->post('retrieve');
		$tracks_count = 0;
		$tracks_retrieved = 0;

		$retrieving_the_rest = false;

		if ($artists_to_retrieve == "all") {
			$retrieving_the_rest = true;
		}

		$this->firephp->log($tracks_listed_so_far, "tracks_listed_so_far");
		$this->firephp->log($tracks_to_retrieve, "tracks_to_retrieve");

		$this->load->config();

		$default_num_tracks_to_display = $this->data['default_num_tracks_to_display'];

		$tracks_listed_so_far_session = Session::get('tracks_listed_so_far');

		if ($tracks_listed_so_far_session > $default_num_tracks_to_display) {

			$default_num_tracks_to_display = $tracks_listed_so_far_session;
		}

		$tracks 			= array();
		$tracks_li_elements_ra 		= array();
		$tracks_li_elements_html 	= "";
		$tracks_li_elements_as_json	= "";

		$more_tracks_json = "";

		require_once('includes/php/library/art.inc.php');

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			$configs = array();

                        $configs['music_dir']                   = $this->data['music_dir'];
                        $configs['art_dir']                     = $this->data['art_dir'];
                        $configs['document_root']               = $this->data['document_root'];
                        $configs['default_no_album_art_image']  = $this->data['default_no_album_art_image'];

			$playlist_name = urldecode(urldecode($encoded_playlist_name));
			$this->firephp->log($playlist_name, "playlist_name");

			$this->data['playlist_name'] 		= $playlist_name;
			$this->data['encoded_playlist_name'] 	= $encoded_playlist_name;

			$this->data['heading_name'] 		= $playlist_name;

			$tracks = $this->MPD->GetTracks("playlist", $playlist_name);

			//$this->firephp->log($tracks, "tracks");
			
			$track_filenames 		= array();

			$playlist_tracks_count 	= count($tracks);
			$this->firephp->log($playlist_tracks_count, "playlist_tracks_count");

			// let's pass this to the view so we don't have to count twice
			$this->data['tracks_count'] = $tracks_count;

			for($i=0; $i<$playlist_tracks_count; $i++){

				if ($tracks_count >= $tracks_listed_so_far) {

					$this->firephp->log($tracks_to_retrieve, "tracks_to_retrieve");

					if (!$retrieving_the_rest) {

						if ($tracks_count >= ($tracks_to_retrieve + $tracks_listed_so_far)) {

							break;
						}
					}

					$this->firephp->log($tracks[$i], "track");

					$trackra = explode("/", $tracks[$i][1]);

					$this->firephp->log($trackra, "trackra");

					$song = $tracks[$i][1];

					// store an array of the songs in the playlist to serialize them later
					$track_filenames[] = $song;

					$artist_name = $trackra[0];
					$album_name = $trackra[1];

					$track_name = $tracks[$i][0];

				    	$album_art_file	= Request::root()."/".get_album_art(	$song, 
												$artist_name, 
												$album_name, 
												$configs	);
					
					$track_length = get_timer_display(0);

					$tracks_li_elements_as_json .= '{ "id":"playlistTrack_'.$i.'", "index":"'.$i.'", "href":"#playlistTrackPopupMenu", "file":"'.str_replace('"', '\\"', $song).'", "art":"'.$album_art_file.'", "name":"'.str_replace('"', '\\"', $track_name).'", "theme_buttons":"'.$this->data['theme_buttons'].'", "theme_icon_class":"'.$this->data['theme_icon_class'].'", "title":"'.$taphold_then_drag_to_reorder_i18n.'", "length":"'.$track_length.'" },';

					$tracks_retrieved++;
				}

				$tracks_count++;
			}

			$tracks_li_elements_as_json = rtrim($tracks_li_elements_as_json, ",");

			$this->data['tracks'] = $tracks;

			$this->data['serialized_tracks'] = urlencode(serialize($track_filenames));
	
			$more_tracks_json = '{ "data" : [{ "count" : "'.$tracks_retrieved.'", "json" : [' . $tracks_li_elements_as_json . '] } ] }';

		} else {

			$more_tracks_json = '{ "data" : [{ "count" : "'.$tracks_retrieved.'", "html" : "" } ] }';
		}

		$this->session->set_userdata('tracks_listed_so_far', ($tracks_listed_so_far + $tracks_retrieved));

		// echo out the HTML for the next set of artist li elements
		echo $more_tracks_json;
	}
}
