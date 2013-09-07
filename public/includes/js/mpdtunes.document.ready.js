
$( document ).ready( function() {

	$( "#playerOne" ).jPlayer({	errorAlerts : 		false,
					warningAlerts : 	false,
					preload : 		"auto",
					swfPath : 		"includes/js",
					ready : function () {
    					
					},
					progress : function(event) {

						if ( primary_player == 1 ) {
 
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
						}
					},
					stalled : function( event ) {

						if (( playing ) && ( primary_player == 1 )) {	
						
							$.mobile.showPageLoadingMsg( theme.bars, "Stalled" );	
						}
					},
					waiting : function( event ) {

						if ( primary_player == 1 ) {

							consoleLog( "Inside waiting event handler", event );

							$.mobile.showPageLoadingMsg( theme.bars, "Waiting" );	
						}
					},
					abort : function( event ) {

						if ( primary_player == 1 ) {
	
							$.mobile.showPageLoadingMsg(theme.bars, "Aborting");
						}
					},
					error : function( event ) {

						consoleLog( "Inside error event handler for playerOne", event.jPlayer.error );
					},
					warning : function( event ) {

						consoleLog( "Inside warning event handler for playerOne", event.jPlayer.warning );
					},
					loadstart : function( event ) {

						if ( playing ) {
	
							if ( primary_player == 1 ) {
		
								// this prevents the resume notice showing up when firefox automatically fires off loadstart
								if ( event.jPlayer.status.src != '') {

									consoleLog( "current track position", current_track_position );

									if (	(( current_track_position > 0 ) && 
										(( event.jPlayer.status.src == playlist.tracks[ track_position ].oggurl ) || ( event.jPlayer.status.src == playlist.tracks[ track_position ].url ))) && 
										( !shuffle_queue )	) {
					
										$.mobile.showPageLoadingMsg( theme.bars, "Resuming" );

									} else {
					
										$.mobile.showPageLoadingMsg( theme.bars, "Loading" );
									}
								}
							}
						}
					},
					loadedmetadata : function( event ) {
						
						if ( primary_player == 1 ) {	
							
							consoleLog( "Inside loadedmetadata event handler", event );
	
							$.mobile.showPageLoadingMsg( theme.bars, "Metadata loaded" );
						
							//$( '#trackTotalDuration' ).html( get_timer_display( event.jPlayer.status.duration ));
						}
					},
					durationchange : function( event ) {

						consoleLog( "Inside durationchange event handler for playerOne", event );

						/*if ( primary_player == 1 ) {	
							
							$.mobile.showPageLoadingMsg( theme.bars, "Duration changed" );

							$( '#trackTotalDuration' ).html( get_timer_display( event.jPlayer.status.duration ));
						}*/
					},
					playing : function( event ) {
						
						if ( primary_player == 1 ) {	

							consoleLog( "Inside playing event handler", event );

							$.mobile.showPageLoadingMsg( theme.bars, "Playing" );
						}
					},
					timeupdate : function( event ) {
      				
						if ( primary_player == 1 ) {		
							
							//consoleLog( "Inside timeupdate event handler", event );

							// These two variables are used throughout the timeupdate handler
							currentTime = event.jPlayer.status.currentTime;
							totalDuration = event.jPlayer.status.duration;

							// Let's try to use the absolute percent first
							playProgress = event.jPlayer.status.currentPercentAbsolute;
							
							if ( playProgress == 0 ) {
							
								playProgress = event.jPlayer.status.currentPercentRelative;
							}	

							processTimeUpdate( currentTime, totalDuration, playProgress );
   
							/*seekPercent = event.jPlayer.status.seekPercent;

							if (( seekPercent < 100 ) || ( !load_progress_complete )) {

								// If we're in timeupdate and seekPercent is 0, then just set it to 100
								if ( seekPercent == 0 ) {

									seekPercent = 100;
								}
								
								consoleLog( "Inside timeupdate event handler", event );			
								consoleLog( "Updating load progress display to ", seekPercent );

								// Update the load progress indicator	
								updateLoadProgressDisplay( seekPercent );
							
								if ( seekPercent == 100 ) {
	
									load_progress_complete = true;
	
									// Update the load progress indicator one final time	
									updateLoadProgressDisplay( 100 );
								}
							}*/
						}
					},
					ended : function( event ) {

						if ( !next_track_already_added ) {

							if ( repeat_track ) {

								skipto( "same" );

							} else {
							
								skipto( "next" );
							}						

						} else { // must have been cross-faded 

							consoleLog( "Updating the currently playing track info", playlist.tracks[ track_position ] );

							// Update the album art and track info as well as the player progress divs
							updateCurrentTrackInfo( playlist.tracks[ track_position ] );
	
							// Reset the variable used to determine whether a song has started to crossfade
							next_track_already_added = false;

							// Reset the two variables used for simulating a fade in and fade out during the crossfade
							out_volume_left_to_fade = volume_fade - 1;
							in_volume_left_to_fade = volume_fade - 1;

							// This will help to control who gets to update the player progress bar
							crossfade_is_in_progress = false;

							// Toggle players
							primary_player = (( primary_player == 1 ) ? 2 : 1);

							consoleLog( "Primary player set to " + primary_player );

							// Swap the active and inactive player selectors
							tmpPlayerSelector = activePlayerSelector;
							activePlayerSelector = inactivePlayerSelector;
							inactivePlayerSelector = tmpPlayerSelector;

							consoleLog( "Active player selector is now: " + activePlayerSelector );
							consoleLog( "Inactive player selector is now: " + inactivePlayerSelector );
					
							// Make sure the inactive player's volume gets set back to full volume
							$( inactivePlayerSelector ).jPlayer( "volume", 1 );

							// Reset the load progress complete tracker
							load_progress_complete = false;
						}
					},
					volumechange : function( event ) {

						consoleLog( "Player volume set to " + event.jPlayer.options.volume );
					}	
  				});

	$( "#playerTwo" ).jPlayer({	errorAlerts : 		false,
					warningAlerts :	 	false,
					preload :	 	"auto",
					swfPath : 		"includes/js",
					cssSelectorAncestor : 	"#jukebox",
					ready : function () {
    					
					},
					progress : function(event) {

						if ( primary_player == 2 ) {
 
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
						}
					},
					stalled : function( event ) {

						if (( playing ) && ( primary_player == 2 )) {	
						
							$.mobile.showPageLoadingMsg( theme.bars, "Stalled" );	
						}
					},
					waiting : function( event ) {

						if ( primary_player == 2 ) {

							consoleLog( "Inside waiting event handler", event );

							$.mobile.showPageLoadingMsg( theme.bars, "Waiting" );	
						}
					},
					abort : function( event ) {

						if ( primary_player == 2 ) {
	
							$.mobile.showPageLoadingMsg(theme.bars, "Aborting");
						}
					},
					error : function( event ) {

						consoleLog( "Inside error event handler for playerTwo", event.jPlayer.error );
					},
					warning : function( event ) {

						consoleLog( "Inside warning event handler for playerTwo", event.jPlayer.warning );
					},
					loadstart : function( event ) {

						if ( playing ) {
	
							if ( primary_player == 2 ) {
		
								// this prevents the resume notice showing up when firefox automatically fires off loadstart
								if ( event.jPlayer.status.src != '' ) {

									consoleLog( "current track position", current_track_position );

									if (	(( current_track_position > 0 ) && 
										(( event.jPlayer.status.src == playlist.tracks[ track_position ].oggurl ) || ( event.jPlayer.status.src == playlist.tracks[ track_position ].url ))) && 
										( !shuffle_queue )	) {
						
										$.mobile.showPageLoadingMsg( theme.bars, "Resuming" );
	
									} else {
						
										$.mobile.showPageLoadingMsg( theme.bars, "Loading" );
									}
								}
							}
						}
					},
					loadedmetadata : function( event ) {
						
						if ( primary_player == 2 ) {	
						
							consoleLog( "Inside loadedmetadata event handler", event );	
	
							$.mobile.showPageLoadingMsg( theme.bars, "Metadata loaded" );
						
							//$( '#trackTotalDuration' ).html( get_timer_display( event.jPlayer.status.duration ));
						}
					},
					durationchange : function(event) {

						consoleLog( "Inside durationchange event handler for playerTwo", event );
	
						/*if ( primary_player == 2 ) {	
							
							$.mobile.showPageLoadingMsg( theme.bars, "Duration changed" );

							$( '#trackTotalDuration' ).html( get_timer_display( event.jPlayer.status.duration ));
						}*/
					},
					playing : function( event ) {
						
						if ( primary_player == 2 ) {	
							
							consoleLog( "Inside playing event handler", event );	

							$.mobile.showPageLoadingMsg( theme.bars, "Playing" );
						}
					},
					timeupdate : function( event ) {
      				
						if ( primary_player == 2 ) {		
				
							//consoleLog( "Inside timeupdate event handler", event );

							// These two variables are used throughout the timeupdate handler
							currentTime = event.jPlayer.status.currentTime;
							totalDuration = event.jPlayer.status.duration;
							
							// Let's try to use the absolute percent first
							playProgress = event.jPlayer.status.currentPercentAbsolute;
							
							if ( playProgress == 0 ) {
							
								playProgress = event.jPlayer.status.currentPercentRelative;
							}	

							processTimeUpdate( currentTime, totalDuration, playProgress );
  
							/*seekPercent = event.jPlayer.status.seekPercent;

							if (( seekPercent < 100 ) || ( !load_progress_complete )) {

								// If we're in timeupdate and seekPercent is 0, then just set it to 100
								if ( seekPercent == 0 ) {

									seekPercent = 100;
								}

								consoleLog( "Inside timeupdate event handler", event );			
								consoleLog( "Updating load progress display to ", seekPercent );

								// Update the load progress indicator	
								updateLoadProgressDisplay( seekPercent );
							
								if ( seekPercent == 100 ) {
	
									load_progress_complete = true;
	
									// Update the load progress indicator one final time	
									updateLoadProgressDisplay( 100 );
								}
							}*/
						}
					},
					ended : function( event ) {

						if ( !next_track_already_added ) {		

							if ( repeat_track ) {

								skipto( "same" );

							} else {
							
								skipto( "next" );
							}
						
						} else { // Must have been cross-faded
							
							consoleLog( "Updating the currently playing track info", playlist.tracks[ track_position ] );

							// Update the album art and track info as well as the player progress divs
							updateCurrentTrackInfo( playlist.tracks[ track_position ] );
	
							// Reset the variable used to determine whether a song has started to crossfade
							next_track_already_added = false;

							// Reset the two variables used for simulating a fade in and fade out during the crossfade
							out_volume_left_to_fade = volume_fade - 1;
							in_volume_left_to_fade = volume_fade - 1;

							// This will help to control who gets to update the player progress bar
							crossfade_is_in_progress = false;

							// Toggle players
							primary_player = (( primary_player == 1 ) ? 2 : 1);

							consoleLog( "Primary player set to " + primary_player );
					
							// Swap the active and inactive player selectors
							tmpPlayerSelector = activePlayerSelector;
							activePlayerSelector = inactivePlayerSelector;
							inactivePlayerSelector = tmpPlayerSelector;
						
							consoleLog( "Active player selector is now: " + activePlayerSelector );
							consoleLog( "Inactive player selector is now: " + inactivePlayerSelector );

							// Make sure the inactive player's volume gets set back to full volume
							$( inactivePlayerSelector ).jPlayer( "volume", 1 );
					
							// Reset the load progress complete tracker
							load_progress_complete = false;
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
