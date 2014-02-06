function clearQueue() {
	
	$.mobile.loading( "show" );

	track_position = 0;

	playlist = { "tracks" : []};

	$( '#jukebox #playpause' ).attr( 'data-icon', 'play' );
	$( '#jukebox #playpause' ).removeClass('ui-icon-pause')
					.addClass('ui-icon-play');

	//$( '#jukebox #playpause' ).buttonMarkup( 'refresh' );

	shuffle_queue = false;

	$('#shuffle').attr('data-theme', theme.buttons);
	$('#shuffle').attr('data-icon', "shuffle");
	$('#shuffle').removeClass( theme.actions )
			.addClass( theme.buttons );
						
	//consoleLog( "Checking to see if player's srcSet is true or false" );
	//consoleLog( $( "#player" ).data( "jPlayer" ).status );
	
	// We only want to try and stop playerTwo if it has it's src set to something
	if ( $( "#player" ).data( "jPlayer" ).status.srcSet ) {
		
		// Pause the primary player instance
		$( "#player" ).jPlayer( "pause" );
		$( "#player" ).jPlayer( "clearMedia" );
	}

	// Reset the variable that holds the current track position from the server
	current_track_position = 0;

	//alert("calling updatePlayerDisplay");
	//updatePlayerDisplay( playing, "pause");

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
