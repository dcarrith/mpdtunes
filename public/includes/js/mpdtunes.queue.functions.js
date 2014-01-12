function clearQueue() {
	
	$.mobile.loading( "show" );

	track_position = 0;

	playlist = "";

	$( '#jukebox #playpause' ).attr( 'data-icon', 'play' );
	$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );

	$('#playpause-span-one').attr('class', 'play ui-btn-inner ui-btn-corner-all');
	$('#playpause-span-two').attr('class', 'play ui-btn-text');
	$('#playpause-span-three').attr('class', 'play-inner ui-icon ui-icon-play ui-icon-shadow');

	shuffle_queue = false;

	$('#shuffle').attr('data-theme', theme.buttons);
	$('#shuffle').attr('data-icon', "shuffle");
	$('#shuffle').removeClass($.mobile.activeBtnClass);

	if (primary_player == 1) {

		if( 'console' in window && 'log' in window.console ) {
						
			console.log( "Checking to see if playerOne's srcSet is true or false" );
			console.log( $( "#playerOne" ).data( "jPlayer" ).status );
		}
	
		// We only want to try and stop playerTwo if it has it's src set to something
		if ( $( "#playerOne" ).data( "jPlayer" ).status.srcSet ) {
		
			// Pause the primary player instance
			$( "#playerOne" ).jPlayer( "pause" );
			$( "#playerOne" ).jPlayer( "clearMedia" );
		}

	} else {

		if( 'console' in window && 'log' in window.console ) {
						
			console.log( "Checking to see if playerTwo's srcSet is true or false" );
			console.log( $( "#playerTwo" ).data( "jPlayer" ).status );
		}
	
		// We only want to try and stop playerTwo if it has it's src set to something
		if ( $( "#playerTwo" ).data( "jPlayer" ).status.srcSet ) {
		
			// Pause the secondary player instance
			$( "#playerTwo" ).jPlayer( "pause" );	
			$( "#playerTwo" ).jPlayer( "clearMedia" );
		}
	}

	// Reset the variable that holds the current track position from the server
	current_track_position = 0;

	// adjust the tracking variables so we know what state the player is in
	playing = false;
	paused = true;

	setTimeout(function(){  

		$('#trackProgressDiv').slideUp('slow');
		$('#playerCurrentlyPlayingDiv').slideUp('slow', function() {
		
			$.mobile.loading( "hide" );
		});

		initialize_timer_display();
	
	}, 1000);
}

function removeQueuedTrack( elementId, removedIndex ){
	
	// SlideUp the list element to hide it
	$( '#'+elementId ).slideUp('slow', function() {
					
		// Remove the track from the locally stored json playlist
		delete playlist.tracks[ removedIndex ];
							
		// Remove the list element from the DOM
		$( '#'+elementId ).remove();
	});

}
