
//var trackStart = 0;
//var trackEnd = 0;
var trackProgress;

var started = false;
var waiting = false;
var elapsed = 0;

window.onerror = function(){
   return true;
}

function startProgress(trackStart, trackEnd) {

	consoleLog("inside startProgress, trackStart: "+trackStart+" and trackEnd: "+trackEnd);
	// Globals: elapsed and started

	// We only want to start it once
	if (started === false) {

		// Store the fact that we started
		started = true;

		// If trackStart is greater than zero, then we must be resuming the track 
		if (trackStart > 0) {

			// Override elapsed with whatever trackStart was set to
			elapsed = trackStart;
		}

		//consoleLog( "calling setInterval which will call updatePlayProgressDisplay( "+elapsed+", "+trackEnd+", "+((elapsed / trackEnd ) * 100)+")");

		// Run the callback every second
		trackProgress = setInterval( function() {

			//alert("elapsed: "+elapsed+"\ntrackEnd: "+trackEnd+"\nProgress: "+((elapsed / trackEnd) * 100));

			//consoleLog( "calling updatePlayProgressDisplay( "+elapsed+", "+trackEnd+", "+((elapsed / trackEnd ) * 100)+")");

			// Update the track progress time, total length and progress bar percentage played
			updatePlayProgressDisplay( elapsed, trackEnd, (( elapsed / trackEnd ) * 100 ) );  

			// Increment the elapsed seconds
			elapsed++;

		}, 1000);
	}
}

function pauseProgress() {

	// Clear the trackProgress interval
	window.clearInterval(trackProgress);

	// Store the fact tht it's no longer running
	started = false;	
}

function resetProgress() {

	// Clear the trackProgress interval
	window.clearInterval(trackProgress);

	// Reset the elapsed time since we stopped the timer
	elapsed = 0;

	// Store the fact that it's no longer running
	started = false;
}

function get_timer_display( timer_input ) {

	hours = "-";
	minutes = "-";
	seconds = "--";

	time_display = hours+':'+minutes+':'+seconds;

	if ( timer_input == "Infinity:NaN" ) {
		
		time_display = "<font style='font-size:12px;'>∞</font>";

		var now = new Date();

		var backgroundColor = "#FFFFFF";

		if ( now.getSeconds() % 2 == 0 ) {

			backgroundColor = "#FFFFFF";

		} else {

			backgroundColor = "#000000";
		}

		$( '#streamProgressDiv' ).css( 'background-color', backgroundColor );

		return time_display;
	}

	if ( timer_input >= 0 ) {

		// Create a date just based on the month/day/year (so time will be 00:00:00)
		var epoch = Date.parse(new Date().toDateString());

		// Add the timer input in milliseconds to our rounded down epoch date 
		var time = new Date(parseInt(epoch) + (parseInt(timer_input) * 1000));

		// Get the time display which will be in the format 00:00:00 GMT-0500 (EST)
		time_display = time.toTimeString();

		// We don't want the GMT-0500 (EST) part, so we'll just take the part up to the first space
		time_display = time_display.substr(0, time_display.indexOf(' '));
	
		if ( parseInt( time.getHours()) == 0 ) {

			// We don't want to display 00 for hours since this will usually be the case
			time_display = time_display.substr( (time_display.indexOf(':') + 1), 5 );
		}

		//consoleLog(time_display);
	}

	return time_display;
}

function initialize_timer_display() {
	
	$( '#trackPlayDuration' ).html( '-:--' );

	$( '#playProgressDiv' ).css( 'width', 0 );
	$( '#playProgressDiv' ).css( 'background-color', '#FFFFFF' );

	$( '#loadProgressDiv' ).css( 'width', '100%' );
	$( '#loadProgressDiv' ).css( 'background', 'rgba(0, 0, 0, .4)' );
	$( '#loadProgressDiv' ).css( 'border', '1px inset #888888' );

	$( '#trackTotalDuration' ).html( '-:--' );
}

function fade_out_current_track () {

	if ( out_volume_left_to_fade > 0 ) {

		out_faded_volume = ( out_volume_left_to_fade / volume_fade );

		consoleLog( "Setting player volume to: " + out_faded_volume );

		$( "#player" ).jPlayer( "volume", out_faded_volume );

		out_volume_left_to_fade--;
	}
}

function fade_in_next_track () {

	if ((( volume_fade - in_volume_left_to_fade ) > 0 ) && ( in_volume_left_to_fade >= 0 )) {

		in_faded_volume = (( volume_fade - in_volume_left_to_fade ) / volume_fade );

		consoleLog( "Setting player volume to: " + out_faded_volume );

		$( "#player" ).jPlayer( "volume", out_faded_volume );

		in_volume_left_to_fade--;
	}
}

// This updates the play progress indicators in the footer on the home page
function updatePlayProgressDisplay( currentTime, totalDuration, playProgress ) {

	//alert("Inside updatePlayProgressDisplay\ntime: "+time+"\ncurrent: "+current+"\nprogress: "+progress);
	
	$( '#trackTotalDuration' ).html( get_timer_display( totalDuration ));

	$( '#playProgressDiv' ).css( 'width', playProgress+'%' );

	$( '#trackPlayDuration' ).html( get_timer_display( currentTime ));
}

// This updates the load progress indicator in the footer on the home page
function updateLoadProgressDisplay( loaded ) {

	//consoleLog( "Inside updateLoadProgressDisplay - setting loadProgressDiv to ", loaded );

	$( '#loadProgressDiv' ).css( 'width', loaded+'%' );	
}

function getNextTrackPosition( destination, currentTrackPosition, currentPlaylistLength ) {
	
	consoleLog( "Inside getNextTrackPosition - destination ", destination );
	consoleLog( "Inside getNextTrackPosition - currentTrackPosition ", "'" + currentTrackPosition + "'" );
	consoleLog( "Inside getNextTrackPosition - currentPlaylistLength ", currentPlaylistLength ); 
	consoleLog( "Inside getNextTrackPosition - currentPlaylistLength minus 1 ", (currentPlaylistLength - 1) ); 

	var nextTrackPosition = currentTrackPosition;

	switch( destination ) {

		case "next" :

			// if the current track position is the same as the total size of the playlist, then start over
			if ( currentTrackPosition == ( currentPlaylistLength - 1 )) {
					
				// Loop around to the beginning again
				nextTrackPosition = 0;
			
			} else {

				// This should work whether or not the queue has been shuffled
				nextTrackPosition++;
			}
			
			break;
			
		case "previous" :

			if ( currentTrackPosition == 0 ) {
	
				// Loop back to the last song in the playlist
				nextTrackPosition = currentPlaylistLength - 1;

			} else {
			
				// This should work whether or not the queue has been shuffled
				nextTrackPosition--;
			}

			break;

		case "beginning" :

			// start from the beginning of the playlist
			nextTrackPosition = 0;

			break;

		case "same" :
 
			// Leave track position as it is

			break;

		default :

			if(( Math.floor( destination ) == destination ) && ( $.isNumeric( destination ))) {

				// If destination is numeric, then it should be a specific track number to which we should skip
				nextTrackPosition = destination;
			
			} else {

				nextTrackPosition = false;
			}

			break;
	}

	return nextTrackPosition;	
}

function processTimeUpdate( currentTime, totalDuration, playProgress ) {

	// This hides the playing message
	if( currentTime > 1 ) {

		$.mobile.loading( "hide" ); 
		$.mobile.loadingMessage = "Loading";
	}

	updatePlayProgressDisplay( currentTime, totalDuration, playProgress );

	/*if (( totalDuration > 0 ) && ( currentTime > 0 ) && (!repeat_track)) {

		// Crossfade into the next song rather than just waiting for the 'ended' event to trigger next
		if (	(( totalDuration - currentTime ) <= max_crossfade ) && 
			(( totalDuration > 0 ) && ( currentTime > 0 )) && 
			( !next_track_already_added )	) {
	
			next_track_already_added = true;

			// start fading out current track
			fade_out_current_track();

			track_position = getNextTrackPosition( "next", track_position, playlist.tracks.length );
	
			// Reset the variable that holds the current track position from the server
			current_track_position = 0;

			alert("inside processTimeUpdate calling setMedia");

			// Get the next track spun up on the secondary player which should be inactive and ready to load
			$( inactivePlayerSelector ).jPlayer( "setMedia", {mp3 : playlist.tracks[ track_position ].url } );	
			$( inactivePlayerSelector ).jPlayer( "play", current_track_position );
			
			// start fading in next track
			fade_in_next_track();

		} else {
	
			if (( totalDuration - currentTime ) <= max_crossfade ) {
	
				// continue fading out current track
				fade_out_current_track();

				// continue fading in next track
				fade_in_next_track();
			}
		}
	}*/
}

function updatePlayerDisplay( playing, action ) {

	action = action || "play";
	
	if (( playing ) && ( action == "pause" )) {

		// adjust the image sprite to show the play button instead of the pause button, since the user just clicked pause
		$( '#jukebox #playpause' ).attr( 'data-icon', 'play' );
		$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );
		$( '#jukebox #playpause' ).removeClass( 'ui-icon-pause' ).addClass( 'ui-icon-play' );
		
		$( "#playerCurrentlyPlayingDiv" ).slideUp({

			duration: default_easout_duration,
			easing: default_easout_equation,
			complete:function(){

				$( "#trackProgressDiv" ).slideUp({

					duration: default_easout_duration,
					easing: default_easout_equation,
					complete:function(){

					}
				});
			}
		});
	
	} else {

		// adjust the image sprite to show the pause button instead of the play button, since the user just clicked play
		$( '#jukebox #playpause' ).attr( 'data-icon', 'pause' );
		$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );
		$( '#jukebox #playpause' ).removeClass( 'ui-icon-play' ).addClass( 'ui-icon-pause' );

		if ( $( '#playerCurrentlyPlayingDiv' ).css( 'display' ) == 'none' ) {

			// use the oncomplete function to adjust the fixed toolbars after the sideDown event finishes
			$( '#playerCurrentlyPlayingDiv' ).slideDown({

				duration: default_easin_duration,
				easing: default_easin_equation,
				complete:function(){

					$( '#trackProgressDiv' ).slideDown({

						duration: default_easin_duration,
						easing: default_easin_equation,
						complete:function(){		
							// done
						}
					});
				}
			});
		}	
	}
}

function skipto( destination, position ){

	trackStart = 0 | position;

	if (trackStart == 0) {

		elapsed = 0;
	}

	$.mobile.loading( "hide" ); 
   	$.mobile.loadingMessage = "Loading";

	if ( typeof playlist.tracks === 'undefined' ) {
		
		return false;
	}

	// Reset the load progress complete tracker
	load_progress_complete = false;
	
	/*if ( next_track_already_added ) {

		// We only want to try and stop the player if it has it's src set to something
		if ( $( inactivePlayerSelector ).data( "jPlayer" ).status.srcSet ) {
		
			$( inactivePlayerSelector ).jPlayer('stop');
		}

		$( inactivePlayerSelector ).jPlayer( "volume", 1 );
		$( activePlayerSelector ).jPlayer( "volume", 1 );
	}*/

	track_position = getNextTrackPosition( destination, track_position, playlist.tracks.length );

	// Update the album art and track info as well as the player progress divs
	updateCurrentTrackInfo( playlist.tracks[ track_position ] );

	// Set the length of the song for the progress watch
	trackEnd = playlist.tracks[ track_position ].Time;

	//alert("calling updatePlayerDisplay("+playing+")");

	// Show the track info div if it's hidden and update the play/pause button
	updatePlayerDisplay( playing );

	//startProgress( trackStart, trackEnd );

	/*alert("inside skipto calling setMedia");

	$( inactivePlayerSelector ).jPlayer( 'setMedia', {mp3 : playlist.tracks[ track_position ].url} );
	$( inactivePlayerSelector ).jPlayer( 'play' );
	*/

	consoleLog( "Checking to see if the player's srcSet is true or false", $( "#player" ).data( "jPlayer" ).status );
	
	// We only need to set the player source to the users stream if it's not already set
	if ( !$( "#player" ).data( "jPlayer" ).status.srcSet ) {

		$( "#player" ).jPlayer( 'setMedia', {mp3 : usersStream } );
	}

	/*tmpPlayerSelector = activePlayerSelector;
	activePlayerSelector = inactivePlayerSelector;
	inactivePlayerSelector = tmpPlayerSelector;

	primary_player = (( primary_player == 1 ) ? 2 : 1);
	*/

	/*if ( shuffle_queue ) {

		// send the command to the MPD server to skip to the track that's next in the shuffle
		post = { "parameters" : [ { "index" : playlist.tracks[ track_position ].mpd_index } ] }; 
		control_mpd( 'skip', post.parameters[ 0 ] );

	} else {*/

		if (destination == "same") {

			if (trackStart == 0) {
	
				// send the command to the MPD server to play the current track
				control_mpd( 'play' );

			} else {

				// send the command to the MPD server to play the current track at the specified position
				post = { "parameters" : [ { "position" : trackStart } ] }; 
				control_mpd( 'play', post.parameters[0] );
			}

		} else if ( destination == "next" ) {

   			initialize_timer_display();

			// send the command to the MPD server to move to the next track
			control_mpd( 'next' );

		} else if ( destination == "previous" ) {

   			initialize_timer_display();

			// send the command to the MPD server to move to the previous track
			control_mpd( 'previous' );
		
		} else if ( destination == "beginning" ) {

			// go to the beginning of the playlist		

		} else {

			//alert("track_position: "+track_position);

			// The destination must be a specific track position
			if( track_position ) {
			
				post = { "parameters" : [ { "index" : track_position } ] }; 
				control_mpd( 'skip', post.parameters[0] );
			}
		}
	//}

	if ( !playing ) {

		// If it's not playing, then let's make it play
		$( "#player" ).jPlayer( 'play' );	

		playing = true;
	}
}

function updateCurrentTrackInfo( currentTrack ){

	//alert(JSON.stringify( currentTrack ));

	consoleLog( "Trying to set the info about the song being played using the track object", currentTrack );

	// We need to check to see if these items exist because if so, then that means we're on the Queue page
    	var currentart = document.getElementById( "currentalbumart" );
    	//var currently = document.getElementById( "currentlyPlayingInfoDiv" );
    	var playerCurrently = document.getElementById( "playerCurrentlyPlayingDiv" );

	// If currently exists, then we must be on the queue page, so we need to update/show the track info div
    	/*if (( typeof currently !== 'undefined' ) && ( currently != '' ) && ( currently != null )) {

		$( '.currentalbumart' ).attr( 'src', currentTrack.art );
		$( '#currentlyPlayingArtistDiv' ).html( currentTrack.artist );
		$( '#currentlyPlayingAlbumDiv' ).html( currentTrack.album );
		$( '#currentlyPlayingTrackDiv' ).html( currentTrack.title ).trigger( 'updatelayout' );

		// slide down the info about currently playing track
		$( '#currentlyPlayingInfoDiv' ).slideDown({

			duration: default_easin_duration,
			easing: default_easin_equation,
			complete:function(){
			
				//$.mobile.fixedToolbars.show();
			}
		});
	}*/

	// set the info about the song being played
	$( '#currentAlbumArtImg' ).attr( 'src', currentTrack.Art );
	$( '#artistDiv' ).html( currentTrack.Artist );

	var fullAlbum = currentTrack.Album;
	var truncatedAlbum = ((fullAlbum.length > max_album_name_string_length) ? (fullAlbum.substring(0, max_album_name_string_length)+'...') : fullAlbum);
	$( '#albumDiv' ).html( truncatedAlbum ).trigger( 'updatelayout' );

	if ( currentTrack.file.indexOf( "http://" ) === 0) {
			
		$( '#trackDiv' ).html( currentTrack.file ).trigger( 'updatelayout' );

	} else {

		var fullTitle = currentTrack.Title;
		var truncatedTitle = ((fullTitle.length > max_track_title_string_length) ? (fullTitle.substring(0, max_track_title_string_length)+'...') : fullTitle);
		$( '#trackDiv' ).html( truncatedTitle ).trigger( 'updatelayout' );
	}

	$( '#playerCurrentlyPlayingDiv' ).css( 'max-width', ( $( window ).width() * .9 ) );

	/*if ( currentTrack.file.indexOf( "http://" ) === 0 ) {

		$( '#streamProgressDiv' ).css( 'width', "100%" );
		$( '#streamProgressDiv' ).css( 'background', 'rgba(0, 0, 0, .4)' );
	} else {

		$( '#streamProgressDiv' ).css( 'width', "0" );
	}*/

	// If currently exists, then we must be on the queue page, so we need to update/show the track info div
    	/*if (( typeof currently !== 'undefined' ) && ( currently != '' ) && ( currently != null )) {

		$( '#currentlyPlayingArtistDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
		$( '#currentlyPlayingAlbumDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
		$( '#currentlyPlayingTrackDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
	}*/
}

// Do the crossfading
function crossfadePlayers( crossfadeTime ) {

        var currentVolume = 90; //$("#" + secPlayerID).jPlayer("getData", "volume");
        var interval = crossfadeTime / currentVolume;

        // mute primary player
        $("#" + priPlayerID).jPlayer("volume",0);

        // secondary player fades out and stops
        $.timer(interval, function (timerOut) {
                var volume = $("#" + secPlayerID).jPlayer("getData", "volume") -1;
                $("#" + secPlayerID).jPlayer("volume",volume);
                if(volume < 1) {
                        $("#" + secPlayerID).jPlayer("stop");
                        timerOut.stop();
                }
        });
        $("#" + secPlayerID + '_elements').hide();

        // primary player fades in (twice as fast)
        $.timer(interval/2, function (timerIn) {
                var volume = $("#" + priPlayerID).jPlayer("getData", "volume") +2;
                $("#" + priPlayerID).jPlayer("volume",volume);
                if(volume > 90) timerIn.stop();
        });

        $("#" + priPlayerID + '_elements').show();
}
