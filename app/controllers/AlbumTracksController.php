<?php 

class AlbumTracksController extends MPDTunesController {

	public function __construct() {

		parent::__construct();

                // Get and merge the tracks page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("tracks"));
	}

	public function index() {

                // Get and merge all the words we need for the Tracks controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("tracks"));
        	
		$this->data['data_url'] = "";

		$encoded_artist_name = Request::segment(2);
		$encoded_album_name = Request::segment(4);

		$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : '' } ] }";

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			//$artist_name = $this->data['artist_name'];

			$artist_name = urldecode(urldecode($encoded_artist_name));
			$this->data['artist_name'] 		= $artist_name;
			$this->data['encoded_artist_name']	= $encoded_artist_name;

			$album_name = urldecode(urldecode($encoded_album_name));
			$this->data['album_name'] 		= $album_name;
			$this->data['encoded_album_name']	= $encoded_album_name;

			// Adding addslashes around the artist_name and album_name because single quotes in the album name were causing a javascript error 
			$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'source' : 'album', 'artist_name' : '".addslashes( $artist_name )."', 'album_name' : '".addslashes( $album_name )."' } ] }";

			$this->data['heading_name'] 		= $album_name;

			$first_song = $this->xMPD->getFirstTrack("album", $album_name);

			$this->data['album_art_file'] = Request::root()."/".$this->getAlbumArt(	$first_song, 
												$artist_name, 
												$album_name	);

			$tracks = $this->xMPD->find("album", $album_name);

			$this->data['tracks'] = $tracks;

			//$track_filenames = array_column( $tracks, "file" );

			$total_length = array_reduce( array_column( $tracks, "Time" ), function($a, $b) {
				
				return ($a += $b);
			});

			$this->data['total_length'] 		= get_timer_display($total_length);
			$this->data['tracksUlId'] 		= "albumTracks";
			$this->data['tracksPageId'] 		= "albumTracksPage";
			$this->data['popupMenuId'] 		= "albumTrackPopupMenu";
			$this->data['dataNameAttribute'] 	= 'data-album-name="'.$album_name.'"';

			$playlists = $this->xMPD->listplaylists();
			$this->firephp->log($playlists, "playlists");

			$this->data['playlists'] = $playlists;
		}

		$this->firephp->log($this->data, "data");
	
		return View::make('albumTracks', $this->data);
	}
}
