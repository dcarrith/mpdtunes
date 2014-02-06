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

		if ($this->xMPD->isConnected()) {

			$this->data['genres'] = $this->xMPD->list("genre");
		}

                $this->firephp->log($this->data, "data");

                return View::make('genres', $this->data);		
	}
}
