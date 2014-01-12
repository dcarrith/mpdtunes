function control_mpd(action, parameters) {

	var url = "/musicpd/index";

	var go_back = false;
	var show_message = false;
	var hide_spinner = false;
	var message = "Loading";
	var timeout = 0;

	switch (action) {
		
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
		case 'prev' :
			url = "/musicpd/control/previous";
			break;
		case 'set_crossfade' :
			url = "/musicpd/control/crossfade";
			break;
		case 'shuffle' :
			url = "/musicpd/control/shuffle";
			break;
		case 'repeat' :
			url = "/musicpd/control/repeat";
			break;
		case 'set_volume' :
			url = "/musicpd/control/volume";
			break;
		case 'mute' :
			url = "/musicpd/control/mute";
			break;
		case 'start_over' :
			url = "/musicpd/control/reset";
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
		case 'skip_to' :
			url = "/musicpd/control/skip";
			break;
		case 'update' :
			timeout = 3000;
			show_message = true;
			message = 'Refreshing MPD';
			url = "/musicpd/control/refresh";
			break;
		default :
			url = "/musicpd/index";
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

	$.ajax({

	   type: "POST",
	   url: url,
	   async: true,
	   dataType: 'json',
	   data: post_data,
	   
	   success: function( result ) {

	   		if ( show_message ) {
		
				//alert(JSON.stringify( result ));
	
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

			if ( go_back ) {

				setTimeout( function() { $('#header-back-link').trigger('click'); }, timeout);
			}
			
			// This is for any actions that are contingent on a successful server-side operation		
			if( result.success ) {

				switch( result.action ) {

					case 'clear':
						clearQueue();			
						break;
					case 'remove':
						removeQueuedTrack( result.id, result.index );					
						break;
					default: 

						break;	
				}
			}
	   	}
	});
}
