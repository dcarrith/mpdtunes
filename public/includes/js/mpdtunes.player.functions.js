function get_timer_display( timer_input ) {
	
	minutes = "-";
	seconds = "--";

	time_display = minutes+':'+seconds;

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

	if ( timer_input > 0 ) {

		minutes = Math.floor( timer_input / 60 );

		seconds = Math.floor( timer_input % 60 );

		if ( seconds < 10 ) {
			
			seconds = "0" + seconds;
		}
	}

	time_display = minutes+':'+seconds;

	if (( time_display == "0:01" ) || ( time_display == "0:00" )) {

		time_display = "-:--";
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

	try {

		if ( out_volume_left_to_fade > 0 ) {

			out_faded_volume = ( out_volume_left_to_fade / volume_fade );

			consoleLog( "Setting " + activePlayerSelector + " volume to: " + out_faded_volume );

			$( activePlayerSelector ).jPlayer( "volume", out_faded_volume );

			out_volume_left_to_fade--;
		}

	} catch (err){
		
		/*txt="An error occurred in the 'fade_out_current_track' function:\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);*/
	}
}

function fade_in_next_track () {

	try {

		if ((( volume_fade - in_volume_left_to_fade ) > 0 ) && ( in_volume_left_to_fade >= 0 )) {

			in_faded_volume = (( volume_fade - in_volume_left_to_fade ) / volume_fade );

			consoleLog( "Setting " + activePlayerSelector + " volume to: " + out_faded_volume );

			$( activePlayerSelector ).jPlayer( "volume", out_faded_volume );

			in_volume_left_to_fade--;
		}

	} catch (err){
		
		/*txt="An error occurred in the 'fade_in_next_track' function:\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);*/
	}
}

function play_station_stream( station_id ) {

	$.ajax({
	   type: "POST",
	   url: "/stations/get_station_details",
	   async: true,
	   data: "station_id="+station_id,
	   success: function(msg){

			result = $.parseJSON(msg);

			station = result.station[0];

			//alert("station.id: "+station.id+"\nstation.name: "+station.name+"\nstation.description: "+station.description+"\nstation.url: "+station.url+"\nstation.icon: "+station.icon+"\nstation.icon_path: "+station.icon_path+"\nstation.icon_url: "+station.icon_url);

			if ( typeof station !== 'undefined' ) {

				// set the src of the album art image to be the station icon
				$( '#jukebox .albumart' ).attr( "src", station.icon_url + station.icon );

				// set the info about the station being streamed then trigger updatelayout in case the new content messed something up
				$( '#artistDiv' ).html( station.name );
				$( '#albumDiv' ).html( station.description );
				$( '#trackDiv' ).html( station.url ).trigger( 'updatelayout' );

				$( inactivePlayerSelector ).jPlayer( 'setMedia', {mp3 : station.url} );
				$( inactivePlayerSelector ).jPlayer( 'play' );
	
				consoleLog( "Checking to see if srcSet is true or false", $( activePlayerSelector ).data( "jPlayer" ).status );
	
				// We only want to try and stop playerOne if it has it's src set to something
				if ( $( activePlayerSelector ).data( "jPlayer" ).status.srcSet ) {
		
					$( activePlayerSelector ).jPlayer('stop');
				}

				tmpPlayerSelector = activePlayerSelector;
				activePlayerSelector = inactivePlayerSelector;
				inactivePlayerSelector = tmpPlayerSelector;

				primary_player = (( primary_player == 1 ) ? 2 : 1);

				updatePlayerDisplay( playing );
				
				//initialize_timer_display();

				$('#streamProgressDiv').css('width', "100%");
				$('#streamProgressDiv').css('background', 'rgba(0, 0, 0, .4)');

				playing = true;

				// send the command to the MPD server to skip to the track that's next in the shuffle
				post = { "parameters" : [ { "station_url" : station.url } ] }; 
				control_mpd('add_url', post.parameters[0]);

			} else {
				
				// show error dialog of some kind
			}
	   }
	});
}

// This updates the play progress indicators in the footer on the home page
function updatePlayProgressDisplay( currentTime, totalDuration, playProgress ) {
	
	$( '#trackTotalDuration' ).html( get_timer_display( totalDuration ));

	$( '#playProgressDiv' ).css( 'width', playProgress+'%' );

	$( '#trackPlayDuration' ).html( get_timer_display( currentTime ));
}

// This updates the load progress indicator in the footer on the home page
function updateLoadProgressDisplay( loaded ) {

	consoleLog( "Inside updateLoadProgressDisplay - setting loadProgressDiv to ", loaded );

	$( '#loadProgressDiv' ).css( 'width', loaded+'%' );	

	/*trackProgressDivWidth = parseInt( $( '#trackProgressDiv' ).css( 'width' ).replace(/px/,'') );
	loadProgressDivWidth = parseInt( $( '#loadProgressDiv' ).css( 'width' ).replace(/px/,'') );
	loadProgressPercentage = ( loadProgressDivWidth / trackProgressDivWidth ) * 100;

	if ( loadProgressPercentage < 100 ) {

		consoleLog( "Inside updateLoadProgressDisplay and checking trackProgressDivWidth", trackProgressDivWidth );
		consoleLog( "Inside updateLoadProgressDisplay and checking loadProgressDivWidth", loadProgressDivWidth );
		consoleLog( "Inside updateLoadProgressDisplay and checking loadProgressPercentage", loadProgressPercentage );
	}*/
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
			}

			break;
	}

	return nextTrackPosition;	
}

function processTimeUpdate( currentTime, totalDuration, playProgress ) {

	if( currentTime > 1 ) {

		$.mobile.hidePageLoadingMsg(); 
		$.mobile.loadingMessage = "Loading";
	}

	updatePlayProgressDisplay( currentTime, totalDuration, playProgress );

	if (( totalDuration > 0 ) && ( currentTime > 0 )) {

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
	}
}

function updatePlayerDisplay( playing, action ) {

	action = action || "play";
	
	if (( playing ) && ( action == "pause" )) {

		$( '#jukebox #playpause' ).attr( 'data-icon', 'play' );
		$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );

		$( '#playpause-span-one' ).attr( 'class', 'play ui-btn-inner ui-btn-corner-all' );
		$( '#playpause-span-two' ).attr( 'class', 'play ui-btn-text' );
		$( '#playpause-span-three' ).attr( 'class', 'play-inner ui-icon ui-icon-play ui-icon-shadow' );

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

		if ( $( '#playerCurrentlyPlayingDiv' ).css( 'display' ) == 'none' ) {

			// adjust the image sprite to show the pause button instead of the play button, since the user just clicked play
			$( '#jukebox #playpause' ).attr( 'data-icon', 'pause' );
			$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );

			$( '#playpause-span-one' ).attr( 'class', 'pause ui-btn-inner ui-btn-corner-all' );
    			$( '#playpause-span-two' ).attr( 'class', 'pause ui-btn-text' );
    			$( '#playpause-span-three' ).attr( 'class', 'pause-inner ui-icon ui-icon-pause ui-icon-shadow' );

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
	
		} else {

			// adjust the image sprite to show the pause button instead of the play button, since the user just clicked play
			$( '#jukebox #playpause' ).attr( 'data-icon', 'pause' );
			$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );

			$( '#playpause-span-one' ).attr( 'class', 'pause ui-btn-inner ui-btn-corner-all' );
    			$( '#playpause-span-two' ).attr( 'class', 'pause ui-btn-text' );
    			$( '#playpause-span-three' ).attr( 'class', 'pause-inner ui-icon ui-icon-pause ui-icon-shadow' );
		}
	}
}

function skipto( destination ){

	$.mobile.hidePageLoadingMsg(); 
   	$.mobile.loadingMessage = "Loading";

   	initialize_timer_display();

	if ( typeof playlist.tracks === 'undefined' ) {
		
		return false;
	}

	// Reset the load progress complete tracker
	load_progress_complete = false;
	
	if ( next_track_already_added ) {

		// We only want to try and stop playerOne if it has it's src set to something
		if ( $( inactivePlayerSelector ).data( "jPlayer" ).status.srcSet ) {
		
			$( inactivePlayerSelector ).jPlayer('stop');
		}

		$( inactivePlayerSelector ).jPlayer( "volume", 1 );
		$( activePlayerSelector ).jPlayer( "volume", 1 );
	}

	track_position = getNextTrackPosition( destination, track_position, playlist.tracks.length );

	// Update the album art and track info as well as the player progress divs
	updateCurrentTrackInfo( playlist.tracks[ track_position ] );

	// Show the track info div if it's hidden and update the play/pause button
	updatePlayerDisplay( playing );

	$( inactivePlayerSelector ).jPlayer( 'setMedia', {mp3 : playlist.tracks[ track_position ].url} );
	$( inactivePlayerSelector ).jPlayer( 'play' );
	
	consoleLog( "Checking to see if srcSet is true or false", $( activePlayerSelector ).data( "jPlayer" ).status );
	
	// We only want to try and stop playerOne if it has it's src set to something
	if ( $( activePlayerSelector ).data( "jPlayer" ).status.srcSet ) {
		
		$( activePlayerSelector ).jPlayer('stop');
	}

	tmpPlayerSelector = activePlayerSelector;
	activePlayerSelector = inactivePlayerSelector;
	inactivePlayerSelector = tmpPlayerSelector;

	primary_player = (( primary_player == 1 ) ? 2 : 1);

	if ( shuffle_queue ) {

		// send the command to the MPD server to skip to the track that's next in the shuffle
		post = { "parameters" : [ { "index" : playlist.tracks[ track_position ].mpd_index } ] }; 
		control_mpd( 'skip_to', post.parameters[ 0 ] );

	} else {

		if ( destination == "next" ) {

			// send the command to the MPD server to move to the next track
			control_mpd( 'next' );

		} else if ( destination == "previous" ) {

			// send the command to the MPD server to move to the next track
			control_mpd( 'prev' );
		
		} else if ( destination == "beginning" ) {

			// go to the beginning of the playlist		

		} else {

			// whatever

			if( Math.floor( destination ) == destination && $.isNumeric( destination )) {	
	
    				var currentart = document.getElementById( "currentalbumart" );
    				var currently = document.getElementById( "currentlyPlayingInfoDiv" );
    				var playerCurrently = document.getElementById( "playerCurrentlyPlayingDiv" );

    				if (( typeof currently !== 'undefined' ) && ( currently != '' ) && ( currently != null )) {

					$( '.currentalbumart' ).attr( 'src', playlist.tracks[ track_position ].art );
					$( '#currentlyPlayingArtistDiv' ).html( playlist.tracks[ track_position ].artist );
					$( '#currentlyPlayingAlbumDiv' ).html( playlist.tracks[ track_position ].album );
					$( '#currentlyPlayingTrackDiv' ).html( playlist.tracks[ track_position ].title ).trigger( 'updatelayout' );

					// slide down the info about currently playing track
					$( '#currentlyPlayingInfoDiv' ).slideDown({

						duration: default_easin_duration,
						easing: default_easin_equation,
						complete:function(){
			
							//$.mobile.fixedToolbars.show();
						}
					});
				}
	
				bumped_file = playlist.tracks[ track_position ].file;

				if ( bumped_file.indexOf( "http://" ) == 0 ) {

					$( '#streamProgressDiv' ).css( 'width', "100%" );
					$( '#streamProgressDiv' ).css( 'background-color', '#000000' );
		
				} else {

					$( '#streamProgressDiv' ).css( 'width', "0px" );
					$( '#streamProgressDiv' ).css( 'background-color', '#000000' );
				}

				$( '#currentlyPlayingArtistDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
				$( '#currentlyPlayingAlbumDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
				$( '#currentlyPlayingTrackDiv' ).css( 'max-width', ( $( window ).width() * .6 ) );
		
				post = { "parameters" : [ { "index" : track_position } ] }; 
				control_mpd( 'skip_to', post.parameters[0] );
			}
		}
	}

	if ( !playing ) {

		playing = true;
	}
}

function updateCurrentTrackInfo( currentTrack ){

	//alert(JSON.stringify( currentTrack ));

	consoleLog( "Trying to set the info about the song being played using the track object", currentTrack );

	// set the info about the song being played
	$( '#currentAlbumArtImg' ).attr( 'src', currentTrack.art );
	$( '#artistDiv' ).html( currentTrack.artist );

	var fullAlbum = currentTrack.album;
	var truncatedAlbum = ((fullAlbum.length > max_album_name_string_length) ? (fullAlbum.substring(0, max_album_name_string_length)+'...') : fullAlbum);
	$( '#albumDiv' ).html( truncatedAlbum ).trigger( 'updatelayout' );

	if ( currentTrack.url.indexOf( "http://" ) === 0) {
			
		$( '#trackDiv' ).html( currentTrack.url ).trigger( 'updatelayout' );

	} else {

		var fullTitle = currentTrack.title;
		var truncatedTitle = ((fullTitle.length > max_track_title_string_length) ? (fullTitle.substring(0, max_track_title_string_length)+'...') : fullTitle);
		$( '#trackDiv' ).html( truncatedTitle ).trigger( 'updatelayout' );
	}

	$( '#playerCurrentlyPlayingDiv' ).css( 'max-width', ( $( window ).width() * .9 ) );

	if ( currentTrack.file.indexOf( "http://" ) == 0 ) {

		$( '#streamProgressDiv' ).css( 'width', "100%" );
		$( '#streamProgressDiv' ).css( 'background', 'rgba(0, 0, 0, .4)' );
	} else {

		$( '#streamProgressDiv' ).css( 'width', "0" );
	} 
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

function consoleLog( msg, obj ) {

	obj = obj || "";
	
	if( 'console' in window && 'log' in window.console && debug ) {

		console.log( msg );
		
		if ( obj != "" ) {

			console.log( obj );
		}
	}
}

