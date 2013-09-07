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
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			if (!isset($selected_genre)){

				// get the artists from mpd
				$artists = $this->MPD->GetArtists();

			} else {

				// need to double decode it in case the user is coming back to the page in history
				$selected_genre = urldecode(urldecode($selected_genre));

				$artists = $this->MPD->GetArtists($selected_genre);

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

			$this->firephp->log($show_album_count_bubbles, "show_album_count_bubbles");

			// only perform the extra overhead processing of getting the album counts if configured to show count bubbles
			if ($show_album_count_bubbles) {

				foreach($artists as $artist) {

					$album_count = 0;

					$this->firephp->log($artist, "artist");

					$albums = $this->MPD->GetAlbums($artist);

					$this->firephp->log($albums, "albums");

					$album_count = count($albums);

					$this->firephp->log($album_count, "album_count");

					$this->data['artists'][] = array('artist'=>$artist, 'album_count'=>$album_count);

					$artists_so_far++;

					if ($artists_so_far == $default_num_artists_to_display) {

						break;
					}
				}
			
			} else {

				foreach($artists as $artist) {

					$this->firephp->log($artist, "artist");

					$this->data['artists'][] = array('artist'=>$artist);

					$artists_so_far++;

					if ($artists_so_far == $default_num_artists_to_display) {

						break;
					}
				}
			}

			$this->firephp->log($this->data['artists'], "this->data['artists']");
		}

		// this is used by the scroll down button in the footer
		$this->data['section'] = Request::segment(1);

		$this->firephp->log($this->data, "data");
	
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

		$artists 			= array();
		$artists_li_elements_ra 	= array();
		$artists_li_elements_html 	= "";
		$artists_li_elements_as_json	= "";
		$more_artists_json 		= "";

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			if (!isset($selected_genre)){

				// get the artists from mpd
				$artists = $this->MPD->GetArtists();

			} else {

				// need to double decode it in case the user is coming back to the page in history
				$selected_genre = urldecode(urldecode($selected_genre));

				$artists = $this->MPD->GetArtists($selected_genre);

				$this->firephp->log($selected_genre, "selected_genre");

				$encoded_selected_genre = urlencode(urlencode($selected_genre));

				$this->firephp->log($encoded_selected_genre, "encoded_selected_genre");
			}

			$this->firephp->log($show_album_count_bubbles, "show_album_count_bubbles");

			// only perform the extra overhead processing of getting the album counts if configured to show count bubbles
			if ($show_album_count_bubbles) {

				foreach($artists as $artist) {

					if ($artists_count >= $artists_listed_so_far) {

						if (!$retrieving_the_rest) {

							if ($artists_count >= ($artists_to_retrieve + $artists_listed_so_far)) {

								break;
							}
						}

						$album_count = 0;

						$this->firephp->log($artist, "artist");

						$albums = $this->MPD->GetAlbums(addslashes($artist));

						$this->firephp->log($albums, "albums");

						$album_count = count($albums);

						$this->firephp->log($album_count, "album_count");

						//$artists_li_elements_html .= "<li><a href='artist/".urlencode(urlencode(str_replace('"', '\\"', $artist)))."/albums' data-transition='".$this->data['default_page_transition']."'>".str_replace('"', '\\"', $artist)."<span class='ui-li-count ui-btn-up-".$this->data['theme_buttons']." ui-btn-corner-all'>".$album_count."</span></a></li>";
						$artists_li_elements_as_json .= '{ "href" : "artist/'.urlencode(urlencode(str_replace('"', '\\"', $artist))).'/albums", "transition":"'.$this->data['default_page_transition'].'", "name":"'.str_replace('"', '\\"', $artist).'", "theme_buttons":"'.$this->data['theme_buttons'].'", "count_bubble_value":"'.$album_count.'" },';

						//$this->firephp->log($artists_li_elements_html, "artists_li_elements_html");

						$artists_retrieved++;
					}

					$artists_count++;
				}

				$artists_li_elements_as_json = rtrim($artists_li_elements_as_json, ",");
			
			} else {

				foreach($artists as $artist) {

					if ($artists_count >= $artists_listed_so_far) {

						if (!$retrieving_the_rest) {

							if ($artists_count >= ($artists_to_retrieve + $artists_listed_so_far)) {

								break;
							}
						}

						$this->firephp->log($artist, "artist");

						//$artists_li_elements_html .= "<li><a href='artist/".urlencode(urlencode(str_replace('"', '\\"', $artist)))."/albums' data-transition='".$this->data['default_page_transition']."'>".str_replace('"', '\\"', $artist)."</a></li>";
						$artists_li_elements_as_json .= '{ "href" : "artist/'.urlencode(urlencode(str_replace('"', '\\"', $artist))).'/albums", "transition":"'.$this->data['default_page_transition'].'", "name":"'.str_replace('"', '\\"', $artist).'", "theme_buttons":"'.$this->data['theme_buttons'].'" },';

						//$this->firephp->log($artists_li_elements_html, "artists_li_elements_html");

						$artists_retrieved++;
					}

					$artists_count++;
				}

				$artists_li_elements_as_json = rtrim($artists_li_elements_as_json, ",");
			}

			//$more_artists_json = '{ "data" : [{ "count" : "'.$artists_retrieved.'", "html" : "' . $artists_li_elements_html . '" } ] }';
			$more_artists_json = '{ "data" : [{ "count" : "'.$artists_retrieved.'", "json" : [' . $artists_li_elements_as_json . '] } ] }';

			//$more_artists_json = json_encode($artists_li_elements_ra);

		} else {

			//$artists_li_elements_ra['data'] = array("count"=>"0", "html"=>"");

			$more_artists_json = '{ "data" : [{ "count" : "'.$artists_retrieved.'", "html" : "" } ] }';

			//$more_artists_json = json_encode($artists_li_elements_ra);
		}

		Session::put('artists_listed_so_far', ($artists_listed_so_far + $artists_retrieved));

		// echo out the HTML for the next set of artist li elements
		echo $more_artists_json;
	}
}
