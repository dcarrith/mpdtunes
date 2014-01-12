<?php 

require_once($this->data['document_root'].'includes/php/library/art.inc.php');
require_once($this->data['document_root'].'includes/php/library/stations.inc.php');

function get_mpd_playlist_as_json($mpd, $configs, $firephp=null, $listed_so_far=0, $items_to_retrieve=0, $playlist_index_offset=0, $user=null) {

	//$firephp->log($configs, "configs");

	$user_id = 0;

	if (isset($user)) {

		$user_id = $user->id;
	}

	$music_dir 			= $configs['music_dir'];
	$art_dir 			= $configs['art_dir'];
	$queue_dir 			= $configs['queue_dir'];
	$base_protocol 			= $configs['base_protocol'];
	$base_domain 			= $configs['base_domain'];
	$document_root 			= $configs['document_root'];
	$default_no_album_art_image 	= $configs['default_no_album_art_image'];

	$json_playlist = ""; 

	if (!isset($mpd->connected) || ($mpd->connected == "")) {

		//return $json_playlist;
	}

	$retrieving_the_rest = false;

	if ($items_to_retrieve == "all") {
		$retrieving_the_rest = true;
	}

	$firephp->log($mpd->playlist, "mpd->playlist");

	//$firephp->log($listed_so_far, "listed_so_far");
	//$firephp->log($items_to_retrieve, "items_to_retrieve");

	$tracks_in_playlist = count($mpd->playlist);

	//$firephp->log($tracks_in_playlist, "tracks_in_playlist");

	if (($listed_so_far == 0) && ($items_to_retrieve == 0)) {

		$items_to_retrieve = $tracks_in_playlist;
	}

	$queue_tracks_so_far = $playlist_index_offset;

	$items_left_to_retrieve = $items_to_retrieve;

	if ($retrieving_the_rest) {

		$items_left_to_retrieve = $tracks_in_playlist;
	}

	if ($tracks_in_playlist > 0) {

		$mpd_playlist_index = 0;

	    	foreach($mpd->playlist as $key=>$playlist_track){

			if ($queue_tracks_so_far < $listed_so_far) {

				$mpd_playlist_index++;

				$queue_tracks_so_far++;

				continue;
			}

	    		//$firephp->log($mpd_playlist_index, "mpd_playlist_index");

	    		//$firephp->log($playlist_track, "playlist_track");

	    		//$firephp->log($playlist_track['file'], "playlist file");

	    		//$firephp->log(strpos($playlist_track['file'], "http://"), "strpos");

			// check to see if this playlist item is a url stream
			if (( strpos($playlist_track['file'], "http://") === 0) || (strpos($playlist_track['file'], "rtmp://") === 0)) {

                                $urlHash = hash("sha512", $playlist_track['file']);




				$is_a_users_station = false;

				// http://demo.mpdtunes.com:16604/mpd.ogg
				$matches = array();

				$mpdogg = preg_match('/mpd\.ogg/i', $playlist_track['file']);

				$firephp->log($mpdogg, "mpdogg?");

				// get host name from URL
				preg_match('@^(?:http://|https://)?([^/]+)@i', $playlist_track['file'], $matches);
				$domain_port = $matches[1];
				$firephp->log($domain_port, "matched fqdn");

				$stream_domain = current(explode(":", $domain_port));

				$firephp->log($base_domain, "base domain");

				// If the fqdn of the stream url is the same as the site's base_domain and the mpd.ogg exists, then
				// we'll assume that this is a user's station
				if ( ($mpdogg == 1) && ($base_domain == $stream_domain) ) {
					
					$is_a_users_station = true;

					$firephp->log($playlist_track['file']." is a user's station.", "message");
				}


				//$firephp->log("flushing cache", "message");
				//Cache::flush();	

				$firephp->log("init_current_mpd_track_data.php - checking to see if the urlHash '".$urlHash."' is in cache", "message");

				if (!Cache::has($urlHash."_".($is_a_users_station ? "null" : $user_id))) {

					$firephp->log("init_current_mpd_track_data.php - adding the station object with urlHash '".$urlHash."' to cache", "message");
	
					$station = Cache::rememberForever($urlHash."_".($is_a_users_station ? "null" : $user_id), function() use ($urlHash, $firephp, $user_id) {
            		
						$result = Station::where( 'url_hash', '=', $urlHash )->where( function( $query ) use ($user_id) {
                					
							$query->where('creator_id', '=', $user_id)->orWhere( function ( $query ) {
										
								$query->whereNull('creator_id');
							});
            					});
						
						if ($result->first()) {

							$firephp->log($result->first()->toArray(), "result - > first() - > toArray()");
						}					
	
						return $result->first();
					});
	
				} else {

					$firephp->log("init_current_mpd_track_data.php - retrieving station object with urlHash '".$urlHash."' from cache", "message");
					
					$station = Cache::get($urlHash."_".($is_a_users_station ? "null" : $user_id));
					
					$firephp->log($station->toArray(), "station object from cache as array");	
				}

			  	$stream_is_current_track = true;
				$firephp->log($stream_is_current_track, "stream_is_current_track?");
	
				if (isset($station)) {

					$firephp->log($station->toArray(), "station->toArray()");

					$station_url = $station->url;
					$station_name = $station->name;
					$station_description = ((strlen($station->description) >= 108) ? substr($station->description, 0, 108).'...' : $station->description);
	
					$firephp->log($station_url, "station_url");
					$firephp->log($station_name, "station_name");
					$firephp->log($station_description, "station_description");

					$stationsIcon = null;

					if (!Cache::has($urlHash."_".($is_a_users_station ? "null" : $user_id)."_icon")) {

						$stationsIcon = Cache::rememberForever($urlHash."_".($is_a_users_station ? "null" : $user_id)."_icon", function() use ($station) {
	
							return $station->stationsIcon;
						});
		
					} else {
		
						$stationsIcon = Cache::get($urlHash."_".($is_a_users_station ? "null" : $user_id)."_icon");
					}

					$firephp->log($stationsIcon->toArray(), "stationsIcon - > toArray()");

					$station_icon_filename = $stationsIcon->baseurl.$stationsIcon->filename;







				/*$firephp->log("flushing cache", "message");
				Cache::flush();

				$station = null;

				$firephp->log("mpd.inc.php - checking to see if the urlHash '".$urlHash."' is in cache", "message");

				if (!Cache::has($urlHash)) {

					$firephp->log("mpd.inc.php - adding the station object with urlHash '".$urlHash."' to cache", "message");
	
					$station = Cache::rememberForever($urlHash, function() use ($urlHash) {
	
						return Station::where( 'url_hash', '=', $urlHash )->first();
					});
	
				} else {

					$firephp->log("mpd.inc.php - retrieving station object with urlHash '".$urlHash."' from cache", "message");
			
					$station = Cache::get($urlHash);
		
					$firephp->log($station->toArray(), "station object from cache as array");	
				}

				//$firephp->log($station->toArray(), "station->toArray()");

				if ($station) {

					$station_url = $station->url;

					$stationsIcon = null;

					if (!Cache::has($urlHash."_icon")) {

						$stationsIcon = Cache::rememberForever($urlHash."_icon", function() use ($station) {
	
							return $station->stationsIcon;
						});
		
					} else {
			
						$stationsIcon = Cache::get($urlHash."_icon");
					}

					$station_icon_filename 	= $stationsIcon->baseurl.$stationsIcon->filename;

					$station_name		= $station->name;
					$station_description	= $station->description;*/

					// the playlist is just a JSON-style object.  we need to double escape double quotes.
					$json_playlist .= '{	"type"		: "stream",
								"url" 		: "'.$station_url.'",
								"oggurl" 	: "",
								"artist" 	: "'.str_replace("\"", "\\\"", $station_name).'",
								"album" 	: "'.str_replace("\"", "\\\"", $station_description).'",
								"title" 	: "'.str_replace("\"", "\\\"", $station_name).'",
								"art" 		: "/'.$station_icon_filename.'",
								"file" 		: "'.$station_url.'",
								"time"		: "Infinity",
								"mpd_index" 	: "'.$mpd_playlist_index.'"	},';
				}

			} else {

				$limited_configs = array();

				$limited_configs['music_dir'] 			= $music_dir;
				$limited_configs['art_dir'] 			= "/".ltrim($art_dir, "/");
				$limited_configs['document_root'] 		= $document_root;
				$limited_configs['default_no_album_art_image'] 	= $default_no_album_art_image;
	
			    	$album_art_filename = get_album_art(	$playlist_track['file'], 
		    							$playlist_track['Artist'], 
		    							$playlist_track['Album'], 
		    							$limited_configs	);

		    		$track_ra = explode("/", $playlist_track['file']);

		        	$filename = $queue_dir.sha1($track_ra[count($track_ra)-1]).'.mp3';

		     		$ogg_file_url = "";

		     		if (ogg_version_exists($music_dir.$playlist_track['file'])) {
		        
		        		$ogg_file_url = $queue_dir.sha1($track_ra[count($track_ra)-1]).'.ogg';
		    		}

				// the playlist is just a JSON-style object.  we need to double escape double quotes.
				$json_playlist .= '{	"type"		: "file",
							"url" 		: "'.$filename.'",
							"oggurl" 	: "'.$ogg_file_url.'",
							"artist" 	: "'.str_replace("\"", "\\\"", $playlist_track['Artist']).'",
							"album" 	: "'.str_replace("\"", "\\\"", $playlist_track['Album']).'",
							"title" 	: "'.str_replace("\"", "\\\"", $playlist_track['Title']).'",
							"art" 		: "'.$album_art_filename.'",
							"file" 		: "'.$playlist_track['file'].'",
							"time"		: "'.$playlist_track['Time'].'",
							"mpd_index" 	: "'.$mpd_playlist_index.'"	},';
			}

			$mpd_playlist_index++;

			$queue_tracks_so_far++;

			$items_left_to_retrieve--;

			if ($items_left_to_retrieve == 0) {

				break;
			}
		}

		$json_playlist = '{ "tracks" : [' . rtrim($json_playlist, ",") . "] }";
	}

	return $json_playlist;
}

function ogg_version_exists($absolute_path){
	
	$ogg_version_exists = false;

    $absolute_path_to_ogg = str_replace("mp3", "ogg", $absolute_path);

    if (file_exists($absolute_path_to_ogg)){
    	$ogg_version_exists = true;
	} 

	return $ogg_version_exists;
}

function mp3_version_exists($absolute_path){
	
	$mp3_version_exists = false;

    $absolute_path_to_mp3 = str_replace("ogg", "mp3", $absolute_path);

    if (file_exists($absolute_path_to_mp3)){
    	$mp3_version_exists = true;
	} 

	return $mp3_version_exists;
}

function get_timer_display($timer_input) {

	$minutes = "-";
	$seconds = "--";

	if ((!isset($timer_input)) || ($timer_input === "Infinity") || ($timer_input === "")) {
		
		return "∞";
	
	} else {

		if ($timer_input > 0) {

			$minutes = floor($timer_input / 60);

			$seconds = floor($timer_input % 60);

			if ($seconds < 10) {
				
				$seconds = "0" . $seconds;
			}
		}
	}

	return ($minutes.':'.$seconds);
}

?>
