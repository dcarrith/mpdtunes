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
		$defaultNumQueueTracksToDisplay = $this->data['default_num_queue_tracks_to_display'];
	
		// This will be zero for the index page
		$queueTracksListedSoFar = 0;

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			$playlistTracks = $this->getPlaylistTracks("current", $queueTracksListedSoFar, $defaultNumQueueTracksToDisplay, "queue");
		}

		// How many items were retrieved
		$justRetrieved = $playlistTracks['count'];

		// We don't need the count in the json part of the array anymore
		unset($playlistTracks['count']);

		// Update the session variable that's tracking how many tracks have been listed so far
		Session::put('queue_tracks_listed_so_far', ($queueTracksListedSoFar + $justRetrieved));
	
		$this->data['tracks'] = $playlistTracks;
		
		// this is used by the scroll down button in the footer
		$this->data['section'] = "queue";

		return View::make('queue', $this->data);
	}

	public function more() {

		// Use current as the name of the actively playing playlist
		$playlistName 		= "current";
		$tracksListedSoFar 	= Request::get('retrieved');
		$tracksToRetrieve 	= Request::get('retrieve');

		// This will be the final array to json_encode and return to the client
		$response = array('data' => array( 'count' => 0, 'html' => array()));

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			$response['data']['json'] = $this->getPlaylistTracks($playlistName, $tracksListedSoFar, $tracksToRetrieve, "queue");
		}

		// How many items were retrieved
		$justRetrieved = $response['data']['json']['count'];

		// We don't need the count in the json part of the array anymore
		unset($response['data']['json']['count']);

		// This is where the lazyloader is expecting to find the count
		$response['data']['count'] = $justRetrieved;

		// Update the session variable that's tracking how many tracks have been listed so far
		Session::put('queue_tracks_listed_so_far', ($tracksListedSoFar + $justRetrieved));
	
		// Echo out the JSON representation of the next set of li elements to display
		echo json_encode($response);
	}
}
