
$( document ).ready( function() {

	$( "#player" ).jPlayer({	errorAlerts : 		false,
					warningAlerts : 	false,
					preload : 		"auto",
					swfPath : 		"includes/js",
					ready : function () {
    					
					},
					progress : function(event) {
 
						seekPercent = event.jPlayer.status.seekPercent;

						if ( seekPercent == null ) {

							seekPercent = 100;
							consoleLog( "seekPercent was null, so setting it to ", seekPercent );
						}

						if (( seekPercent < 100 ) || ( !load_progress_complete )) {  

							consoleLog( "Inside progress event handler", event );			
							consoleLog( "Updating load progress display to ", seekPercent );			
							
							// Update the load progress indicator	
							updateLoadProgressDisplay( seekPercent );

							if ( seekPercent == 100 ) {
	
								load_progress_complete = true;

								// Update the load progress indicator one final time	
								updateLoadProgressDisplay( 100 );
							}
						}
					},
					stalled : function( event ) {

						if ( playing ) {
						
							$.mobile.loading( "show", theme.bars, "Stalled" );	
						}
					},
					waiting : function( event ) {

						consoleLog( "Inside waiting event handler", event );

						$.mobile.loading( "show", theme.bars, "Waiting" );	
				
					},
					abort : function( event ) {

						$.mobile.loading( "show", theme.bars, "Aborting" );
					},
					error : function( event ) {

						consoleLog( "Inside error event handler for player", event.jPlayer.error );
					},
					warning : function( event ) {

						consoleLog( "Inside warning event handler for player", event.jPlayer.warning );
					},
					loadstart : function( event ) {

						if ( playing ) {
		
							// this prevents the resume notice showing up when firefox automatically fires off loadstart
							if ( event.jPlayer.status.src != '') {

								consoleLog( "current track position", current_track_position );

								if (	(( current_track_position > 0 ) && 
									(( event.jPlayer.status.src == playlist.tracks[ track_position ].oggurl ) || ( event.jPlayer.status.src == playlist.tracks[ track_position ].url ))) && 
									( !shuffle_queue )	) {
					
									$.mobile.loading( "show", theme.bars, "Resuming" );

								} else {
					
									$.mobile.loading( "show", theme.bars, "Loading" );
								}
							}
						}
					},
					loadedmetadata : function( event ) {
						
						consoleLog( "Inside loadedmetadata event handler", event );
	
						$.mobile.loading( "show", theme.bars, "Metadata loaded" );	
					},
					durationchange : function( event ) {

						consoleLog( "Inside durationchange event handler for player", event );
					},
					playing : function( event ) {

						consoleLog( "Inside playing event handler", event );

						$.mobile.loading( "show", theme.bars, "Playing" );
					},
					timeupdate : function( event ) {
							
						//consoleLog( "Inside timeupdate event handler", event );

						// These two variables are used throughout the timeupdate handler
						currentTime = event.jPlayer.status.currentTime;
						totalDuration = event.jPlayer.status.duration;

						if ( playlist.tracks[ track_position ].url.indexOf( "http://" ) === 0) {
	
							totalDuration = "Infinity:NaN";
						}

						// Let's try to use the absolute percent first
						playProgress = event.jPlayer.status.currentPercentAbsolute;
							
						if ( playProgress == 0 ) {
							
							playProgress = event.jPlayer.status.currentPercentRelative;
						}	

						processTimeUpdate( currentTime, totalDuration, playProgress );
					},
					ended : function( event ) {

						if ( repeat_track ) {

							skipto( "same" );

						} else {
							
							skipto( "next" );
						}
					},
					volumechange : function( event ) {

						consoleLog( "Player volume set to " + event.jPlayer.options.volume );
					}	
  				});


	if( typeof playlist !== 'undefined' ) {
		
		if( typeof playlist.tracks !== 'undefined' ) {	

			$( '#artistDiv' ).html( playlist.tracks[ track_position ].artist );

			var fullAlbum = playlist.tracks[ track_position ].album;
			var truncatedAlbum = (( fullAlbum.length > max_album_name_string_length ) ? ( fullAlbum.substring( 0, max_album_name_string_length ) + '...' ) : fullAlbum );
			$( '#albumDiv' ).html( truncatedAlbum ).trigger( 'updatelayout' );
	
			if ( playlist.tracks[ track_position ].url.indexOf( "http://" ) === 0) {
			
				$( '#trackDiv' ).html( playlist.tracks[ track_position ].url ).trigger( 'updatelayout' );
			
			} else {
					
				var fullTitle = playlist.tracks[ track_position ].title;
				var truncatedTitle = (( fullTitle.length > max_track_title_string_length ) ? ( fullTitle.substring( 0, max_track_title_string_length ) + '...' ) : fullTitle );
						
				$( '#trackDiv' ).html( truncatedTitle ).trigger( 'updatelayout' );
			}
		}
	}
} );
