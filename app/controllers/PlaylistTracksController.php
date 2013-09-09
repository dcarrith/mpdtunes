<?php 

class PlaylistTracksController extends MPDTunesController {

	public function __construct() {

		parent::__construct();

                // Get and merge the tracks page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("tracks"));
	
                // Get and merge all the words we need for the Tracks controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("tracks"));
	}

	public function index() {

		$default_num_tracks_to_display = $this->data['default_num_tracks_to_display'];
		$tracks_so_far = 0;
		$this->data['tracks_so_far'] = $tracks_so_far;
	
		$this->data['data_url'] = "";

		$encoded_playlist_name 	= Request::segment(2);

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

		require_once('includes/php/library/art.inc.php');

		$this->data['heading_name'] = '';

		$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : '' } ] }";

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			//$artist_name = $this->data['artist_name'];

			$configs = array();

			$configs['music_dir']			= $this->data['music_dir'];
			$configs['art_dir']			= $this->data['art_dir'];
			$configs['document_root']		= $this->data['document_root'];
			$configs['default_no_album_art_image']	= $this->data['default_no_album_art_image'];

			$playlist_name = urldecode(urldecode($encoded_playlist_name));

			$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : 'playlist', 'playlist_name' : '".$playlist_name."' } ] }";

			$this->data['playlist_name'] 		= $playlist_name;
			$this->data['encoded_playlist_name'] 	= $encoded_playlist_name;
			$this->data['heading_name'] 		= $playlist_name;

			$playlistTracks = $this->MPD->GetTracks("playlist", $playlist_name);

			$this->firephp->log($playlistTracks, "playlistTracks");
				
			$track_filenames 	= array();
			$tracks_count 		= count($playlistTracks);

			if ($tracks_count > $default_num_tracks_to_display) {
				
				// if we have lazy loading enabled, then we need to set albums_count to default_num_albums_to_display
				$tracks_count = $default_num_tracks_to_display;
			}

			// let's pass this to the view so we don't have to count twice
			$this->data['tracks_count'] = $tracks_count;

			$tracks = array();

			for($i=0; $i<$tracks_count; $i++){

				$this->firephp->log($playlistTracks[$i], "playlistTrack");

			  	if (strpos($playlistTracks[$i][1], "http://") === 0) {				

					// We need to get the station details and populate the tracks data for showing as a playlist track	
					$tracks[$i][0] = $playlistTracks[$i][0];
					$tracks[$i][1] = $playlistTracks[$i][1];
					$tracks[$i][2] = 0;
					
					$station = DB::table('stations')->where('url_hash', hash('sha512', $tracks[$i][1]))->where('creator_id', Auth::user()->id)->first();
					$tracks[$i][0] = $station->name;

					$stationsIcon = StationsIcon::find($station->icon_id);
				
					$album_art_file = URL::to( $stationsIcon->baseurl . $stationsIcon->filename );
	
					$tracks[$i][3] = $album_art_file;

				} else {

					$trackra = explode("/", $playlistTracks[$i][1]);
	
					$this->firephp->log($trackra, "trackra");
				
					$tracks[$i][0] = $playlistTracks[$i][0];
					$tracks[$i][1] = $playlistTracks[$i][1];
					
					$song = $playlistTracks[$i][1];

					$artist_name = $trackra[0];
					$album_name = $trackra[1];

				
			
					$album_art_file = Request::root()."/".get_album_art	(	$song, 
													$artist_name, 
													$album_name, 
													$configs	);

					$tracks[$i][2] = 0;
					$tracks[$i][3] = $album_art_file;
				}
			}

			$this->data['tracks'] = $tracks;

			$this->data['tracksUlId']		= "playlistTracks";
			$this->data['tracksPageId']		= "playlistTracksPage";
			$this->data['popupMenuId'] 		= "playlistTrackPopupMenu";
			$this->data['dataNameAttribute']	= 'data-playlist-name="'.$playlist_name.'"';
		}

		$this->firephp->log($this->data, "data");
	
		return View::make('playlistTracks', $this->data);
	}

	public function more() {

		$playlist_name = Request::get('param_one');
		$this->firephp->log($playlist_name, "playlist_name");

		$encoded_playlist_name = urlencode($playlist_name);
		
		$tracks_listed_so_far = Request::get('retrieved');
		$tracks_to_retrieve = Request::get('retrieve');
		$tracks_count = 0;
		$tracks_retrieved = 0;

		$retrieving_the_rest = false;

		if ($tracks_to_retrieve == "all") {
			$retrieving_the_rest = true;
		}

		$this->firephp->log($tracks_listed_so_far, "tracks_listed_so_far");
		$this->firephp->log($tracks_to_retrieve, "tracks_to_retrieve");

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
			
			$track_filenames = array();

			$playlist_tracks_count = count($tracks);
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

					$tracks_li_elements_as_json .= '{ "id":"playlistTrack_'.$i.'", "index":"'.$i.'", "href":"#playlistTrackPopupMenu", "file":"'.str_replace('"', '\\"', $song).'", "art":"'.$album_art_file.'", "name":"'.str_replace('"', '\\"', $track_name).'", "theme_buttons":"'.$this->data['theme_buttons'].'", "theme_icon_class":"'.$this->data['theme_icon_class'].'", "title":"'.$this->data['taphold_then_drag_to_reorder_i18n'].'", "length":"'.$track_length.'" },';

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

		Session::put('tracks_listed_so_far', ($tracks_listed_so_far + $tracks_retrieved));

		// echo out the HTML for the next set of artist li elements
		echo $more_tracks_json;
	}
}
