<?php 

class MPDController extends MPDTunesController {

	function __construct() {

        	parent::__construct();
	}

	public function index() {

	}

	public function control($operation) {

		$return = [ 	'action'	=>$operation, 
				'success'	=>true, 
				'message'	=>''	];

		switch ($operation) {

			// Route: /mpd/control/idle
			case 'idle':

				$this->firephp->log(Session::get("mpdidle"), "Checking if mpd is already sitting idle");

				// Let's first make sure we don't already have an idle connection with MPD
				if (!Session::get("mpdidle")) {
	
					// Let's keep track of whether or not we already have an idle session going
					Session::put("mpdidle", true);

					// Let's try to establish an idle loop with MPD
					try {

						while( $update = $this->xMPD->idle() ) {
						//while( 1 ) {

							$this->xMPD->RefreshInfo();

							// Initialize the message to publish with just the action
							$message["msg"] = array("action" => $operation);

							$data = array();

							switch ($update) {

								case 'player':
							
									$data = $this->xMPD->status();

									$newCurrentTrack = $this->xMPD->playlist[$data['song']];
									//$this->firephp->log($newCurrentTrack, "switched to track");
	
									$newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack); 
									//$this->firephp->log($newCurrentTrack, "added supplementary info to track");

									// Add the update data and newTrack data to the message
									$message["msg"] = array_merge($message["msg"], array( "update" => $data, 
															      "track"  => $newCurrentTrack));

									break;

								default :
	
									break;
							}

							//$updatedPlaylist = array( "playlist" => array( "tracks" => $this->getPlaylistTracks( "current" )));

							//$message["msg"] = array_merge( $message["msg"], $updatedPlaylist );
							$message["msg"] = array_merge( $message["msg"], array( "user" => $this->user->toArray()));

							//$this->firephp->log($message, "publising the following message");
	
							// Publish the new track to all listeners of the station through the WebSocket
							Latchet::publish('radio/station/'.$this->data['station']->id, $message);
				
							$this->firephp->log("sleeping for 5 seconds");
	
							// Sleep for 5 seconds so we don't bombard the client with messages	
							sleep(5);
						}
				
					} catch (Exception $e) {

						//$this->firephp->log($e, "Exception occurred");

						// Let's call noidle just to make sure it's torn down
						$this->control("noidle");

						// Restart the idle connection
						$this->control("idle");
					}
				}

				break;

			// Route: /mpd/control/noidle
			case 'noidle':

				// Let's update the session so we can restart the idle connection
				Session::put("mpdidle", false);

				$update = $this->xMPD->noidle();
							
				$data = $this->xMPD->status();

				$message["msg"] = array(	"action"=>$operation,
								"update"=>$data,
								"user"=>$this->user->toArray());

				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;

			// Route: /mpd/control/play
			case 'play':

				$position = Request::get("position");

				$trackId = $this->xMPD->current_track_id;

				$this->firephp->log($trackId, "trying to play the track at index");				

				if ($trackId < 0) {

					$trackId = 0;
				}

 				$this->firephp->log($trackId, "calling xMPD->play(".$trackId.")");

				$newCurrentTrack = $this->xMPD->playlist[$trackId];
				$this->firephp->log($newCurrentTrack, "switched to track");
	
				$newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack); 
				$this->firephp->log($newCurrentTrack, "added supplementary info to track");

				if ($position) {

					$this->firephp->log($position, "trying to seek first");

					$this->xMPD->seek( $trackId, $position );

					$newCurrentTrack['seekTo'] = $position; 

					$this->firephp->log($newCurrentTrack, "added seekTo");

				} else {

					$this->xMPD->play($trackId);
				}


				$message["msg"] = array(	"action"=>$operation,
								"track"=>$newCurrentTrack,
								"user"=>$this->user->toArray() );
	
				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;

			// Route: /mpd/control/pause
			case 'pause':

				// Let's keep the music playing on the server side
				//$this->xMPD->pause();
				break;
			
			// Route: /mpd/control/stop
			case 'stop':

				$this->xMPD->stop();
				break;

			// Route: /mpd/control/next
			case 'next':

				//$changed = $this->xMPD->GetIdle();
				//$this->firephp->log($changed, "what changed?");

				$newTrackId = ($this->xMPD->current_track_id + 1);
				
 				$this->firephp->log($newTrackId, "calling xMPD->play(".$newTrackId.")");
				if($newTrackId > (count($this->xMPD->playlist) - 1)) {

					// Loop it around to the beginning of the playlist
					$newTrackId = 0;
						
				}

				//$this->xMPD->next();
				$this->xMPD->play($newTrackId);

				$newCurrentTrack = $this->xMPD->playlist[$newTrackId];
				$this->firephp->log($newCurrentTrack, "switched to next track");
	
				$newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack); 
				$this->firephp->log($newCurrentTrack, "added supplementary info to track");


				/* next altnerate method
                                $this->xMPD->next();

                                $this->xMPD->RefreshInfo();

                                $newTrackId = $this->xMPD->current_track_id;

                                $newCurrentTrack = $this->xMPD->playlist[$newTrackId];
                                $this->firephp->log($newCurrentTrack, "switched to next track");

                                $newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack);
                                $this->firephp->log($newCurrentTrack, "added supplementary info to track");
				*/


				// This will be the final array to json_encode and return to the client
				/*
				$playlist = array('tracks' => array());

				// playlistName = "current", tracksListedSoFar = 0, tracksToRetrieve = "all", and context = "sync" 
				$playlist['tracks'] = $this->getPlaylistTracks("current", 0, "all", "sync");

				// How many items were retrieved (not used currently, but perhaps it will be useful for something)
				$justRetrieved = $playlist['tracks']['count'];

				// We don't need the count in the json part of the array anymore
				unset($playlist['tracks']['count']);
	
				// Echo out the JSON representation of the next set of li elements to display
				$json_encoded_playlist = json_encode($playlist);
		
				$this->firephp->log($json_encoded_playlist, "json_encoded_playlist");
				*/




				$message["msg"] = array(	"action"=>$operation,
								"track"=>$newCurrentTrack,
								"user"=>$this->user->toArray());//,
								/*"playlist" => $playlist );*/
	
				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;

			// Route: /mpd/control/previous
			case 'previous':

				$newTrackId = ($this->xMPD->current_track_id - 1);

				if($newTrackId < 0) {

					// Loop it around to the last track in the playlist
					$newTrackId = (count($this->xMPD->playlist) - 1);
						
				}

				//$this->xMPD->previous();	
				$this->xMPD->play($newTrackId);

				$newCurrentTrack = $this->xMPD->playlist[$newTrackId]; 
				$this->firephp->log($newCurrentTrack, "switched to previous track");
	
				$newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack); 
				$this->firephp->log($newCurrentTrack, "added supplementary info to track");

				$message["msg"] = array(	"action"=>$operation,
								"track"=>$newCurrentTrack,
								"user"=>$this->user->toArray());

				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;
			
			// Route: /mpd/control/skip
			case 'skip':

				$newTrackId = Request::get('index');

				$this->xMPD->play($newTrackId);

				$newCurrentTrack = $this->xMPD->playlist[$newTrackId]; 
				$this->firephp->log($newCurrentTrack, "skipped to track");
	
				$newCurrentTrack = $this->addSupplementaryTrackInfo($newCurrentTrack); 
				$this->firephp->log($newCurrentTrack, "added supplementary info to track");

				$message["msg"] = array(	"action"=>$operation,
								"track"=>$newCurrentTrack,
								"user"=>$this->user->toArray());

				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;
			
			// Route: /mpd/control/crossfade
			case 'crossfade':
			
				$this->firephp->log(Request::get('crossfade'), "setting crossfade");
				$this->xMPD->crossfade(Request::get('crossfade'));

				// Sanity check to see if crossfade was set properly
				$this->firephp->log($this->xMPD->status(array()), "xMPD->status()");
				break;

			// Route: /mpd/control/mixrampdb
			case 'mixrampdb':

				$this->xMPD->mixrampdb(Request::get('mixrampdb'));

				// Sanity check to see if mixrampdb was set properly
				$this->firephp->log($this->xMPD->status(array()), "xMPD->status()");
				break;

			// Route: /mpd/control/mixrampdelay
			case 'mixrampdelay':

				$this->firephp->log(Request::get('mixrampdelay'), "setting mixrampdelay");
				$this->xMPD->mixrampdelay(Request::get('mixrampdelay'));

				// Sanity check to see if mixrampdelay was set properly
				$this->firephp->log($this->xMPD->status(array()), "xMPD->status()");
				break;

			// Route: /mpd/control/shuffle
			case 'random':

				$this->xMPD->random(Request::get('random'));
	
				$this->firephp->log($this->xMPD->playlist, "newly shuffled playlist");
				break;

			// Route: /mpd/control/repeat
			case 'repeat':

				$repeat = Request::get('repeat');

				$this->xMPD->repeat($repeat);

				if ($repeat) {

					if (Request::has('option')) {

						if (Request::get('option') == 'track') {

							// Single mode in combination with repeat means repeat a single track
							$this->xMPD->single(1);

						} else {

							// We'll want to turn off single if repeating playlist
							$this->xMPD->single(0);
						}
					}

				} else {

					// We'll want to turn off single if repeat was turned off
					$this->xMPD->single(0);
				}

				break;

			// Route: /mpd/control/refresh
			case 'update':

				$this->xMPD->refresh();
				break;

			// Route: /mpd/control/status
			case 'status':
				
				$this->xMPD->status();

				break;

			// Route: /mpd/control/volume
			case 'volume':
				
				$this->firephp->log(Request::get('volume'), "setting volume");
				$this->xMPD->setvol(Request::get('volume'));

				$this->firephp->log($this->xMPD->status(array()), "xMPD->status()");
				break;

			// Route: /mpd/control/mute
			case 'mute':

				$this->xMPD->setvol(1);
				break;

			// Route: /mpd/control/reset
			case 'reset':

				$this->xMPD->seek(0);
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
				$this->xMPD->save($playlistName);
				break;

			// Route: /mpd/playlist/delete
                        case 'delete':
                                $this->xMPD->rm($playlistName);
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

                                        $this->addAlbums($artist);

				} else if ( $what == "track" ) {

					$file = Request::get( 'file' );

					$this->firephp->log( $file, "file" );

					if (!$playlistName) {

						$this->xMPD->add( $file );

					} else {

						$this->xMPD->playlistadd( $playlistName, $file );
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
						
						$this->addTracks($source, $name);
					}

				} else if ( $what == "url" ) {

					$stationUrl = Request::get('station_url');

					$this->xMPD->add($stationUrl);

				} else {
					// nothing else yet
				}

				// Refresh all the properties of the xMPD object
				$this->xMPD->RefreshInfo();

				$playlist = array('tracks' => array());

				// playlistName = "current", tracksListedSoFar = 0, tracksToRetrieve = "all", and context = "sync" 
				$playlist['tracks'] = $this->getPlaylistTracks("current", 0, "all", "sync");

				// How many items were retrieved (not used currently, but perhaps it will be useful for something)
				$justRetrieved = $playlist['tracks']['count'];

				// We don't need the count in the json part of the array anymore
				unset($playlist['tracks']['count']);

				$message["msg"] = array(	"action"=>$operation,
								"playlist"=>$playlist,
								"user"=>$this->user->toArray());

				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;

			// Route: /mpd/playlist/clear
			case 'clear':
				
				$this->xMPD->clear();

				// Refresh all the properties of the xMPD object
				$this->xMPD->RefreshInfo();

				$this->firephp->log($this->xMPD->playlist, "playlist");

				$message["msg"] = array(	"action"=>$operation,
								"playlist"=>$this->xMPD->playlist,
								"user"=>$this->user->toArray());

				// Publish the new track to all listeners of the station through the WebSocket
				Latchet::publish('radio/station/'.$this->data['station']->id, $message);

				break;

			// Route: /mpd/playlist/move/track
			case 'move':

                                if ( $what == "track" ) {

					$fromPos       = Request::get( 'from_pos' );
					$toPos         = Request::get( 'to_pos' );
                                                
					$this->firephp->log( $fromPos, "from_pos" );
					$this->firephp->log( $toPos, "to_pos" );

					if ( $playlistName ) {

						$this->xMPD->playlistmove( $playlistName, $fromPos, $toPos );

					} else {
	
						$this->xMPD->move( $fromPos, $toPos );
                                	}
				}								

				break;
			
			// Route: /mpd/playlist/remove/track
			case 'remove':

				if ( $what == "track" ) {

					if ( $playlistName ) {

						$pos = Request::get( 'pos' );

						$this->firephp->log( $pos, "pos" );

						$this->xMPD->playlistdelete( $playlistName, $pos );

					} else {

						$index	= Request::get('index');
						$id	= Request::get('id');
	
						if ( $this->xMPD->delete( $index )) {

							$result = array_merge( $result, array( 'index'=>$index, 'id'=>$id ));
						}
					}
				}

				break;
		}

		$this->firephp->log(json_encode($result), "json encoded result");
		
		echo json_encode($result);
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

				$filepaths = $this->xMPD->listplaylist($name);
		
				break;

			case 'album' :

				$tracks = $this->xMPD->find("album", $name);

				// Get an array of file paths from the tracks array
				$filepaths = array_column($tracks, "file");

				break;

			default :

				// Don't know of any scenario at this point
				break;
		}
		
		$this->xMPD->PLAddBulk( $filepaths );

		return $result;
	}

	public function addAlbums($artist){

		// Default this to just the action, whether or not it was successful and a blank message
		$result = [ 'action'=>'addAlbums', 'success'=>true, 'message'=>'' ];

	    	$artistsAlbums = $this->xMPD->list("album", $artist);

	    	$this->firephp->log($artistsAlbums, "artistsAlbums");

	    	$filepaths = array();

	    	foreach($artistsAlbums as $albumName){

			$albumTracks = $this->xMPD->find("album", $albumName);

			// Merge the filepaths taken from the 'file' column of the album_tracks array
			$filepaths = array_merge($filepaths, array_column($albumTracks, "file"));
	    	}

		$this->firephp->log($filepaths, "album_tracks");

		$this->xMPD->PLAddBulk( $filepaths );

		return $result;
	}
}
