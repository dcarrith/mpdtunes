<?php 

class ArtistsController extends MPDTunesController {

	public function index() {

		$this->data['site_title'] = Config::get('defaults.base_site_title') . " - Artists";
		$show_album_count_bubbles = Config::get('defaults.show_album_count_bubbles');
		$this->data['show_album_count_bubbles'] = $show_album_count_bubbles;

		$default_num_artists_to_display = Config::get('defaults.default_num_artists_to_display');
		$artists_so_far = 0;

		// if returning to the artists page from a deeper page, then check to see if we need to show more artists
		if (Session::get('artists_listed_so_far')) {

			$artists_listed_so_far_session = Session::get('artists_listed_so_far');

			$this->firephp->log($artists_listed_so_far_session, "artists_listed_so_far_session");

			if ($artists_listed_so_far_session > $default_num_artists_to_display) {

				$default_num_artists_to_display = $artists_listed_so_far_session;
				$this->firephp->log($default_num_artists_to_display, "default_num_artists_to_display");
			}

		} else { // if the artists_listed_so_far session variable hasn't been set yet, then set it

			Session::put('artists_listed_so_far', $default_num_artists_to_display);
			$this->firephp->log(Session::get('artists_listed_so_far'), "Session::get('artists_listed_so_far')");
		}

                // Get and merge all the words we need for the Artists controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("artists"));

		$artists 			= array();
		$this->data['artists'] 		= array();

		$this->data['data_url'] 	= "";

		$selected_genre 		= NULL;
		$this->data['selected_genre'] 	= "";

		if (Request::segment(1) == 'genre') {
		
			$selected_genre 		= Request::segment(2);
			$this->data['selected_genre'] 	= $selected_genre;
		}

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			if (!isset($selected_genre)){

				// get the artists from mpd
				$artists = $this->xMPD->list("artist");

				//$this->firephp->log($artists, "artists");

			} else {

				// need to double decode it in case the user is coming back to the page in history
				$selected_genre = urldecode(urldecode($selected_genre));

				$artists = $this->xMPD->list("artist", "genre", $selected_genre);

				$this->firephp->log($selected_genre, "selected_genre");

				// we need to triple url encode some / & # , " [ ] etc
				/*$encoded_selected_genre = str_replace(	array('%2F', '%22', '%23', '%26', '%27', '%28', '%29', '%2C'),
														array('%25252F', '%252522', '%252523', '%252526', '%252527', '%252528', '%252529', '%25252C'),
														urlencode($selected_genre));*/

				$encoded_selected_genre = urlencode(urlencode($selected_genre));

				$this->firephp->log($encoded_selected_genre, "encoded_selected_genre");

				$this->data['data_url'] = 'data-url="/genre/'.$encoded_selected_genre.'/artists"';
				//$this->data['data_url'] = 'data-url="/genre/'.$selected_genre.'/artists"';
			}

			// only perform the extra overhead processing of getting the album counts if configured to show count bubbles
			if ($show_album_count_bubbles) {

				foreach($artists as $index => $artist) {

					//$this->firephp->log($artist, "artist");

					$albums = $this->xMPD->list("album", $artist);

					$this->data['artists'][] = array('artist'=>$artist, 'album_count'=>count($albums));

					if (($index + 1) == $default_num_artists_to_display) {

						break;
					}
				}
			
			} else {

				foreach($artists as $index => $artist) {

					$this->data['artists'][] = array('artist'=>$artist);

					if (($index + 1) == $default_num_artists_to_display) {

						break;
					}
				}
			}
		}

		// this is used by the scroll down button in the footer
		$this->data['section'] = Request::segment(1);
	
		return View::make('artists', $this->data);
	}

	public function more() {

		$artists_listed_so_far 	= Request::get('retrieved');
		$artists_to_retrieve 	= Request::get('retrieve');
		$artists_count = 0;
		$artists_retrieved = 0;

		$retrieving_the_rest = false;

		if ($artists_to_retrieve == "all") {
			$retrieving_the_rest = true;
		}

		// if there is a first_param being passed with artists, then it must be the selected genre
		$selected_genre = Request::get('param_one');

		if ($selected_genre == '') {

			unset($selected_genre);
		}

		$this->firephp->log($artists_listed_so_far, "artists_listed_so_far");
		$this->firephp->log($artists_to_retrieve, "artists_to_retrieve");

		$show_album_count_bubbles = $this->data['show_album_count_bubbles'];

		$default_num_artists_to_display = $this->data['default_num_artists_to_display'];

		$artists_listed_so_far_session = Session::get('artists_listed_so_far');

		if ($artists_listed_so_far_session > $default_num_artists_to_display) {

			$default_num_artists_to_display = $artists_listed_so_far_session;
		}

		// This will be the final array to json_encode and return to the client
		$response = array('data' => array( 'count' => 0, 'html' => array()));

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			if (!isset($selected_genre)){

				// get the artists from mpd
				$artists = $this->xMPD->list("artist");

			} else {

				// need to double decode it in case the user is coming back to the page in history
				$selected_genre = urldecode(urldecode($selected_genre));

				$artists = $this->xMPD->list("artist", "genre", $selected_genre);

				$encoded_selected_genre = urlencode(urlencode($selected_genre));
			}

			// only perform the extra overhead processing of getting the album counts if configured to show count bubbles
			if ($show_album_count_bubbles) {

				foreach($artists as $index => $artist) {

					//$this->firephp->log($artist, "artist");

					if ($index >= $artists_listed_so_far) {

						if (!$retrieving_the_rest) {

							if ($index >= ($artists_to_retrieve + $artists_listed_so_far)) {

								break;
							}
						}

						$albums = $this->xMPD->list("album", $artist);

						$artistUrl = 'artist/'.urlencode(urlencode($artist)).'/albums';

						$response['data']['json'][] = array( 	'href' => $artistUrl,
											'transition' => $this->data['default_page_transition'],
											'name' => $artist, 
											'theme_buttons' => $this->data['theme_buttons'],
											'count_bubble_value' => count($albums)	);

						$artists_retrieved++;
					}
				}
			
			} else {

				foreach($artists as $index => $artist) {

					if ($index >= $artists_listed_so_far) {

						if (!$retrieving_the_rest) {

							if ($index >= ($artists_to_retrieve + $artists_listed_so_far)) {

								break;
							}
						}

						$artistUrl = 'artist/'.urlencode(urlencode($artist)).'/albums';

						$response['data']['json'][] = array( 	'href' => $artistUrl,
											'transition' => $this->data['default_page_transition'],
											'name' => $artist, 
											'theme_buttons' => $this->data['theme_buttons']	);

						$artists_retrieved++;
					}
				}
			}
		} 

		$response['data']['count'] = $artists_retrieved;

		Session::put('artists_listed_so_far', ($artists_listed_so_far + $artists_retrieved));

		// Echo out the JSON representation of the next set of li elements to display
		echo json_encode($response);
	}
}
