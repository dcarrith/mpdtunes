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

	if( 'console' in window && 'log' in window.console ) {
						
		console.log( "Checking to see if player's srcSet is true or false" );
		console.log( $( "#player" ).data( "jPlayer" ).status );
	}
	
	// We only want to try and stop playerTwo if it has it's src set to something
	if ( $( "#player" ).data( "jPlayer" ).status.srcSet ) {
		
		// Pause the primary player instance
		$( "#player" ).jPlayer( "pause" );
		$( "#player" ).jPlayer( "clearMedia" );
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
