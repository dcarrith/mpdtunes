<?php 

class AlbumTracksController extends MPDTunesController {

	public function __construct() {

		parent::__construct();

                // Get and merge the tracks page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("tracks"));
	}

	public function index() {

		$default_num_tracks_to_display = $this->data['default_num_tracks_to_display'];
		$tracks_so_far = 0;
		$this->data['tracks_so_far'] = $tracks_so_far;

                // Get and merge all the words we need for the Tracks controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("tracks"));
        	
		$this->data['data_url'] = "";

		$encoded_artist_name 	= Request::segment(2);
		$encoded_album_name 	= Request::segment(4);

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

			$this->firephp->log($configs, "configs");

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

			$this->data['album_art_file'] = Request::root()."/".get_album_art	(	$first_song, 
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
			$this->data['dataNameAttribute'] 	= 'data-album-name="'.$album_name.'"';

			$playlists = $this->MPD->GetPlaylists();

			foreach($playlists as $playlist) {

				$this->data['playlists'][]	= array('playlist'=>$playlist);

				$this->firephp->log($playlist, "playlist");
			}

			//$this->data['serialized_tracks'] = urlencode(serialize($track_filenames));
		}

		$this->firephp->log($this->data, "data");
	
		return View::make('albumTracks', $this->data);
	}
}
