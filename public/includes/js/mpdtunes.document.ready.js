
$( document ).ready( function() {

   // connect to WAMP server
   ab.connect("ws://demo.mpdtunes.com",
 
      // WAMP session was established
      function (session) {

	sess = session;

	//alert(JSON.stringify(sess)); 
         // things to do once the session has been established
 
		var channel = 'radio/station/'+usersStationId;
                
		sess.subscribe(channel, onUpdate);
      },
 
      // WAMP session is gone
      function (code, reason) {

	//alert("no session"); 
         // things to do once the session fails
      }
   );

	$( "#player" ).jPlayer({	errorAlerts : 		false,
					warningAlerts : 	false,
					preload : 		"auto",
					swfPath : 		"includes/js",
					ready : function () {
    					
					},
					progress : function(event) {
	
						//consoleLog(sec);
						//consoleLog((sec/trackEnd)*100);	
			
						//processTimeUpdate( sec, trackEnd, ((sec/trackEnd)*100) );
	
						/*ticker = ticker + 1;
		
						if (ticker == 3) {

							ticker = 0;
							trackPlay = trackPlay + 1;
							consoleLog("trackPlay: "+trackPlay);
						}*/


						//consoleLog( "Date.now()", Date.now() );
						//consoleLog( "Inside progress event handler", event );	
 
						seekPercent = event.jPlayer.status.seekPercent;

						if ( seekPercent == null ) {

							seekPercent = 100;
							consoleLog( "seekPercent was null, so setting it to ", seekPercent );
						}

						if (( seekPercent < 100 ) || ( !load_progress_complete )) {  

							//consoleLog( "Inside progress event handler", event );			
							//consoleLog( "Updating load progress display to ", seekPercent );			
							
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

							if ( started ) {

								//consoleLog("current_track_position: "+current_track_position+" elapsed: "+elapsed);

								// Update the current_track_position based on what has elapsed since initial loading
								current_track_position = elapsed;

								waiting = true;

								pauseProgress();
							}
						
							$.mobile.loading( "show", theme.bars, "Stalled" );	
						}
					},
					waiting : function( event ) {

						if ( playing ) {

							if ( started ) {
								
								//consoleLog("current_track_position: "+current_track_position+" elapsed: "+elapsed);
			
								// Update the current_track_position based on what has elapsed since initial loading
								current_track_position = elapsed;

								waiting = true;

								pauseProgress();
							}
						}

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
									(( event.jPlayer.status.src == playlist.tracks[ track_position ].file ) || ( event.jPlayer.status.src == playlist.tracks[ track_position ].file ))) && 
									( !shuffle_queue )	) {
					
									$.mobile.loading( "show", theme.bars, "Resuming" );

								} else {
					
									$.mobile.loading( "show", theme.bars, "Loading" );
								}

								if ( started ) {
			
									waiting = true;

									pauseProgress();
								}
							}
						}
					},
					loadedmetadata : function( event ) {
						
						consoleLog( "Inside loadedmetadata event handler", event );
	
						$.mobile.loading( "show", theme.bars, "Metadata loaded" );	
					},
					durationchange : function( event ) {

						//consoleLog( "Inside durationchange event handler for player", event );
					},
					play : function ( event ) {

						consoleLog( "Inside play event handler", event );
					},
					playing : function( event ) {

						trackTime = playlist.tracks[ track_position ].Time;

						/*if (current_track_position > elapsed) {

							elapsed = current_track_position;
						}*/

						consoleLog("Inside the jPlayer playing event handler");

						waiting = false;						

						startProgress( elapsed, trackTime, ((elapsed / trackTime) * 100));

						consoleLog( "Inside playing event handler", event );

						//$.mobile.loading( "show", theme.bars, "Playing" );

						$.mobile.loading( "hide" );
					},
					timeupdate : function( event ) {
							
						//consoleLog( "Inside timeupdate event handler", event );

						// These two variables are used throughout the timeupdate handler
						/*currentTime = event.jPlayer.status.currentTime;
						
						totalDuration = event.jPlayer.status.duration;

						if ( playlist.tracks[ track_position ].file.indexOf( "http://" ) === 0) {
	
							totalDuration = "Infinity:NaN";
						}

						// Let's try to use the absolute percent first
						playProgress = event.jPlayer.status.currentPercentAbsolute;
							
						if ( playProgress == 0 ) {
							
							playProgress = event.jPlayer.status.currentPercentRelative;
						}	

						processTimeUpdate( currentTime, totalDuration, playProgress );
						*/
					},
					ended : function( event ) {

						/*if ( repeat_track ) {

							skipto( "same" );

						} else {
							
							skipto( "next" );
						}*/
					},
					volumechange : function( event ) {

						consoleLog( "Player volume set to " + event.jPlayer.options.volume );
					}	
  				});


	if( typeof playlist !== 'undefined' ) {
		
		if( typeof playlist.tracks !== 'undefined' ) {	

			if( typeof playlist.tracks[ track_position ] !== 'undefined' ) {

				$( '#artistDiv' ).html( playlist.tracks[ track_position ].artist );

				var fullAlbum = playlist.tracks[ track_position ].Album;
				var truncatedAlbum = (( fullAlbum.length > max_album_name_string_length ) ? ( fullAlbum.substring( 0, max_album_name_string_length ) + '...' ) : fullAlbum );
				$( '#albumDiv' ).html( truncatedAlbum ).trigger( 'updatelayout' );
	
				if ( playlist.tracks[ track_position ].file.indexOf( "http://" ) === 0) {
			
					$( '#trackDiv' ).html( playlist.tracks[ track_position ].file ).trigger( 'updatelayout' );
			
				} else {
					
					var fullTitle = playlist.tracks[ track_position ].Title;
					var truncatedTitle = (( fullTitle.length > max_track_title_string_length ) ? ( fullTitle.substring( 0, max_track_title_string_length ) + '...' ) : fullTitle );
							
					$( '#trackDiv' ).html( truncatedTitle ).trigger( 'updatelayout' );
				}
			}
		}
	}
} );
