function control_mpd(action, parameters) {

	var url = "/musicpd/index";

	var go_back = false;
	var show_message = false;
	var hide_spinner = false;
	var message = "Loading";
	var timeout = 0;

	switch (action) {
		
		case 'mpdidling' :
			url = "/musicpd/control/mpdidling";
			break;
		case 'idle' :
			url = "/musicpd/control/idle";
			break;
		case 'noidle' :
			url = "/musicpd/control/noidle";
			break;
		case 'play' :
			url = "/musicpd/control/play";
			break;
		case 'pause' :
			url = "/musicpd/control/pause";
			break;
		case 'stop' :
			url = "/musicpd/control/stop";
			break;
		case 'next' :
			url = "/musicpd/control/next";
			break;
		case 'previous' :
			url = "/musicpd/control/previous";
			break;
		case 'skip' :
			url = "/musicpd/control/skip";
			break;
		case 'crossfade' :
			url = "/musicpd/control/crossfade";
			break;
		case 'mixrampdb' :
			url = "/musicpd/control/mixrampdb";
			break;
		case 'mixrampdelay' :
			url = "/musicpd/control/mixrampdelay";
			break;
		case 'random' :
			url = "/musicpd/control/random";
			break;
		case 'repeat' :
			url = "/musicpd/control/repeat";
			break;
		case 'setvol' :
			url = "/musicpd/control/volume";
			break;
		case 'mute' :
			url = "/musicpd/control/mute";
			break;
		case 'reset' :
			url = "/musicpd/control/reset";
			break;
		case 'status' :
			url = "/musicpd/control/status";
			break;
		case 'save_playlist' :
			show_message = true;
			message = 'Saving playlist'; 
			url = "/musicpd/playlist/save";
			go_back = true;
			break;
		case 'delete_playlist' :
			show_message = true;
			message = 'Deleting playlist'; 
			url = "/musicpd/playlist/delete";
			go_back = true;
			break;
		case 'track_add' :
			url = "/musicpd/playlist/add/track";
			break;
		case 'move_track' :
			url = "/musicpd/playlist/move/track";
			break;
		case 'track_move' :
			url = "/musicpd/playlist/move/track";
			break;
		case 'track_remove' :
			url = "/musicpd/playlist/remove/track";
			break;
		case 'remove' :
			timeout = 3000;
			show_message = true;
			message = "Removing track";
			url = "/musicpd/playlist/remove/track";
			break;
		case 'clear' :
			timeout = 3000;
			show_message = true;
			message = "Clearing playlist";
			url = "/musicpd/playlist/clear";
			break;
		case 'add' :
			timeout = 1000;
			show_message = true;
			hide_spinner = false;
			message = "Adding track";
			url = "/musicpd/playlist/add/track";
			break;
		case 'add_url' :
			url = "/musicpd/playlist/add/url";
			break;
		case 'add_all' :
			timeout = 3000;
			show_message = true;
			message = 'Adding tracks';
			url = "/musicpd/playlist/add/tracks";
			go_back = true;
			break;
		case 'add_albums' :
			timeout = 3000;
			show_message = true;
			message = 'Adding albums';
			url = "/musicpd/playlist/add/albums";
			go_back = true;
			break;
		case 'update' :
			timeout = 3000;
			show_message = true;
			message = 'Refreshing MPD';
			url = "/musicpd/control/refresh";
			break;
		default :
			// As long as the client and server commands are the same, we can just form the url with 
			// whatever action was called. This works fine for: play, pause, stop, next, previous, skip,
			// crossfade, mixrampdb, mixrampdelay, shuffle, repeat, setvol, mute, reset, status, and refresh
			url = "/musicpd/control/"+action;
			break;
	}

	post_data = "";

	if (typeof parameters !== 'undefined') {

		for (parameter in parameters) {

			// Use encodeURIComponent here instead of escape so that it uses UTF-8 and not UTF-16 for double byte characters
			post_data += parameter + "=" + encodeURIComponent(parameters[parameter]) + "&"
		}

		post_data = post_data.substring(0, (post_data.length - 1));
	}

	if (show_message) {
	
		if (timeout == 0) {
	
			$.mobile.loadingMessage = message;
			$.mobile.loading( "show", "a", message, hide_spinner );

		} else {

			$.mobile.loading( "show", "a", message, hide_spinner ); 
		}
	}

	//alert("url: "+url+"\npost_data: "+post_data);

	// I'll eventually want to switch to using Promises
	/*post( url, post_data ).then( function( response ) {

		console.log( "Success!", response );
	
	}, function( error ) {

		console.error( "Failed!", error );
	});*/

	var xhr = $.ajax({

	   type: "POST",
	   url: url,
	   async: true,
	   dataType: 'json',
	   data: post_data,
	   
	   success: function( result ) {

	   		if ( show_message ) {
			
				if( result.message != "" ) {

					// Hide and reshow with the new message
					$.mobile.loading( "hide" );
					$.mobile.loadingMessage = result.message;
		
					// Make sure to use the red theme if the server-side operation was unsuccessful
					$.mobile.loading( "show", (result.success ? theme.body : "r"), result.message, hide_spinner);
				
					timeout = 3000;
					go_back = false;

					setTimeout( function() { $.mobile.loading( "hide" ); $.mobile.loadingMessage = 'Loading';}, timeout );

				} else {
	
					if ( timeout == 0 ) {
		
						$.mobile.loading( "hide" );
						$.mobile.loadingMessage = 'Loading';

					} else {

						setTimeout( function() { $.mobile.loading( "hide" ); }, timeout );
					}
				}
			}
			
			// This is for any actions that are contingent on a successful server-side operation		
			if( result.success ) {

				switch( result.action ) {

					case 'clear':
						clearQueue();			
						break;
					case 'remove':
						removeQueuedTrack( result.id, result.index );
					
						// Hide the loading message
						$.mobile.loading( "hide" ); 
						$.mobile.loadingMessage = 'Loading';	
						break;
					case 'play':
					case 'next':
					case 'previous':
					case 'skip':
						break;					
					case 'pause':
						control_mpd('noidle');
						break;
					case 'idle':
						break;	
					case 'add_all' :
						// Hide the loading message
						$.mobile.loading( "hide" ); 
						$.mobile.loadingMessage = 'Loading';
						break;			

					default:

						break;	
				}

				if ( typeof result.mpdidling != 'undefined' ) {
				
					if ( !result.mpdidling ) {

						// If the server communicates to us that MPD is not yet sitting idle, then send the idle command
						control_mpd('idle');

						// Set the server side session variable to indicate that idle has been started
						control_mpd('mpdidling');
					}
				}
			}

			if ( go_back ) {
							
				$('#header-back-link').trigger('click');
			} 
	   	}
	});

	if( action == 'idle' ) {

		// We don't need to wait around for a response from idle the idle command
		//xhr.abort();
	}
}


/* I'll eventually want to switch to using promises
function post(url, data) {

	// Return a new promise.
	return new Promise(function(resolve, reject) {

		// Do the usual XHR stuff
		var req = new XMLHttpRequest();
		req.open("POST", url, true);
		req.setRequestHeader("Content-type","application/json");

		req.onload = function() {

			// This is called even on 404 etc, so check the status
			if (req.status == 200) {
				
				// Resolve the promise with the response text
				resolve(req.response);
			
			} else {

				// Otherwise reject with the status text which will hopefully be a meaningful error
				reject(Error(req.statusText));
			}
		};

		// Handle network errors
		req.onerror = function() {
     
			reject(Error("Network Error"));
		};

		// Make the request
		req.send(data);
	});
}*/
