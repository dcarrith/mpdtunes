<?php 

class MPDController extends MPDTunesController {

	function __construct() {

        	parent::__construct();
	}

	public function index() {

	}
	
        public function query() {

                // first check to make sure we're connected to MPD (is it running?)
                if (isset($this->MPD->connected) && ($this->MPD->connected != "")) {

                        $this->MPD->RefreshInfo();

                } else {

                        echo "";
                        exit();
                }

                $configs = array();

                $configs['music_dir']                   = $this->data['music_dir'];
                $configs['art_dir']                     = $this->data['art_dir'];
                $configs['document_root']               = $this->data['document_root'];
                $configs['default_no_album_art_image']  = $this->data['default_no_album_art_image'];
                $configs['queue_dir']                   = $this->data['queue_dir'];
                $configs['base_protocol']               = $this->data['base_protocol'];
                $configs['base_domain']                 = $this->data['base_domain'];

                $this->data['json_playlist'] = get_mpd_playlist_as_json($this->MPD, $configs, $this->firephp);

		return Response::json($this->data['json_playlist']);
        }

	public function control($operation) {

		$return = [ 	'action'	=>$operation, 
				'success'	=>true, 
				'message'	=>''	];

		switch ($operation) {

			// Route: /mpd/control/play
			case 'play':
				$this->MPD->Play();
				break;

			// Route: /mpd/control/pause
			case 'pause':
				$this->MPD->Pause();
				break;
			
			// Route: /mpd/control/stop
			case 'stop':
				$this->MPD->Stop();
				break;

			// Route: /mpd/control/next
			case 'next':
				$this->MPD->Next();
				$this->MPD->Play();
				break;

			// Route: /mpd/control/previous
			case 'previous':
				$this->MPD->Previous();
				$this->MPD->Play();
				break;
			
			// Route: /mpd/control/skip
			case 'skip':
				$this->MPD->SkipTo(Request::get('index'));
				break;
			
			// Route: /mpd/control/crossfade
			case 'crossfade':
				$this->MPD->SetXFade(Request::get('crossfade'));
				break;

			// Route: /mpd/control/shuffle
			case 'shuffle':
				$this->MPD->PLShuffle();
				break;

			// Route: /mpd/control/repeat
			case 'repeat':
				$this->MPD->SetRepeat(Request::get('repeat'));
				break;

			// Route: /mpd/control/refresh
			case 'update':
				$this->MPD->DBRefresh();
				break;

			// Route: /mpd/control/volume
			case 'volume':
				$this->MPD->SetVolume(Request::get('volume'));
				break;

			// Route: /mpd/control/mute
			case 'mute':
				$this->MPD->SetVolume(1);
				break;

			// Route: /mpd/control/reset
			case 'reset':
				$this->MPD->SeekTo(0);
				break;

			default:
				// nothing to do here
				break;
		}

		return $return;
	}

	public function playlist($arguments) {

		$argumentsRa = explode("/", $arguments);
		
		$operation = $argumentsRa[0];
		$what = "";

		if( count($argumentsRa) == 2 ) {
			
			$operation = $argumentsRa[0];
			$what = $argumentsRa[1];
		}

		$playlistName = Request::get('playlist_name');
		
		$this->firephp->log( $playlistName, "playlistName" );
		$this->firephp->log( $operation, "operation" );
		$this->firephp->log( $what, "what" );

		$result = [	'action'	=>$operation, 
				'success'	=>true, 
				'message'	=>'' 	];
	
		switch($operation) {

			// Route: /mpd/playlist/save
			case 'save':
				$this->MPD->PLSave($playlistName);
				break;

			// Route: /mpd/playlist/delete
                        case 'delete':
                                $this->MPD->PLDelete($playlistName);
                                break;

			// Route: /mpd/playlist/add/album
			// Route: /mpd/playlist/add/albums
			// Route: /mpd/playlist/add/track
			// Route: /mpd/playlist/add/tracks
			// Route: /mpd/playlist/add/url
			case 'add':

                                if ( $what == "album" ) {

					// no use case yet

                                } else if ( $what == "albums" ) {

                                        $artist = Request::get('artist_name');

                                        $this->firephp->log($artist, "artistName");

                                        $result = $this->addAlbums($artist);

				} else if ( $what == "track" ) {

					$file = Request::get( 'file' );

					$this->firephp->log( $file, "file" );

					if (!$playlistName) {

						$result = $this->add($file);

					} else {

						$this->MPD->PLAddTrack( $playlistName, $file );
					}

				} else if ( $what == "tracks" ) {

                                	$source = Request::get('source');
					$name = "";

					if ($source == "playlist") {

						$name = Request::get('playlist_name');

					} else if ( $source == "album" ) {

						$name = Request::get('album_name');

					} else {
						
						// whatever else
					}

					$this->firephp->log($source, "source"); 
					$this->firephp->log($name, "name"); 

					if ($name != "") {
						
						$result = $this->addTracks($source, $name);
					}

				} else if ( $what == "url" ) {

					$stationUrl = Request::get('station_url');

					$this->MPD->PLAdd($stationUrl);

				} else {
					// nothing else yet
				}

				break;

			// Route: /mpd/playlist/clear
			case 'clear':
				
				$result = $this->clear();

				break;

			// Route: /mpd/playlist/move/track
			case 'move':

                                if ( $what == "track" ) {

					$fromPos       = Request::get( 'from_pos' );
					$toPos         = Request::get( 'to_pos' );
                                                
					$this->firephp->log( $fromPos, "from_pos" );
					$this->firephp->log( $toPos, "to_pos" );

					if ( $playlistName ) {

						$this->MPD->PLTrackMove( $playlistName, $fromPos, $toPos );

					} else {
	
						$this->MPD->PLMoveTrack( $fromPos, $toPos );
                                	}
				}								

				break;
			
			// Route: /mpd/playlist/remove/track
			case 'remove':

				if ( $what == "track" ) {

					if ( $playlistName ) {

						$pos = Request::get( 'pos' );

						$this->firephp->log( $pos, "pos" );

						$this->MPD->PLTrackRemove( $playlistName, $pos );

					} else {

						$index	= Request::get('index');
						$id	= Request::get('id');
						
						$result = array_merge( $result, array( 'index'=>$index, 'id'=>$id ), $this->remove( $index, $id ));
					}
				}

				break;
		}

		$this->firephp->log(json_encode($result), "json encoded result");
		
		echo json_encode($result);
	}

	public function add( $filepath ) {

		$result = array_merge( array( 'action'=>'add' ), $this->addSymbolicLink( $filepath ));

		$this->firephp->log($result, "final result");
		
		// We only want to add the track to the queue if the symbolic link was successfully created	
		if( $result['success'] ) {

		    	$this->MPD->PLAdd($filepath);
		}

		return $result;
	}

	public function addTracks($source, $name) {

		// Default this to just the action, whether or not it was successful and a blank message
		$result = [ 'action'=>'addTracks', 'success'=>true, 'message'=>'' ];
		
		// This is to store the array of tracks from the playlist or album
		$tracks = array();

		// This is to store the array of track filepaths 
		$filepaths = array();

		switch($source) {

			case 'playlist' :

				$tracks = $this->MPD->GetTracks("playlist", $name);

				break;

			case 'album' :

				$tracks = $this->MPD->GetTracks("album", $name);

				break;

			default :

				// Don't know of any scenario at this point
				break;
		}

		for($i=0; $i<count($tracks); $i++){

			$filepath = $tracks[$i][1];

			$filepaths[] = $filepath;
		
			$result = $this->addSymbolicLink( $filepath );
		
			if( !$result['success'] ) {

				// break as soon as there is an unsuccessful attempt to add a symbolic link
				break;
			}
		}

		// Put the action part of the array back 
		$result = array_merge( $result, array( 'action'=>'addTracks' ));

		$this->firephp->log($result, "final result");
		
		// This is just going to confirm that it was able to add the last symbolic link
		// Assuming all symbolic links are being created in the same directory, 
		// they should all either succeed or fail
		if( $result['success'] ) {

			$this->MPD->PLAddBulk( $filepaths );
		}

		return $result;
	}

	public function addAlbums($artist){

		// Default this to just the action, whether or not it was successful and a blank message
		$result = [ 'action'=>'addAlbums', 'success'=>true, 'message'=>'' ];

	    	$artists_albums = $this->MPD->GetAlbums($artist);

	    	$this->firephp->log($artists_albums, "artists_albums");

	    	$filepaths = array();

	    	foreach($artists_albums as $album_name){

	      		$album_tracks = $this->MPD->GetTracks("album", $album_name);

	      		$this->firephp->log($album_tracks, "album_tracks");

	      		foreach($album_tracks as $track){
	      
				$filepath = $track[1];

				$filepaths[] = $filepath;
		
				$result = $this->addSymbolicLink( $filepath );
	
				if( !$result['success'] ) {

					// break out 2 levels as soon as there is an unsuccessful attempt to add a symbolic link
					break 2;
				}
			}
	    	}

		// Put the action part of the array back 
		$result = array_merge( $result, array( 'action'=>'addAlbums' ));

		$this->firephp->log($result, "final result");
		
		// This is just going to confirm that it was able to add the last symbolic link
		// Assuming all symbolic links are being created in the same directory, 
		// they should all either succeed or fail
		if( $result['success'] ) {

			$this->MPD->PLAddBulk( $filepaths );
		}

		return $result;
	}
	
	public function remove( $index ) {

		$result = [ 'action'=>'remove', 'success'=>true, 'message'=>'' ];

		// Attempt to remove the symbolic link based on the index that was passed
		$result = array_merge( $result, $this->removeSymbolicLink( $index ));
		
		$this->firephp->log($result, "final result");

		// We only want to remove the track from the MPD playlist if we were able to remove the symbolic link
		if( $result['success'] ) {

			// Send the command to MPD to remove the track at the specified index from the playlist
		    	$this->MPD->PLRemove($index);
		}

		return $result;
	}

	public function clear() {

		$this->MPD->RefreshInfo();

		// Default this to just the action, whether or not it was successful and a blank message
		$result = [ 'action'=>'clear', 'success'=>true, 'message'=>'' ];

	    	foreach($this->MPD->playlist as $key=>$playlist) {
		
			$result = $this->removeSymbolicLink( $key );
		
			if( !$result['success'] ) {

				// break as soon as there is an unsuccessful attempt to remove a symbolic link
				break;
			}
		}

		// Put the action part of the array back 
		$result = array_merge( $result, array( 'action'=>'clear' ));

		$this->firephp->log($result, "final result");
		
		// This is just going to confirm that it was able to remove the last symbolic link
		// Assuming all permissions are the same for each symbolic link, then we should be 
		// fairly confident that it was a total success
		if( $result['success'] ) {

			$this->MPD->PLClear();
		}

		return $result;
	}

	public function addSymbolicLink( $filepath ) {

		$document_root 	= $this->data['document_root'];
		$music_dir 	= $this->data['music_dir'];
		$queue_dir 	= $this->data['queue_dir'];
		$output		= null;
		$resultCode	= -1;

		$result = [ 'success'=>true, 'message'=>'' ];

		if (strrpos($document_root, "/") == (strlen($document_root) - 1)) {
			$queue_dir = ltrim($this->data['queue_dir'], "/");
		}

	    	$track_ra = explode("/", $filepath);

	    	$this->firephp->log($track_ra, "track_ra");

	    	$absolute_path = "\"".$music_dir.$filepath."\"";
	    	$absolute_path_no_quotes = $music_dir.$filepath;

	    	$this->firephp->log($absolute_path_no_quotes, "absolute_path_no_quotes");

	    	$track = $track_ra[count($track_ra)-1];

		if (ogg_version_exists($absolute_path_no_quotes)) {

			$absolute_path_to_ogg = str_replace("mp3", "ogg", $absolute_path_no_quotes);
	          	
			$this->firephp->log('ln -s '.escapeshellarg(str_replace("$", "\\$", $absolute_path_to_ogg)).' '.escapeshellarg($document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.ogg'), "symbolic link");
	          	
			// Execute the ln -s command and direct standard error to standard output to make it available to output and result
			exec('ln -s '.escapeshellarg(str_replace("$", "\\$", $absolute_path_to_ogg)).' '.escapeshellarg($document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.ogg').' 2>&1', $output, $resultCode);
	        }

	       	if (mp3_version_exists($absolute_path_no_quotes)) {

			$absolute_path_to_mp3 = str_replace("ogg", "mp3", $absolute_path_no_quotes);

			$this->firephp->log('ln -s '.escapeshellarg(str_replace("$", "\\$", $absolute_path_to_mp3)).' '.escapeshellarg($document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.mp3'), "symbolic link");

			// Execute the ln -s command and direct standard error to standard output to make it available to output and result
	          	exec('ln -s '.escapeshellarg(str_replace("$", "\\$", $absolute_path_to_mp3)).' '.escapeshellarg($document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.mp3').' 2>&1', $output, $resultCode);
	        }
	
		if ($output) {

			$this->firephp->log($output, "output");

			try {

				// Sanity check
				if ( array_key_exists( 0, $output )) {

					$output_array = explode(":", $output[0]);

					if ( count($output_array) == 3 ) {
		
						$result['message'] = trim($output_array[2]);
					}

					$this->firephp->log($result, "result");

					if( strpos($result['message'], "denied") > 0 ) {
				
						$this->firephp->log($result['message'], "message");
				
						$result['success'] = false;
					}
				}

			} catch ( Exception $e ) {

				$this->firephp->log( $e, "Exception");
			}
		}

		return $result;     
	}

	public function removeSymbolicLink( $index ) {

		$document_root 	= $this->data['document_root'];
		$queue_dir 	= $this->data['queue_dir'];
		$output		= null;
		$resultCode	= -1;

		$result = [ 'success'=>true, 'message'=>'' ];

	        $track_ra = explode("/", $this->MPD->playlist[$index]['file']);

	      	$symbolic_link = $document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.mp3';

	      	if (file_exists($symbolic_link)) {

	      		$this->firephp->log("rm -f ".$symbolic_link, "remove symbolic link");

			// Execute the rm command and direct standard error to standard output to make it available to output and result
	        	exec(escapeshellcmd('rm -f -v').' '.escapeshellarg($symbolic_link).' 2>&1', $output, $resultCode);
		}

	      	$symbolic_link = $document_root.$queue_dir.sha1($track_ra[count($track_ra)-1]).'.ogg';

	      	if (file_exists($symbolic_link)) {

	      		$this->firephp->log("rm -f ".$symbolic_link, "remove symbolic link");

			// Execute the rm command and direct standard error to standard output to make it available to output and result
	        	exec(escapeshellcmd('rm -f -v').' '.escapeshellarg($symbolic_link).' 2>&1', $output, $resultCode);
		}
	
		if ($output) {

			$this->firephp->log($output, "output");

			try {

				// Sanity check
				if ( array_key_exists( 0, $output )) {

					$output_array = explode(":", $output[0]);

					if ( count($output_array) == 3 ) {
		
						$result['message'] = trim($output_array[2]);
					}

					$this->firephp->log($result, "result");

					if( strpos($result['message'], "denied") > 0 ) {
				
						$this->firephp->log($result['message'], "message");
				
						$result['success'] = false;
					}
				}

			} catch ( Exception $e ) {

				$this->firephp->log( $e, "Exception");
			}
		}

		return $result;
	}
}
