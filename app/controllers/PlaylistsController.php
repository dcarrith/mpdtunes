<?php 

use Services\Playlists\Validation as PlaylistValidationService;

class PlaylistsController extends MPDTunesController {

	function __construct() {

        	parent::__construct();

                // Get and merge the playlists page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("playlists"));

                // Get and merge all the words we need for the Playlists controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("playlists"));        
	}

	public function index() {

		$show_playlist_track_count_bubbles = $this->data['show_playlist_track_count_bubbles'];

		$playlists = array();
		$this->data['playlists'] = array();
		$this->data['data_url'] = "";

		if ($this->xMPD->isConnected()) {

			$playlists = $this->xMPD->listplaylists();
			$this->firephp->log($playlists, "playlists");

			$this->firephp->log($show_playlist_track_count_bubbles, "show_playlist_track_count_bubbles");

			// only perform the extra overhead processing of getting the track counts if configured to show count bubbles
			if ($show_playlist_track_count_bubbles) {

				foreach($playlists as $playlist) {

					$tracks = $this->xMPD->listplaylist($playlist);

					$tracks_count = count($tracks);

					$this->data['playlists'][] = array_merge(array( "name" => $playlist ), array('tracks_count'=>$tracks_count));
				}

			} else {

				foreach($playlists as $playlist) {

					$this->data['playlists'][] = $playlist;
				}
			}

			$this->data['current_volume'] = $this->xMPD->volume;
			$this->data['current_xfade'] = $this->xMPD->xfade;
		}

                return View::make('playlists', $this->data);		
	}

	public function create() {

		$this->data['saved_successfully'] = '';

		// Show a success message 
		if (Session::get('success')) {
			
			$this->data['saved_successfully'] = true;
		}	

		return View::make('createPlaylist', $this->data);
	}

	public function postCreate() {

                $posted_playlist_name = trim( Input::get('playlist_name') );
		$this->firephp->log($posted_playlist_name, "posted_playlist_name");

                // Add the csrf before filter to guard against cross-site request forgery
                $this->beforeFilter('csrf');
 
		try {

			$validate = new PlaylistValidationService(Input::all());

			$validate->creation();

		} catch (ValidateException $errors) {
			
			return Redirect::to('playlist/create')->withErrors($errors->get())->withInput();
		}

		if (isset($posted_playlist_name)) {

			// Create a playlist containing the tracks currently in the queue and name it based on the posted playlist name		
			$this->xMPD->save($posted_playlist_name);

			Session::flash('success', true);
		}	

		return Redirect::to('/playlist/create', 303)->withInput();
	}
}
