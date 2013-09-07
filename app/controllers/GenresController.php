<?php 

class GenresController extends MPDTunesController {

    	function __construct() {

        	parent::__construct();

                // Get and merge the tracks page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("genres"));
	}

	public function index() {

                // Get and merge all the words we need for the Genres controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("genres"));

		$this->data['genres'] = array();

		$this->data['data_url'] = "";

		$genres_artists = array();

		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			$this->data['genres'] = $this->MPD->GetGenres();
		}

                $this->firephp->log($this->data, "data");

                return View::make('genres', $this->data);		
	}
}
