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

		$this->data['artists'] 	= array();
		$this->data['data_url'] = "";

		$encoded_artist_name 	= NULL;

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

		require_once('includes/php/library/art.inc.php');

		$configs = array();

		$configs['music_dir']			= $this->data['music_dir'];
		$configs['art_dir']			= $this->data['art_dir'];
		$configs['document_root']		= $this->data['document_root'];
		$configs['default_no_album_art_image']	= $this->data['default_no_album_art_image'];

		$this->firephp->log($configs, "configs");

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			//$artist_name = $this->data['artist_name'];

			if (isset($encoded_artist_name) && ($encoded_artist_name != '')) {

				$artist_name = urldecode(urldecode($encoded_artist_name));
				$this->data['artist_name'] 		= $artist_name;
				$this->data['encoded_artist_name'] 	= $encoded_artist_name;

				// we need to double decode it here in case the user is going back in history (data-url is double encoded)
				$albums = $this->MPD->GetAlbums(addslashes($artist_name));

				// we want the list of albums to be sorted alphabetically by name
				sort($albums, SORT_STRING);

				$albums_count = count($albums);

				if ($albums_count > $default_num_albums_to_display) {
				
					// if we have lazy loading enabled, then we need to set albums_count to default_num_albums_to_display
					$albums_count = $default_num_albums_to_display;
				}

				// let's pass this to the view so we don't have to count twice
				$this->data['albums_count'] = $albums_count;

				for($i=0; $i<$albums_count; $i++) {

					$album_name 		= $albums[$i];
					$first_song 		= $this->MPD->GetOneTrack("album", $album_name);					
					
					$this->firephp->log( $first_song, "first_song");

					if ( is_array($first_song) ) {

						$first_song = $first_song[0][1];
					}

					$this->firephp->log( $first_song, "first_song");

					$encoded_album_name 	= urlencode(urlencode($album_name));
					$album_art_file 	= get_album_art($first_song, $artist_name, $album_name, $configs, $this->firephp);

					//$album_art_file = $configs['default_no_album_art_image'];
	
					// make sure there is no forward slash on the front of the file path
					$album_art_file 	= ltrim($album_art_file, "/");

					$this->firephp->log($album_art_file, "album_art_file");

					// only perform the extra overhead processing of getting the track counts if configured to show count bubbles
					if ($show_album_track_count_bubbles) {

						$tracks 	= $this->MPD->GetTracks("album", $album_name);

						$this->firephp->log($tracks, "tracks");

						$tracks_count 	= count($tracks);

						$total_length 	= "-:--";

						for($j=0; $j<$tracks_count; $j++) {
							
							$total_length += $tracks[$j][2];
						}

						$total_length = get_timer_display($total_length);

						$this->firephp->log($total_length, "total_length");

						$this->data['albums'][$i] = array(	"album_name"		=> $album_name, 
											"encoded_album_name"	=> $encoded_album_name, 
											"album_art"		=> $album_art_file,
 											"total_length"		=> $total_length,
											"track_count"		=> $tracks_count	);
 
						/*if ($albums_so_far == $default_num_albums_to_display) {

							break;
						}*/

					} else {

						$this->data['albums'][$i] = array(	"album_name"		=> $album_name, 
											"encoded_album_name"	=> $encoded_album_name, 
											"album_art"		=> $album_art_file	);		

						/*if ($albums_so_far == $default_num_albums_to_display) {

							break;
						}*/				
					}

					$albums_so_far++;
				}
			}
		}

		$this->firephp->log($this->data['albums'], "albums");

                // this is used by the scroll down button in the footer
                $this->data['section'] = Request::segment(3);

                $this->firephp->log($this->data, "data");

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

		$albums 			= array();
		$albums_li_elements_ra 		= array();
		$albums_li_elements_html 	= "";
		$albums_li_elements_as_json	= "";

		$more_albums_json = "";

		require_once('includes/php/library/art.inc.php');

		$configs = array();

		$configs['music_dir']			= $this->data['music_dir'];
		$configs['art_dir']			= $this->data['art_dir'];
		$configs['document_root']		= $this->data['document_root'];
		$configs['default_no_album_art_image']	= $this->data['default_no_album_art_image'];

		// only try to retrive the artists list from MPD if there is a valid connection to MPD
		if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

			if (isset($artist_name) && ($artist_name != '')) {

				$albums = $this->MPD->GetAlbums(addslashes($artist_name));

				// we want the list of albums to be sorted alphabetically by name
				sort($albums, SORT_STRING);

				$artist_albums_count = count($albums);

				$this->firephp->log($artist_albums_count, "artist_albums_count");

				for($i=0; $i<$artist_albums_count; $i++) {

					$this->firephp->log($albums_count, "albums_count");
					$this->firephp->log($albums_listed_so_far, "albums_listed_so_far");

					if ($albums_count >= $albums_listed_so_far) {

						$this->firephp->log($albums_to_retrieve, "albums_to_retrieve");

						if (!$retrieving_the_rest) {

							if ($albums_count >= ($albums_to_retrieve + $albums_listed_so_far)) {

								break;
							}
						}

						$album_name 		= $albums[$i];

						$this->firephp->log($album_name, "album_name");

						$this->firephp->log(str_replace('"', '\\"', $album_name), "str_replaced album_name");

						$first_song 		= $this->MPD->GetOneTrack("album", $album_name);

						$this->firephp->log( $first_song, "first_song");

						if ( is_array($first_song) ) {

							$first_song = $first_song[0][1];
						}

						$this->firephp->log( $first_song, "first_song");

						$encoded_album_name 	= urlencode(urlencode($album_name));

						$album_art_file 	= get_album_art($first_song, $artist_name, $album_name, $configs);

						// make sure there is no forward slash on the front of the file path
						$album_art_file 	= ltrim($album_art_file, "/");

						$this->firephp->log($album_art_file, "album_art_file");

						// only perform the extra overhead processing of getting the track counts if configured to show count bubbles
						if ($show_album_track_count_bubbles) {

							$tracks 	= $this->MPD->GetTracks("album", $album_name);

							$this->firephp->log($tracks, "tracks");

							$tracks_count 	= count($tracks);

							$total_length 	= "-:--";

							for($j=0; $j<$tracks_count; $j++) {
								
								$total_length += $tracks[$j][2];
							}

							$total_length 	= get_timer_display($total_length);

							//$albums_li_elements_html .= "<li class='ui-li-has-thumb'><a href='/artist/".urlencode(urlencode(str_replace('"', '\\"', $artist_name)))."/album/".str_replace('"', '\\"', $encoded_album_name)."/tracks' data-transition='".$this->data['default_page_transition']."'><img src='/".$album_art_file."' class='ui-li-thumb album-art-img' /><h3 class='ui-li-heading'>".str_replace('"', '\\"', $album_name)."</h3><span class='ui-li-count ui-btn-up-".$this->data['theme_buttons']." ui-btn-corner-all'>".$tracks_count."</span></a></li>";
							$albums_li_elements_as_json .= '{ "href" : "/artist/'.urlencode(urlencode(str_replace('"', '\\"', $artist_name))).'/album/'.str_replace('"', '\\"', $encoded_album_name).'/tracks", "art":"/'.$album_art_file.'", "transition":"'.$this->data['default_page_transition'].'", "name":"'.str_replace('"', '\\"', $album_name).'", "theme_buttons":"'.$this->data['theme_buttons'].'", "count_bubble_value":"'.$tracks_count.'", "total_length":"'.$total_length.'" },';

							//$this->firephp->log($albums_li_elements_html, "albums_li_elements_html");
							$this->firephp->log($album_name, "album_name");

						} else {
					
							//$albums_li_elements_html .= "<li class='ui-li-has-thumb'><a href='/artist/".urlencode(urlencode(str_replace('"', '\\"', $artist_name)))."/album/".str_replace('"', '\\"', $encoded_album_name)."/tracks' data-transition='".$this->data['default_page_transition']."'><img src='/".$album_art_file."' class='ui-li-thumb album-art-img' /><h3 class='ui-li-heading'>".str_replace('"', '\\"', $album_name)."</h3></a></li>";
							$albums_li_elements_as_json .= '{ "href" : "/artist/'.urlencode(urlencode(str_replace('"', '\\"', $artist_name))).'/album/'.str_replace('"', '\\"', $encoded_album_name).'/tracks", "art":"/'.$album_art_file.'", "transition":"'.$this->data['default_page_transition'].'", "name":"'.str_replace('"', '\\"', $album_name).'", "theme_buttons":"'.$this->data['theme_buttons'].'" },';


							$this->firephp->log($album_name, "album_name");
						}

						$albums_retrieved++;
					}

					$albums_count++;
				}

				$albums_li_elements_as_json = rtrim($albums_li_elements_as_json, ",");
			}

			//$more_albums_json = '{ "data" : [{ "count" : "'.$albums_retrieved.'", "html" : "' . $albums_li_elements_html . '" } ] }';
			$more_albums_json = '{ "data" : [{ "count" : "'.$albums_retrieved.'", "json" : [' . $albums_li_elements_as_json . '] } ] }';

		} else {

			$more_albums_json = '{ "data" : [{ "count" : "'.$albums_retrieved.'", "html" : "" } ] }';
		}

		Session::put('albums_listed_so_far', ($albums_listed_so_far + $albums_retrieved));

		// echo out the HTML for the next set of artist li elements
		echo $more_albums_json;
	}
}
