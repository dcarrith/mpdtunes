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

		$this->data['data_url'] = "";
	
		$encodedPlaylistName = Request::segment(2);	

		$playlistName = urldecode(urldecode($encodedPlaylistName));

		$tracksListedSoFar = 0;

		$tracksToRetrieve = $this->data['default_num_playlist_tracks_to_display'];
		
		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			$playlistTracks = $this->getPlaylistTracks($playlistName, $tracksListedSoFar, $tracksToRetrieve, "playlist");
		}

		// How many items were retrieved
		$justRetrieved = $playlistTracks['count'];

		// We don't need the count in the json part of the array anymore
		unset($playlistTracks['count']);

		// Update the session variable that's tracking how many tracks have been listed so far
		Session::put('playlist_tracks_listed_so_far', ($tracksListedSoFar + $justRetrieved));
	
		$this->data['tracks'] 			= $playlistTracks;
		$this->data['tracksUlId']		= "playlistTracks";
		$this->data['tracksPageId']		= "playlistTracksPage";
		$this->data['popupMenuId'] 		= "playlistTrackPopupMenu";
		$this->data['dataNameAttribute']	= 'data-playlist-name="'.$playlistName.'"';
		$this->data['add_tracks_post_json'] 	= "{ 'parameters' : [ { 'source' : 'playlist', 'playlist_name' : '".$playlistName."' } ] }";

		//$this->firephp->log($this->data, "data");
	
		return View::make('playlistTracks', $this->data);
	}

	public function more() {

		$playlistName 		= Request::get('param_one');
		$tracksListedSoFar 	= Request::get('retrieved');
		$tracksToRetrieve 	= Request::get('retrieve');

		// This will be the final array to json_encode and return to the client
		$response = array('data' => array( 'count' => 0, 'html' => array()));

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			$response['data']['json'] = $this->getPlaylistTracks($playlistName, $tracksListedSoFar, $tracksToRetrieve, "playlist");
		}

		// How many items were retrieved
		$justRetrieved = $response['data']['json']['count'];

		// We don't need the count in the json part of the array anymore
		unset($response['data']['json']['count']);

		// This is where the lazyloader is expecting to find the count
		$response['data']['count'] = $justRetrieved;

		// Update the session variable that's tracking how many tracks have been listed so far
		Session::put('playlist_tracks_listed_so_far', ($tracksListedSoFar + $justRetrieved));
	
		// Echo out the JSON representation of the next set of li elements to display
		echo json_encode($response);
	}
}
