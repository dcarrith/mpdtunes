<?php 

class AlbumsController extends MPDTunesController {

	public function index() {

		$this->data['site_title'] = Config::get('site.base_site_title') . " - Albums";

		$show_album_track_count_bubbles = Config::get('defaults.show_album_track_count_bubbles');
		$this->data['show_album_track_count_bubbles'] = $show_album_track_count_bubbles;

		$default_num_albums_to_display = Config::get('defaults.default_num_albums_to_display');
		$albums_so_far = 0;
		$this->data['albums_so_far'] = $albums_so_far;

		// if returning to the artists page from a deeper page, then check to see if we need to show more artists
		if (Session::get('albums_listed_so_far')) {

			$albums_listed_so_far_session = Session::get('albums_listed_so_far');

			if ($albums_listed_so_far_session > $default_num_albums_to_display) {

				$default_num_albums_to_display = $albums_listed_so_far_session;
			}

		} else { // if the artists_listed_so_far session variable hasn't been set yet, then set it

		 	Session::put('albums_listed_so_far', $default_num_albums_to_display);
		}

		$this->data['default_num_albums_to_display'] = $default_num_albums_to_display;

                // Get and merge all the words we need for the Albums controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("albums"));

		$this->data['data_url'] = "";

		$encoded_artist_name = NULL;

		if (Request::segment(1) == 'artist') {

			$encoded_artist_name = Request::segment(2);

			$this->data['data_url'] = 'data-url="/artist/'.$encoded_artist_name.'/albums"';
			
		} else if ((Request::segment(1) == 'genre') && (Request::segment(3) == 'artist')) {

			$encoded_artist_name = Request::segment(4);

			$encoded_genre_name = urlencode(Request::segment(2));

			$this->data['data_url'] = 'data-url="/genre/'.$encoded_genre_name.'/artist/'.$encoded_artist_name.'/albums"';
		
		} else {
			
			$encoded_artist_name = NULL;	
		}

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			//$artist_name = $this->data['artist_name'];

			if (isset($encoded_artist_name) && ($encoded_artist_name != '')) {

				$artist_name = urldecode(urldecode($encoded_artist_name));
				$this->data['artist_name'] 		= $artist_name;
				$this->data['encoded_artist_name'] 	= $encoded_artist_name;

				// Adding addslashes around the artist_name because single quotes in the album name were causing a javascript error 
				$this->data['add_tracks_post_json'] = "{ 'parameters' : [ { 'artist_name' : '".addslashes( $artist_name )."' } ] }";

				// we need to double decode it here in case the user is going back in history (data-url is double encoded)
				$albums = $this->xMPD->list("album", stripslashes($artist_name));

				// we want the list of albums to be sorted alphabetically by name
				sort($albums, SORT_STRING);

				$albums_count = count($albums);

				if ($albums_count > $default_num_albums_to_display) {
				
					// if we have lazy loading enabled, then we need to set albums_count to default_num_albums_to_display
					$albums_count = $default_num_albums_to_display;
				}

				// let's pass this to the view so we don't have to count twice
				$this->data['albums_count'] = $albums_count;
				
				// Iterate through the array of albums and make the necessary adjustments to each element
				foreach( $albums as $index => $album ) {

					// Get the first track of the album so we can get the album art
					$firstTrack = $this->xMPD->getFirstTrack("album", $album);
		
					// We need to double url encode the album name since it can contain some special characters
					$encodedAlbumName = urlencode( urlencode( $album ));
					
					// Get the album art from the first track of the album and then trim off the front forward slash
					$albumArtFile = ltrim( $this->getAlbumArt($firstTrack, $artist_name, $album), "/" );
				
					// only perform the extra overhead processing of getting the track counts if configured to show count bubbles
					if ($show_album_track_count_bubbles) {

						$tracks = $this->xMPD->find("album", $album);

						$totalLength = get_timer_display( array_reduce( array_column( $tracks, "Time" ), function($a, $b) {
							return $a += $b;
						}));

						$this->data['albums'][] = array(	"album_name"		=> $album, 
											"encoded_album_name"	=> $encodedAlbumName, 
											"album_art"		=> $albumArtFile,
 											"total_length"		=> $totalLength,
											"track_count"		=> count($tracks)	);
 

					} else {

						$this->data['albums'][] = array(	"album_name"		=> $album, 
											"encoded_album_name"	=> $encodedAlbumName, 
											"album_art"		=> $albumArtFile	);
					}

					// We only want to show the first 20 or so albums. The rest will be lazyloaded.
					if (($index + 1) == $albums_count) { break; }
				}
			}
		}

                // this is used by the scroll down button in the footer
                $this->data['section'] = Request::segment(3);

                return View::make('albums', $this->data);
	}

	public function more() {

		$artist_name 		= Request::get('param_one');
		$albums_listed_so_far 	= Request::get('retrieved');
		$albums_to_retrieve 	= Request::get('retrieve');
		$albums_count = 0;
		$albums_retrieved = 0;

		$retrieving_the_rest = false;

		if ($albums_to_retrieve == "all") {
			$retrieving_the_rest = true;
		}

		$this->firephp->log($albums_listed_so_far, "albums_listed_so_far");
		$this->firephp->log($albums_to_retrieve, "albums_to_retrieve");
		$this->firephp->log($artist_name, "artist_name");

		$show_album_track_count_bubbles = $this->data['show_album_track_count_bubbles'];
		$default_num_albums_to_display = $this->data['default_num_albums_to_display'];

		$albums_listed_so_far_session = Session::get('albums_listed_so_far');

		if ($albums_listed_so_far_session > $default_num_albums_to_display) {

			$default_num_albums_to_display = $albums_listed_so_far_session;
		}

		// This will be the final array to json_encode and return to the client
		$response = array('data' => array( 'count' => 0, 'html' => array()));

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if ($this->xMPD->isConnected()) {

			if (isset($artist_name) && ($artist_name != '')) {

				$albums = $this->xMPD->list("album", $artist_name);

				// we want the list of albums to be sorted alphabetically by name
				sort($albums, SORT_STRING);

				$artist_albums_count = count($albums);

				$this->firephp->log($artist_albums_count, "artist_albums_count");

				// Iterate through the array of albums and make the necessary adjustments to each element
				foreach( $albums as $index => $album ) {

					// We want to start the list of albums from where we left off
					if ($index >= $albums_listed_so_far) {

						if (!$retrieving_the_rest) {

							if ($index >= ($albums_to_retrieve + $albums_listed_so_far)) {

								break;
							}
						}

						// Get the first track of the album so we can get the album art
						$firstTrack = $this->xMPD->getFirstTrack("album", $album);
		
						// We need to double url encode the album name since it can contain some special characters
						$encodedAlbumName = urlencode( urlencode( $album ));
					
						// Get the album art from the first track of the album and then trim off the front forward slash
						$albumArtFile = ltrim( $this->getAlbumArt($firstTrack, $artist_name, $album), "/" );

						// Let's double url encode the artist name so it doesn't choke up JQM
						$dueArtistName = urlencode(urlencode(str_replace('"', '\\"', $artist_name)));
 
						// Let's concatenate the URL to use for the li a element's href attribute
						$albumUrl = '/artist/'.$dueArtistName.'/album/'.str_replace('"', '\\"', $encodedAlbumName).'/tracks'; 
				
						// only perform the extra overhead processing of getting the track counts if configured to show count bubbles
						if ($show_album_track_count_bubbles) {

							$tracks = $this->xMPD->find("album", $album);

							$totalLength = get_timer_display( array_reduce( array_column( $tracks, "Time" ), function($a, $b) {
								return $a += $b;
							}));
	
							$response['data']['json'][] = array(	'href' => $albumUrl,
												'art' => '/'.$albumArtFile, 
												'transition' => $this->data['default_page_transition'],
												'name' => str_replace('"', '\\"', $album), 
												'theme_buttons' => $this->data['theme_buttons'],
												'count_bubble_value' => count($tracks),
												'total_length' => $totalLength	); 

						} else {

							$response['data']['json'][] = array(	'href' => $albumUrl,
												'art' => '/'.$albumArtFile, 
												'transition' => $this->data['default_page_transition'],
												'name' => str_replace('"', '\\"', $album), 
												'theme_buttons' => $this->data['theme_buttons']	);
						}

						// Increment the count of how many we've iterated through so far
						$albums_retrieved++;
					}
				}
			}

			$response['data']['count'] = $albums_retrieved;

		} 

		$this->firephp->log($albums_listed_so_far, "albums listed so far");
		$this->firephp->log($albums_retrieved, "albums retrieved");

		Session::put('albums_listed_so_far', ($albums_listed_so_far + $albums_retrieved));

		// Echo out the JSON representation of the next set of li elements to display
		echo json_encode($response);
	}
}
