function consoleLog( msg, obj ) {

	obj = obj || "";
	
	if( 'console' in window && 'log' in window.console && debug ) {

		console.log( msg );
		
		if ( obj != "" ) {

			console.log( obj );
		}
	}
}

function processIncomingMessage( msg ) {

	if (typeof msg.action != 'undefined') {

		switch( msg.action ) {

			case 'idle' :

				if( typeof msg.track != 'undefined' ) {	

					// We don't want idle events messing with our player head
					if(( track_position != msg.track.Pos ) && ( !waiting )) {
					
						movePlayerHead( msg.track );
					}
				}

				break;
				
			case 'play' :
			case 'previous' :
			case 'next' :
			case 'skip' :
				
				if( typeof msg.track != 'undefined' ) {	

					movePlayerHead( msg.track );
				}

				break;

			case 'add' :
			case 'clear' :
	
				if( typeof msg.playlist != 'undefined' ) {

					syncPlaylist( msg.playlist );
				}

			default :

				break;
		}
	}
}

function movePlayerHead( newTrack ) {
	
	consoleLog("Moving playerHead to newTrack");
	consoleLog( newTrack );

	// Reset the play progress tracker
	resetProgress();

	// Set the global variable that tracks position within playlist
	track_position = newTrack.Pos;

	// Update the album art and track info as well as the player progress divs
	updateCurrentTrackInfo( playlist.tracks[ track_position ] );

	// Default trackStart to zero
	trackStart = 0;

	if ( typeof newTrack.seekTo != 'undefined' ) {

		// If the newTrack has seekTo set, then let's start from there	
		trackStart = newTrack.seekTo;

		// Set the global elapsed variable so that jPlayer can resume from there if needed
		elapsed = trackStart;
	}

	// Set the length of the song for the progress watch
	trackEnd = newTrack.Time;

	consoleLog("track event calling startProgress("+trackStart+", "+trackEnd+")");

	// Start the play progress tracker and display updater
	startProgress(trackStart, trackEnd);
}

function syncPlaylist( updatedPlaylist ) {

	consoleLog("Syncing playlist to updatedPlaylist");

	if ( typeof updatedPlaylist.tracks != 'undefined' ) {

		if ( updatedPlaylist.tracks.length > 0 ) {
	
			// Update the global playlist	
			playlist = updatedPlaylist;
		}
	}
}

function onUpdate(topicUri, event) {
   
	consoleLog(topicUri);
	consoleLog(event);

	if (typeof event.topic != 'undefined') {

		if (event.topic.match(/^radio\/station\/[0-9]+$/i)) {
	
			if (typeof event.msg != 'undefined') {
	
				processIncomingMessage( event.msg );
			}

			// Process the update		
			
			//$.mobile.loading( "show" ); 
   			//$.mobile.loadingMessage = "Switching track";

			if (playing) {

				//alert(JSON.stringify(event.msg.playlist));

	   			//initialize_timer_display();

				/*if ( typeof playlist.tracks === 'undefined' ) {
		
					return false;
				}

				// A track object will be sent in the msg object if a Play, Next, Previous or Skip operation was performed
				if ( typeof event.msg.track != 'undefined' ) {
	
					// Reset the play progress tracker
					resetProgress();

					// Set the global variable that tracks position within playlist
					track_position = event.msg.track.Pos;

					// Update the album art and track info as well as the player progress divs
					updateCurrentTrackInfo( playlist.tracks[ track_position ] );

					// Default trackStart to zero
					trackStart = 0;

					if ( typeof event.msg.track.seekTo != 'undefined' ) {

						trackStart = event.msg.track.seekTo;
					}

					// Set the length of the song for the progress watch
					trackEnd = event.msg.track.Time;

					consoleLog("track event calling startProgress("+trackStart+", "+trackEnd+")");

					// Start the play progress tracker and display updater
					startProgress(trackStart, trackEnd);
				}

				// A player object will be sent in the msg object if an idle event from the player subsystem was received
				if ( typeof event.msg.update != 'undefined' ) {

					// Reset the play progress tracker
					resetProgress();

					// Set the global variable that tracks position within playlist
					track_position = event.msg.update.song;
	
					// Update the album art and track info as well as the player progress divs
					updateCurrentTrackInfo( playlist.tracks[ event.msg.update.song ] );

					// Default trackStart to elapsed
					trackStart = event.msg.update.elapsed;				
					//trackStart = 0;
					//elapsed = 0;

					// Set the length of the song for the progress watch
					trackEnd = playlist.tracks[ event.msg.update.song ].Time;

					consoleLog("player event - calling startProgress("+trackStart+", "+trackEnd+")");
		
					// Start the play progress tracker and display updater
					startProgress(trackStart, trackEnd);
				}*/
			}
		}	
	}
}
