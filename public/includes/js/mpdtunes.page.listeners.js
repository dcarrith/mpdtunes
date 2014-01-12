// **********************************************************************************
// Page init event handlers
// **********************************************************************************

$("body").on("lazyloadercreate", "#index", function ( evt ){

	//alert("lazyloadercreate called!\n\nevt.instances: "+JSON.stringify(evt));
});

$("body").on("lazyloaderbeforecreate", "#index", function ( evt ){

	//alert("lazyloaderbeforecreate called!\n\nevt.instances: "+JSON.stringify(evt));
});

$("body").on("lazyloaderdestroy", "#index", function ( evt ){

	//alert("lazyloaderdestroy called!\n\nevt.instances: "+JSON.stringify(evt));
});

$("body").on("lazyloaderdoneloading", "#index", function ( evt ){

	//alert("lazyloaderdoneloading called!\n\nevt.instances: "+JSON.stringify(evt));

	$.mobile.loading( "hide" );
	$.mobile.loadingMessage = "Loading";
});

$("body").on("lazyloaderalldone", "#index", function ( evt ){

	//alert("lazyloaderalldone called!\n\nevt.instances: "+JSON.stringify(evt));

	$.mobile.loading( "hide" );
	$.mobile.loadingMessage = "Loading";
});

$("body").on("lazyloaderbusy", "#index", function ( evt ){

	//alert("lazyloaderalldone called!\n\nevt.instances: "+JSON.stringify(evt));

	$.mobile.loading( "hide" );
	$.mobile.loadingMessage = "Loading";
});

$("body").on("lazyloadererror", "#index", function ( evt ){

	//alert("lazyloadererror called!\n\nevt.instances: "+JSON.stringify(evt));
});

$("body").on("lazyloaderreset", "#index", function ( evt ){

	//alert("lazyloaderreset called!\n\nevt.instances: "+JSON.stringify(evt));
});

$("body").on("lazyloaderresetall", "#index", function ( evt ){

	//alert("lazyloaderresetall called!\n\nevt.instances: "+JSON.stringify(evt));
});

$('body').on('pageinit', '#index', function( evt, ui ) {

	// Initialize the lazyloader widget
	$( "#index" ).lazyloader();

	// Set up the variable options to pass to the lazyloader reinitialize function
	var options = {};

	// Set up the page specific settings to pass to the lazyloader reinitialize function
	var settings = { "clearUrl" : "/session/clear" };

	// Set up the post parameters to pass to the lazyloader reinitialize function
	var parameters = {};

	// Re-initialize the lazyloader widget
	$( "#index" ).lazyloader( "reInitialize", options, settings, parameters );

	// Set some default options for the lazyloader
	$.mobile.lazyloader.prototype.timeoutOptions.mousewheel = 300;
	$.mobile.lazyloader.prototype.timeoutOptions.scrollstart = 700;
	$.mobile.lazyloader.prototype.timeoutOptions.scrollstop = 100;
	$.mobile.lazyloader.prototype.timeoutOptions.showprogress = 100;

	// This is disabled on the Queue page for the drag and drop functionality - so, re-enable it
	$( 'body' ).enableSelection();
});

$( 'body' ).on( 'pageinit', '#genres', function( evt, ui ) {

	// reinitialize the artists lazy loader default
	//artists_listed_so_far = default_artists_listed_so_far;
});

$( 'body' ).on( 'pageinit', '#queue', function( evt, ui ) {

	// This will attach the mousewheel event listener to the artistList div for scrolling in desktop browsers
	//attach_mousewheel_event("queue");
	
	//attach_window_scroll_event("queue");

	// to make the address bar auto-hide in some mobile browsers
	//window.scrollTo(0, 1);

	// Use an automatic threshold that's a function of the height of the viewport
	threshold = $( window ).height();

	// Set up the variable options to pass to the lazyloader reinitialize function
	var options = {	"threshold"		: threshold,
			"retrieve"		: 20,
			"retrieved"		: 20,
			"bubbles"		: true,
			"offset"		: playlist_index_offset };

    	/*var template = {"tag":"li","class":"ui-li-has-thumb queued_track${playlist_index}","children":[
                                {"tag":"a","href":"","data-icon":"none","onclick":(function(e){ bump_track( e.obj.playlist_index ); }),"class":"ui-link-inherit","title":"Bump to top of queue","children":[
                                    {"tag":"img","src":"${art}","class":"ui-li-thumb track-item-image","html":""},
                                    {"tag":"h3","class":"track-title-heading ui-li-heading","html":"${title}"},
                                    {"tag":"p","class":"ui-li-aside ui-li-desc","html":"${time}"}
                                ]},
                                {"tag":"a","href":"","data-icon":"delete","onclick":(function(e){ remove_queued_track( e.obj.playlist_index, e.obj.title, e.obj.symbolic_link_filename ); }),"class":"ui-li-link-alt ui-btn ui-btn-up-${theme_buttons} remove_queued_track","data-theme":"${theme_buttons}","title":"Remove from queue","html":"Remove from queue"}
                            ]};*/

	// Set up the page specific settings to pass to the lazyloader reinitialize function
	var settings = { 	"pageId" 		: "queue",
                        	"templateType"          : "dust",
                        	"templateId"            : "queue",
                        	"templatePrecompiled"   : true,
				"mainId"		: "queueTracksList",
				"progressDivId"         : "lazyloaderProgressDiv",
				"moreUrl"		: "/queue/more",
				"clearUrl" 		: "/session/clear",
				"JSONP"			: false,
				"JSONPCallback"		: "callback"	};

	// Set up the post parameters to pass to the lazyloader reinitialize function
	var parameters = { 	"retrieve" 		: options.retrieve,
				"retrieved"		: options.retrieved,
				"offset"		: options.offset };

	// Reinitialize the lazyloader so that it correctly handles the listview on the artists page
	$( "#index" ).lazyloader( "reInitialize", options, settings, parameters );

	var sortable_options = 	{ 	disabled: false,
			    		axis: 'y', 
				    	containment: 'parent',
				    	distance: 1, 
				    	opacity: 0.7, 
				    	scroll: true,
				    	scrollSensitivity: 160,
				    	scrollSpeed: 30,
				    	revert: true,
				    	placeholder: 'ui-state-highlight-sortable',
				    	items: 'li',
				    	handle: 'a.move',
				    	delay: 800 	};

	$( "#queueTracksList" ).sortable( sortable_options );

    	// getter
	/*var scroll = $( "#queueTracksList" ).sortable( "option", "scroll" );
 
	alert("scroll: "+JSON.stringify(scroll));

	// setter
	$( "#queueTracksList" ).sortable( "option", "scroll", false );

	alert("scroll: "+JSON.stringify(scroll));*/

    	// This line causes issues with the Search filter on UL elements provided by JQM
	$( 'body' ).disableSelection();
});

$('body').on('pageinit', '#artists', function(evt, ui) {

	// This will attach the mousewheel event listener to the artistList div for scrolling in desktop browsers
	//attach_mousewheel_event("artists");
	
	//attach_window_scroll_event("artists");

	// Reset the lazy loader instance for the albums page
	$( "#index" ).lazyloader( "reset", "albums" );

	// Use an automatic threshold that's a function of the height of the viewport
	threshold = $( window ).height();

	// Set up the variable options to pass to the lazyloader reinitialize function
	var options = {	"threshold"		: threshold,
			"retrieve"		: 20,
			"retrieved"		: 20,
			"bubbles"		: true,
			"offset"		: 0 };

	// Set up the page specific settings to pass to the lazyloader reinitialize function
	var settings = {	"pageId"                : "artists",
                        	"templateType"          : "dust",
                        	"templateId"            : "artists",
                        	"templatePrecompiled"   : true,
				"mainId"		: "artistsList",
				"progressDivId"         : "lazyloaderProgressDiv",
				"moreUrl"		: "/artists/more",
				"clearUrl" 		: "/session/clear"	};

	// Set up the post parameters to pass to the lazyloader reinitialize function
	var parameters = { 	"retrieve" 		: options.retrieve,
				"retrieved"		: options.retrieved,
				"offset"		: options.offset };

	// Reinitialize the lazyloader so that it correctly handles the listview on the artists page
	$( "#index" ).lazyloader( "reInitialize", options, settings, parameters );
});

$('body').on('pageinit', '#save_playlist', function(evt, ui) {

	$( 'body' ).enableSelection();

	// Reset the lazy loader instance for the queue page
	$( "#index" ).lazyloader( "reset", "queue" );
});

$('body').on('pageinit', '#albums', function(evt, ui) {

	// This will attach the mousewheel event listener to the artistList div for scrolling in desktop browsers
	//attach_mousewheel_event("albums");

	//attach_window_scroll_event("albums");

	// Use an automatic threshold that's a function of the height of the viewport
	threshold = $( window ).height();

	// Set up the variable options to pass to the lazyloader reinitialize function
	var options = {	"threshold"		: threshold,
			"retrieve"		: 20,
			"retrieved"		: 20,
			"bubbles"		: true };

	// Set up the page specific settings to pass to the lazyloader reinitialize function
	var settings = {	"pageId" 		: "albums",
                       		"templateType"          : "dust",
                        	"templateId"            : "albums",
                        	"templatePrecompiled"   : true,
				"mainId"		: "albumsList",
				"progressDivId"         : "lazyloaderProgressDiv",
				"moreUrl"		: "/albums/more",
				"clearUrl" 		: "/session/clear"	};

	// Set up the post parameters to pass to the lazyloader reinitialize function
	var parameters = { 	"retrieve" 		: options.retrieve,
				"retrieved"		: options.retrieved,
				"offset"		: options.offset };

	// Reinitialize the lazyloader so that it correctly handles the listview on the artists page
	$( "#index" ).lazyloader( "reInitialize", options, settings, parameters );
});

$('body').on('pageinit', '#playlists', function(evt, ui) {

    // Reset the lazy loader instance for the tracks page
    $( "#index" ).lazyloader( "reset", "playlistTracksPage" );

	// This is disabled on the Queue page for the drag and drop functionality - so, re-enable it
	$( 'body' ).enableSelection();
});

$('body').on('pageinit', '#playlistTracksPage', function(evt, ui) {

	// Use an automatic threshold that's a function of the height of the viewport
	threshold = $( window ).height();

	// Set up the variable options to pass to the lazyloader reinitialize function
	var options = {	"threshold"	: threshold,
                    	"retrieve"      : 50,
                    	"retrieved"     : 50,
                    	"bubbles"       : true,
                    	"offset"        : 0 };

	// Set up the page specific settings to pass to the lazyloader reinitialize function
	var settings = {	"pageId"                : "playlistTracksPage",
                        	"templateType"          : "dust",
                        	"templateId"            : "playlistTracks",
                        	"templatePrecompiled"   : true,
                        	"mainId"                : "playlistTracks",
                        	"progressDivId"         : "lazyloaderProgressDiv",
                        	"moreUrl"               : "/playlist/tracks/more",
                        	"clearUrl"              : "/session/clear" };

	// Set up the post parameters to pass to the lazyloader reinitialize function
	var parameters = {  	"retrieve"      : options.retrieve,
                        	"retrieved"     : options.retrieved,
                        	"offset"        : options.offset };

    	// Reinitialize the lazyloader so that it correctly handles the listview on the artists page
    	$( "#index" ).lazyloader( "reInitialize", options, settings, parameters );

    	var sortable_options = 	{ 	disabled: false,
			    		axis: 'y', 
				    	containment: 'parent',
				    	distance: 1, 
				    	opacity: 0.7, 
				    	scroll: true,
				    	scrollSensitivity: 160,
				    	scrollSpeed: 30,
				    	revert: true,
				    	placeholder: 'ui-state-highlight-sortable',
				    	items: 'li',
				    	handle: 'a.move',
				    	delay: 800 	};

	$( "#playlistTracks" ).sortable( sortable_options );
	$( 'body' ).disableSelection();
});

$('body').on('pageinit', '#settings', function(evt, ui) {
	

	$('#settings').on('change', '#theme_id', function(event, ui) {

		if ($('#theme_id :selected').val() == "0"){

			$.mobile.changePage("/settings/custom/theme", { 
				allowSamePageTransition: true,
				transition: "slideup",
				reloadPage: false,
				showLoadMsg: true,
				changeHash: false
			} ); 

			//$('#theme_id option:selected').val('1');

			//alert($('#theme_id option:selected').val());

			//refresh theme select box and force rebuild
			//$('#theme_id').selectmenu('refresh', true);
		}
	});
});

$('body').on('pagebeforeshow', '#music_uploader', function(evt, ui) {

	// Style the plupload's header as we would our own
	$('div.plupload_header').addClass('ui-bar-'+theme.bars)
				.attr('role', 'banner');
});

$('body').on('pageinit', '#music_uploader', function(evt, ui) {

	// Convert divs to queue widgets when the DOM is ready
	$(function() {

	    $("#uploader").pluploadQueue({
	        // General settings
	        runtimes : 'html5',
	        url : '/upload/music',
	        max_file_size : '128mb',
	        // chunk_size : '2mb',
	        unique_names : true,
	        multiple_queues : true,
	        // Resize images on clientside if we can
	        resize : {width : 320, height : 240, quality : 90},
	        // Specify what files to browse for
	        filters : [
	            {title : "MP3 files", extensions : "mp3"},
	            {title : "OGG files", extensions : "ogg"}
	        ],

			// Post init events, bound after the internal events
			init : {
				Refresh: function(up) {
					// Called when upload shim is moved
					//console.log('[Refresh]');
				},

				StateChanged: function(up) {
					// Called when the state of the queue is changed
					//console.log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : up.state);

					if (up.state == plupload.STOPPED) {

						// Refresh MPD database after uploads have completed
						control_mpd('update');

						// The data-url attribute has to be set on the data-role="page" div on the /playlists page so the 
						// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
						// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
						//change_page('/', $.mobile.defaultPageTransition, false, false, true, null, null, true, false);
					}
				},

				QueueChanged: function(up) {
					// Called when the files in queue are changed by adding/removing files
					//console.log('[QueueChanged]');
				},

				UploadProgress: function(up, file) {
					// Called while a file is being uploaded
					//console.log('[UploadProgress]', 'File:', file, "Total:", up.total);
				},

				FilesAdded: function(up, files) {
					// Callced when files are added to queue
					//console.log('[FilesAdded]');

					//plupload.each(files, function(file) {
					//	console.log('  File:', file);
					//});
				},

				FilesRemoved: function(up, files) {
					// Called when files where removed from queue
					//console.log('[FilesRemoved]');

					//plupload.each(files, function(file) {
					//	console.log('  File:', file);
					//});
				},

				FileUploaded: function(up, file, info) {
					// Called when a file has finished uploading
					//console.log('[FileUploaded] File:', file, "Info:", info);

					// Refresh MPD database after uploads have completed (if we wanted to refresh after each upload)
					//control_mpd('update');
				},

				ChunkUploaded: function(up, file, info) {
					// Called when a file chunk has finished uploading
					//console.log('[ChunkUploaded] File:', file, "Info:", info);
				},

				Error: function(up, args) {
					// Called when a error has occured
					//console.log('[error] ', args);
				}
			}


	    });
	    // Client side form validation
	    $('form').submit(function(e) {
	        var uploader = $('#uploader').pluploadQueue(); 
	        // Files in queue upload them first
	        if (uploader.files.length > 0) {
	            // When all files are uploaded submit form
	            uploader.bind('StateChanged', function() {
	                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
	                    $('form')[0].submit();
	                }
	            });
	            uploader.start();
	        } else {
	            alert('You must queue at least one file.');
	        }
	        return false;
	    });
	});
});


// **********************************************************************************
// Page show event handlers
// **********************************************************************************

/*$( 'body' ).on( 'pageshow', function( event, ui ){

	alert("test");

    $(function(){
      $( '[data-position=fixed]' ).fixedtoolbar({ updatePagePadding:false }).trigger('create');
    });
});*/

// This is only used for phonegap native app testing
$('body').on('pageshow', '#register_form', function(evt, ui) {

	//alert("register form pageshow called");
     /*$.getJSON("http://www.musotic.com/login/check/jsonp?callback=?",{
     	site_address: entered_site_address
     },function(data) {
        // Handle response here
        //console.info("Twitter returned: ",tweets);
        alert(JSON.stringify(data));
     });*/
});


/*$("#artistsList").bind("lazyloadercreate", function () {

	$(this).lazyloader( "reset", "artists" );

});*/

$('body').on('pageshow', '#index', function(evt, ui) {

	// reinitialize the queue track removal array
	//removed_from_queue = Array();

	//$( "#index" ).lazyloader( "resetAll" );

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-' + theme.body);

	$('#playerCurrentlyPlayingDiv').css('max-width', ($(window).width() * .9)).trigger( 'updatelayout' );

	if (typeof ui.prevPage[0] != 'undefined') {
		
		if (ui.prevPage[0].id == 'settings') {

			// make sure the fixed header and footer get repositioned after saving settings
			//$.mobile.fixedToolbars.show();

		} else if (ui.prevPage[0].id == 'apply_settings') {

			// make sure the fixed header and footer get repositioned after applying settings
			//$.mobile.fixedToolbars.show();

		} else if (ui.prevPage[0].id == 'music_uploader') {

			// we don't need to refresh the current_playlist object if coming from music_uploader

		} else {
		
			$.mobile.loading( "hide" );

			//setTimeout(function() {

			current_playlist = $.trim( get_mpd_playlist( shuffle_queue ) );

			if ( current_playlist != "" ){

				playlist = $.parseJSON( current_playlist );

				/*count = 0;
				message = "";
				for (i=0; i<playlist.tracks.length; i++){
					
					track = playlist.tracks[i];
					message = message + "track "+i+" - mpd_index "+track.mpd_index+" - "+track.artist+" - "+track.album+" - "+track.title+"\n";
					count++;
				}
				message = "streaming.php -> pageshow: "+count+" tracks loaded\n"+message;
				alert(message);*/
			
			} else {
				
				playlist = "";
			}

			//}, 2000);
			
		}
	}

	//window.scrollTo(0, 1);

	// This is disabled on the Queue page for the drag and drop functionality - so, re-enable it
	$( 'body' ).enableSelection();

	$( "#index" ).lazyloader( "resetAll" );
});

/*$('body').on('pageshow', '#settings', function(evt, ui) {

	if (typeof ui.prevPage[0] != 'undefined') {
		
		if (ui.prevPage[0].id == 'apply_settings') {

		    // hide the loading message
		    $.mobile.loading( "hide" );

		    $.mobile.loadingMessage = 'Loading';

			// we don't need to refresh the current_playlist object if coming from settings
			$('.ui-dialog').dialog('close');
		} 
	}

	if ($('#theme_id option:selected').val() == 0) {
		
		$('#theme_id option:selected').val('1');

		//alert($('#theme_id option:selected').val());

		//refresh theme select box and force rebuild
		$('#theme_id').selectmenu('refresh', true);
	}
});*/


$('body').on('pageshow', '#artists', function(event, ui) {

	//alert("inside artists pageshow");

	// clear lazy loading session variables specific to albums (section=albums)
	/*$.ajax({

		type: "POST",
		url: "/home/clearSession",
		async: true,
		data: "section=albums",
		success: function(msg){

			if (parseInt(msg)) {

				// reinitialize the albums lazy loader default
				albums_listed_so_far = default_albums_listed_so_far;
			}
		}
	});*/

	//$( "#albumsList" ).lazy( "reset", "albums" );

	$.mobile.loading( "hide" );
});

$('body').on('pageshow', '#genres', function(event, ui) {
	
	$( "#index" ).lazyloader( "reset", "artists" );

	$.mobile.loading( "hide" );
});

$('body').on('pageshow', '#playlists', function(event, ui) {

	$.mobile.loading( "hide" ); 
});

$('body').on('pageshow', '#stations', function(event, ui) {

	$.mobile.loading( "hide" ); 

	/* Stations are not being lazyloaded at the moment

        // Reset the lazy loader instance for the stations page
        $( "#index" ).lazyloader( "reset", "stations" );

        // Use an automatic threshold that's a function of the height of the viewport
        threshold = $( window ).height();

        // Set up the variable options to pass to the lazyloader reinitialize function
        var options = { "threshold"	: threshold,
                        "retrieve"	: 20,
                        "retrieved"	: 20,
                        "bubbles"       : true,
                        "offset"        : 0 };

        // Set up the page specific settings to pass to the lazyloader reinitialize function
        var settings = {        "pageId"                : "stations",
                        	"templateType"          : "dust",
                        	"templateId"            : "stations",
                        	"templatePrecompiled"   : true,
                        	"mainId"                : "stationsList",
                        	"progressDivId"         : "lazyloaderProgressDiv",
                                "moreUrl"               : "/stations/more",
                                "clearUrl"              : "/home/clearSession"  };

        // Set up the post parameters to pass to the lazyloader reinitialize function
        var parameters = {      "retrieve"	: options.retrieve,
                                "retrieved"	: options.retrieved,
                                "offset"	: options.offset };

        // Reinitialize the lazyloader so that it correctly handles the listview on the artists page
        $( "#index" ).lazyloader( "reInitialize", options, settings, parameters );
	*/
});

$('body').on('pageshow', '#queue', function(event, ui) {

	$.mobile.loading( "hide" ); 

	$('#currentlyPlayingArtistDiv').css('max-width', ($(window).width() * .6));
	$('#currentlyPlayingAlbumDiv').css('max-width', ($(window).width() * .6));
	$('#currentlyPlayingTrackDiv').css('max-width', ($(window).width() * .6));
});

$('body').on('pageshow', '#admin', function(evt, ui) {

	$.mobile.loading( "hide" );
	
	if (typeof ui.prevPage[0] != 'undefined') {
		
		if (ui.prevPage[0].id == 'edit_user_config') {

			$.mobile.loading( "hide" );

		} else if (ui.prevPage[0].id == 'edit_site_config') {

			$.mobile.loading( "hide" );

		} else if (ui.prevPage[0].id == 'users') {

			$.mobile.loading( "hide" );

		} else {
		
			$.mobile.loading( "hide" );
		}
	}

	// set the style to use for the active button state (button hover will give the desired effect
	$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

	//alert("dollar.mobile.activeBtnClass: "+theme.active);
});

$('body').on('pageshow', '#setup', function(evt, ui) {

    $.mobile.loading( "hide" );

    $('body').attr('class', 'ui-mobile-viewport ui-overlay-' + theme.body);

    // set the style to use for the active button state (button hover will give the desired effect
    $.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;
});

$('body').on('pageshow', '#music_uploader', function(evt, ui) {

    // Refresh the listview so it is re-enhanced by JQM
    //$( '.ui-btn' ).button( 'refresh' );

    // Update the data-theme attribute and the relevant css classes for any data-role=button elements
    $('*[data-role="button"]').each(function(index){

        $(this).attr('data-theme',theme.buttons)
        		.removeClass('ui-btn-up-' + theme.buttons)
        		.addClass('ui-btn-up-' + theme.buttons);
    });
});

$('body').on('pageshow', '#save_playlist', function(evt, ui) {

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

	if (typeof ui.prevPage[0] != 'undefined') {
		
		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'save_playlist') {

			var success = true;

			$('div.required-field-error').each(function( index ) {

				// if we get inside the loop, then an error occurred
				success = false;
			});
			
			if (success) {

				$.mobile.loadingMessage = 'Please wait';
				$.mobile.loading( "show" );

				goBackTimer = setTimeout(function() {
				
					$.mobile.loading( "hide" );
					$.mobile.loadingMessage = 'Please wait';
	
					history.go(-1);	
					
				}, 1000);

				//$('.ui-dialog').dialog('close');	
			}
		}
	}
});

$('body').on('pageshow', '#admin_payments', function(evt, ui) {

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

	if (typeof ui.prevPage[0] != 'undefined') {
		
		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'admin_payments') {

			window.scrollTo( 0, 1 );

			// Removing previous item from urlHistory stack 
        	//$.mobile.urlHistory.stack.pop();

        	// Decrement urlHistory activeIndex 
        	//$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 
		} 
	}
});

$('body').on('pageshow', '#edit_site_config', function(evt, ui) {

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

	if (typeof ui.prevPage[0] != 'undefined') {
		
		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'edit_site_config') {

			window.scrollTo( 0, 1 );

			// Removing previous item from urlHistory stack 
        	//$.mobile.urlHistory.stack.pop();

        	// Decrement urlHistory activeIndex 
        	//$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 
		} 
	}
});

$('body').on('pageshow', '#edit_user_config', function(evt, ui) {

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

	if (typeof ui.prevPage[0] != 'undefined') {
		
		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'edit_user_config') {

			window.scrollTo( 0, 1 );

			// Removing previous item from urlHistory stack 
        	//$.mobile.urlHistory.stack.pop();

        	// Decrement urlHistory activeIndex 
        	//$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 
		} 
	}
});

$('body').on('pageshow', '#playlists', function(evt, ui) {

	// This fixes the issue that was occurring where you select a playlist, then click Delete, 
	// then select No to cancel then go back to /playlists - if click Back again, it wasn't going anywhere
	// because activeIndex was equal to 2, but it needs to be 1
	//if ( $.mobile.urlHistory.activeIndex > 1 ) {
	//	$.mobile.urlHistory.activeIndex = 1;
	//}

	//alert(JSON.stringify($.mobile.urlHistory.stack));

	//alert("$.mobile.urlHistory.activeIndex: "+$.mobile.urlHistory.activeIndex);
});

$('body').on('pageshow', '#account', function(event, ui) {

	// set the style to use for the active button state (button hover will give the desired effect
	$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

	if (typeof ui.prevPage[0] != 'undefined') {

		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'account') {

			window.scrollTo( 0, 1 );
		} 
	}
});

$.widget('blueimp.fileupload', $.blueimp.fileupload, {

    options: {
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        processQueue: {
            action: 'validate',
            acceptFileTypes: '@',
            disabled: '@disableValidation'
        }
    },

    processActions: {

        validate: function (data, options) {
            if (options.disabled) {
                return data;
            }
            var dfd = $.Deferred();
            var file = data.files[data.index];
            
		if (!options.acceptFileTypes.test(file.type)) {
                	file.error = 'Invalid file type.';
                	dfd.rejectWith(this, [data]);
            	} else {
                	dfd.resolveWith(this, [data]);
            	}
           	 return dfd.promise();
        }

    }

});

$('body').on('pageshow', '#station', function(evt, ui) {

	/* 
	 * Instantiate the jQuery File Upload widget
  	 * See project wiki for options: https://github.com/blueimp/jQuery-File-Upload/wiki/Options
	 */ 
	$('#station_image_file').fileupload({
		url: '/upload/station/icon',
		replaceFileInput: false,
		sequentialUploads: true,
		dataType: 'json',
        	acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		maxFileSize: 5242880,
        	add: function(e, data) {
                	
			var uploadErrors = [];
                	var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
			var maxFileSize = 5242880;

			// Make sure we remove any validation error classes from the input
			$('#station_image_file_div').children().removeClass('required-field');
	
			// Make sure we remove any existing error text and reset the height before appending the new error text
			$('#station_image_file_div')
				.closest('.station-field-divs')
				.height( 80 )
				.find('div.required-field-error')
				.remove();

			consoleLog("The file being uploaded is as follows", data.originalFiles);
                	
			if( !acceptFileTypes.test( data.originalFiles[0]['type'] )) {
			
				uploadErrors.push( 'The selected file is not an allowed type' );
                	}
                
			if( data.originalFiles[0]['size'] > maxFileSize ) {
			
				uploadErrors.push( 'The selected file is larger than '+((maxFileSize / 1024) / 1024)+'MB');
                	}
                
			if( uploadErrors.length > 0 ) {

				// If there are form validation errors, then highlight the border of the input red
				$('#station_image_file_div').children().addClass('required-field');

				var errorMessages = '<div class="error-message-divs">'+uploadErrors.join('</div><div class="error-message-divs">')+'</div>';

				extraHeight = uploadErrors.length * 20;
	
				currentHeight = $('#station_image_file_div').closest('.station-field-divs').height();

				// Find the parent div of the station image and input and increase the height and append the error message
				$('#station_image_file_div')
					.closest('.station-field-divs')
					.height( currentHeight + extraHeight )
					.append('<div class="required-field-error">'+errorMessages+'</div>');
                	
			} else {
			
				data.submit();
			}
        	},
		start: function (e) {

			$.mobile.loadingMessage = "Uploading image";
			$.mobile.loading( "show" ); 

			consoleLog("File upload started");
		},
		progress: function (e, data) {

			var progress = parseInt(data.loaded / data.total * 100, 10);
					
			$.mobile.loadingMessage = progress+"%";
				
			consoleLog(progress);
		},
		done: function (e, data) {

			consoleLog("File upload complete");
			consoleLog("e", e);
			consoleLog("data", data);
			consoleLog("Setting the uploaded_image element src to: " + "/" + data.result.baseurl + data.result.filename );

			// The new station image is only temporary until the form is saved		
			$('#uploaded_image').attr('src', '/' + data.result.baseurl + data.result.filename);
					
			// We need to set the hidden input for station_icon_id to the new id
			$('#station_icon_id').val( data.result.id );	
				
			$.mobile.loading( "hide" ); 
			$.mobile.loadingMessage = "Loading";
		},
		fail: function( e, data ) {
			
			consoleLog("File upload failed");

			$.mobile.loading( "hide" ); 
			$.mobile.loadingMessage = "Loading";
		}
	});

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');

    	$('#saved_successfully').removeClass('success-top-padding');

	if (typeof ui.prevPage[0] != 'undefined') {
		
		// This must be because the CodeIgniter form validation returned false and is reshowing the page
		if (ui.prevPage[0].id == 'station') {

			window.scrollTo( 0, 1 );
		} 
	}
});

$('body').on('pageshow', '#login', function(evt, ui) {

	// This is only used for phonegap native app testing
     /*$.getJSON("http://www.musotic.com/login/check/jsonp?callback=?",{
     	site_address: "www.musotic.com"
     },function(data) {
        // Handle response here
        //console.info("Twitter returned: ",tweets);
        alert(JSON.stringify(data));
     });*/

	// If there are form validation errors, then highlight the border of the input red
	//$('div.required-field-error').prev().css('border', '2px solid #CC0000');
});

// **********************************************************************************
// Page hide event handlers
// **********************************************************************************

$('body').on( 'pagehide', '#create_new_theme', function(event, ui) {

	if ($('#theme_id option:selected').val() == 0) {
		
		$('#theme_id option:selected').val('1');

		//refresh theme select box and force rebuild
		$('#theme_id').selectmenu('refresh', true);

	}
});

$('body').on('pagebeforeshow', '#index', function(evt, ui) {

});

$('body').on('pagebeforeshow', '#station', function(evt, ui) {
	
	if (typeof ui.prevPage[0] != 'undefined') {

		if (ui.prevPage[0].id == 'station') {

			// Removing previous item from urlHistory stack (tracks page with ui-dialog hash)
			//$.mobile.urlHistory.stack.pop();

			// Decrement urlHistory activeIndex 
			//$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 
		}
	}
});

$('body').on('pagebeforeshow', '#queue', function(event, ui) {

	$('#currentlyPlayingArtistDiv').css('max-width', ($(window).width() * .6));
	$('#currentlyPlayingAlbumDiv').css('max-width', ($(window).width() * .6));
	$('#currentlyPlayingTrackDiv').css('max-width', ($(window).width() * .6));
});

$('body').on('pagebeforeshow', '#account', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-' + theme.body);

	$.mobile.activeBtnClass = 'ui-btn-hover-' + theme.active;
});

$('body').on('pagebeforeshow', '#admin', function(event, ui) {

    $('body').attr('class', 'ui-mobile-viewport ui-overlay-' + theme.body);
});

// **********************************************************************************
// Page before change event handlers
// **********************************************************************************

$('body').on('pagebeforechange', '#artists', function(event, ui) {

	//alert("window.scrollY: "+window.scrollY);
	//$.mobile.silentScroll(0);
});

$('body').on('pagebeforechange', '#albums', function(event, ui) {

	//alert("window.scrollY: "+window.scrollY);
	//$.mobile.silentScroll(0);
});

$('body').on('pagebeforechange', '#tracks', function(event, ui) {

	//alert("window.scrollY: "+window.scrollY);
	//$.mobile.silentScroll(0);
});

// **********************************************************************************
// Element change event handlers
// **********************************************************************************

$('body').on('change', '#volume_control', function() {

	// there seems to be a bug in jQuery mobile where the swipeleft and swiperight events are being 
	// fired even when the user is actively controlling a slider.  So, I'll attempt to prevent it.		
	just_swiped_left = true;
	just_swiped_right = true;

 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
 	// user a few seconds to get the value they want...then send the control command
 	if (!waiting_to_adjust_volume) {
	
	 	waiting_to_adjust_volume = true;

	 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
	 	// user a second to get the value they want...then send the control command.
	 	// The swipe_left and swipe_right variables are a hack to prevent those events from firing
	 	// when the user is adjusting the slider.
		setTimeout(function(){ new_volume = $('#volume_control').val(); post = { "parameters" : [ { "volume" : new_volume } ] }; control_mpd('set_volume', post.parameters[0]); waiting_to_adjust_volume = false; just_swiped_left = false; just_swiped_right = false;}, 1000);
	}
});

$('body').on('change', '#xfade_control', function() {
	
	// there seems to be a bug in jQuery mobile where the swipeleft and swiperight events are being 
	// fired even when the user is actively controlling a slider.  So, I'll attempt to prevent it.		
	just_swiped_left = true;
	just_swiped_right = true;

 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
 	// user a few seconds to get the value they want...then send the control command
 	if (!waiting_to_adjust_xfade) {
	
	 	waiting_to_adjust_xfade = true;

	 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
	 	// user a second to get the value they want...then send the control command.
	 	// The swipe_left and swipe_right variables are a hack to prevent those events from firing
	 	// when the user is adjusting the slider.
		setTimeout(function(){ new_xfade_value = $('#xfade_control').val(); post = { "parameters" : [ { "crossfade" : new_xfade_value } ] }; control_mpd('set_crossfade', post.parameters[0]); waiting_to_adjust_xfade = false; just_swiped_left = false; just_swiped_right = false;}, 500);
	}
});


$('body').on('change', '#volume_fade_control', function() {
	
	// there seems to be a bug in jQuery mobile where the swipeleft and swiperight events are being 
	// fired even when the user is actively controlling a slider.  So, I'll attempt to prevent it.		
	just_swiped_left = true;
	just_swiped_right = true;

 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
 	// user a few seconds to get the value they want...then send the control command
 	if (!waiting_to_adjust_volume_fade) {
	
	 	waiting_to_adjust_volume_fade = true;

	 	// This prevents the firing off 10 or possible more MPD commands. Let's give the 
	 	// user a second to get the value they want...then send the control command.
	 	// The swipe_left and swipe_right variables are a hack to prevent those events from firing
	 	// when the user is adjusting the slider.
		setTimeout(function(){ new_volume_fade_value = $('#volume_fade_control').val(); waiting_to_adjust_volume_fade = false; just_swiped_left = false; just_swiped_right = false;}, 500);
	}
});

$('body').on('change', '#operating_mode', function(event, ui) {
	
	//alert($('#operating_mode :selected').text());

	if ($('#operating_mode :selected').text() == "Streaming"){
		
		mode = "streaming";

	} else {

		mode = "localplay";
	}
});

$('body').on('change', '#station_visibility', function(evt, ui) {
	
        var current_station_visibility = $( '#station_visibility' ).prop( 'value' );

        var station_visibility_now = ((current_station_visibility == 'on') ? 'off' : 'on');

        $( "#station_visibility" ).prop( 'value', station_visibility_now ).checkboxradio( 'refresh' );
});


$('body').on('change', '#user_account_active', function(evt, ui) {
	
	var user_account_active = $( '#user_account_active' ).prop( 'value' );

	var user_account_now_active = ((user_account_active == 'on') ? 'off' : 'on');

	$( "#user_account_active" ).prop( 'value', user_account_now_active ).checkboxradio( 'refresh' );
});

$('body').on('change', '#paypal_sandbox_mode', function(evt, ui) {

        var sandbox_enabled = $( '#paypal_sandbox_mode' ).prop( 'value' );

	// Toggle the sandbox enabled checkbox
        var sandbox_enabled_now = ((sandbox_enabled == 'on') ? 'off' : 'on');

        $( "#paypal_sandbox_mode" ).prop( 'value', sandbox_enabled_now ).checkboxradio( 'refresh' );
});

// **********************************************************************************
// Element click event handlers
// **********************************************************************************

$( 'body' ).on( 'click', '#playpause', function( evt ) {

	evt.preventDefault();

	$.mobile.loading( "hide" ); 
   	$.mobile.loadingMessage = "Loading";

	if ( playing ) {

		// Hide the track info div and update the pause button to be a play button
		updatePlayerDisplay( playing, "pause" );

		if ( next_track_already_added ) {

			// We only want to try and stop playerOne if it has it's src set to something
			if ( $( inactivePlayerSelector ).data( "jPlayer" ).status.srcSet ) {
		
				$( inactivePlayerSelector ).jPlayer('stop');
			}

			$( inactivePlayerSelector ).jPlayer( "volume", 1 );
			$( activePlayerSelector ).jPlayer( "volume", 1 );
		}

		// Pause the active player instance
		$( activePlayerSelector ).jPlayer( "pause" );

		// send the command to the MPD server to pause the currently playing track
		control_mpd( 'pause' );

		playing = false;
		paused = true;

	} else { // it must have just loaded, or played and then paused

		/*
		// Update the current track information like album art, track name, duration, load progress, etc.
		updateCurrentTrackInfo( playlist.tracks[ track_position ] );

		// Show the track info div and update the play button to be a pause button
		updatePlayerDisplay( playing );

		// Reset the load progress complete tracker
		load_progress_complete = false;

		$( activePlayerSelector ).jPlayer( "setMedia", {mp3 : playlist.tracks[ track_position ].url } );	
		$( activePlayerSelector ).jPlayer( "play", current_track_position );

		// send the command to the MPD server to start playing the current track
		control_mpd('play');

		playing = true;
		*/

		skipto( "same" );
	}
});

$( 'body' ).on( 'click', '#next', function( evt ) {
	
	evt.preventDefault();

	skipto( "next" );

	return false;
});

$( 'body' ).on( 'click', '#prev', function( evt ) {

	evt.preventDefault();	

	skipto( "previous" );	

	return false;
});

$( 'body' ).on( 'click', '#repeat', function( evt ) {
	
	if ( !repeat_track ) {
		
		$.mobile.loading( "show", theme.bars, "Repeat On", true );
		
		setTimeout( function() { $.mobile.loading( "hide" ); }, 3000 );

		repeat_track = true;

		$( '#repeat' ).removeClass( 'ui-btn-'+theme.buttons )
						.removeClass( 'ui-btn-up-'+theme.buttons )
						.removeClass( 'ui-btn-hover-'+theme.buttons )
						.attr( 'data-theme', theme.action )
						.attr( 'data-icon', "repeat" )
						.addClass( 'ui-btn-'+theme.action )
						.addClass( 'ui-btn-up-'+theme.action );

		post = { "parameters" : [ { "repeat" : '1' } ] };
		control_mpd( 'repeat', post.parameters[ 0 ] );	

	} else {
	
		$.mobile.loading( "show", theme.bars, "Repeat Off", true );
		
		setTimeout( function() { $.mobile.loading( "hide" ); }, 3000 );

		repeat_track = false;

		$( '#repeat' ).removeClass( 'ui-btn-'+theme.action )
						.removeClass( 'ui-btn-up-'+theme.action )
						.removeClass( 'ui-btn-hover-'+theme.action )
						.attr( 'data-theme', theme.buttons )
						.attr( 'data-icon', "repeat" )
						.addClass( 'ui-btn-'+theme.buttons )
						.addClass( 'ui-btn-up-'+theme.buttons );

		post = { "parameters" : [ { "repeat" : '0' } ] }; 
		control_mpd( 'repeat', post.parameters[ 0 ] );	
	}
	
	/*if (primary_player == 1) {
	
		$("#playerOne").jPlayer("loop", !repeat);

	} else {

		$("#playerTwo").jPlayer("loop", !repeat);
	}*/

	$( activePlayerSelector ).jPlayer( "loop", repeat_track );
});

$( 'body' ).on( 'click', '#shuffle', function( evt ) {
	
	if ( typeof playlist.tracks !== 'undefined' ) {

		if ( playlist.tracks.length > 0 ) {

			if ( !shuffle_queue ) {

				$.mobile.loading( "show", theme.bars, "Shuffling the playlist", true );

				//current_audio_element.loop = false;
				//repeat_track = false;
				shuffle_queue = true;

				$( '#shuffle' ).removeClass( 'ui-btn-'+theme.buttons )
								.removeClass( 'ui-btn-up-'+theme.buttons )
								.removeClass( 'ui-btn-hover-'+theme.buttons )
								.attr( 'data-theme', theme.action )
								.attr( 'data-icon', "shuffle" )
								.addClass( 'ui-btn-'+theme.action )
								.addClass( 'ui-btn-up-'+theme.action );
				
				playlist = $.parseJSON( shuffle_playlist( JSON.stringify( playlist ) ) );

				setTimeout(function() { $.mobile.loading( "hide" ); }, 2000);

			} else {

				$.mobile.loading( "show", theme.bars, "Unshuffling the playlist", true);
				
				shuffle_queue = false;
				
				$( '#shuffle' ).removeClass( 'ui-btn-'+theme.action )
								.removeClass( 'ui-btn-up-'+theme.action )
								.removeClass( 'ui-btn-hover-'+theme.action )
								.attr( 'data-theme', theme.buttons )
								.attr( 'data-icon', "shuffle" )
								.addClass( 'ui-btn-'+theme.buttons )
								.addClass( 'ui-btn-up-'+theme.buttons );

				// set the current_audio_element.pos to the mpd_index of the currently playing track, so that the user can proceed from there
				//current_audio_element.pos = playlist.tracks[ current_audio_element.pos ].mpd_index;
				
				track_position = playlist.tracks[ track_position ].mpd_index;
				
				playlist = unshuffle_playlist();

				setTimeout( function() { $.mobile.loading( "hide" ); }, 2000 );
			}

		} else {
			
			//alert('You will be able to shuffle it up after you add some tracks to your queue.');
			$.mobile.loading( "show", theme.bars, "Nothing to shuffle", true );
			setTimeout( function() { $.mobile.loading( "hide" ); }, 2000 );
		}

	} else {
		
		//alert('You will be able to shuffle it up after you add some tracks to your queue.');
		$.mobile.loading( "show", theme.bars, "Nothing to shuffle", true );
		setTimeout( function() { $.mobile.loading( "hide" ); }, 2000 );
	}
});

$( 'body' ).on( 'click', '#settings_form_save', function( evt, ui ) {

	evt.preventDefault();

	var is_admin_user 	= $('#is_admin_user').val();
	var new_theme_id 	= $('#theme_id').val();
	var new_mode 		= $('#mode').val();
	var new_language_id	= $('#language_id').val();
	var new_volume 		= $('#volume_control').val();
	var new_crossfade 	= $('#xfade_control').val();
	var new_volume_fade 	= $('#volume_fade_control').val();

	if (((typeof new_volume == "undefined") || (new_volume == '')) && is_admin_user) {
		
		new_volume = 5;
	} 
	
	if (((typeof new_crossfade == "undefined") || (new_crossfade == ''))) {
		
		new_crossfade = 5;
	}

	if (((typeof new_volume_fade == "undefined") || (new_volume_fade == ''))) {
		
		new_volume_fade = 5;
	}

	//alert("theme_id: "+new_theme_id+"\nmode: "+new_mode+"\nvolume: "+new_volume+"\ncrossfade: "+new_crossfade+"\nvolume_fade: "+new_volume_fade);

	if (	((typeof new_theme_id 		!= "undefined") && (new_theme_id 	!= '')) && 
		((typeof new_mode 		!= "undefined") && (new_mode 		!= '')) &&
		((typeof new_language_id 	!= "undefined") && (new_language_id 	!= ''))	) {

		$.mobile.loadingMessage = 'Saving Settings';
		$.mobile.loading( "show" );

		$.ajax({
			type: "POST",
			url: "/settings/save",
			async: true,
			data: "theme_id="+new_theme_id+"&mode="+new_mode+"&volume="+new_volume+"&crossfade="+new_crossfade+"&volume_fade="+new_volume_fade+"&language_id="+new_language_id,
			success: function(msg){

				saved = $.parseJSON(msg);

				//alert(JSON.stringify(saved));

			    	var current_theme = new Object;

				current_theme.body 	= theme.body;
				current_theme.bars 	= theme.bars;
				current_theme.buttons 	= theme.buttons;
				current_theme.controls 	= theme.controls;
				current_theme.action 	= theme.action;
				current_theme.active 	= theme.active;

				max_crossfade 		= new_crossfade;
				volume_fade 		= new_volume_fade;
				out_volume_left_to_fade = new_volume_fade - 1;
				in_volume_left_to_fade 	= new_volume_fade - 1;

				theme.body 		= saved.theme[0].body;
				theme.bars 		= saved.theme[0].bars;
				theme.buttons 		= saved.theme[0].buttons;
				theme.controls 		= saved.theme[0].controls;
				theme.action 		= saved.theme[0].action;
				theme.active 		= saved.theme[0].active;

				$.mobile.loadingMessageTheme = theme.bars;

				$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

			    	$('.ui-body-' + current_theme.body).each(function(){
			        	$(this).removeClass('ui-body-' + current_theme.body).addClass('ui-body-' + theme.body);    
			    	});

			    	$('.ui-overlay-' + current_theme.body).each(function(){
			        	$(this).removeClass('ui-overlay-' + current_theme.body).addClass('ui-overlay-' + theme.body);    
			    	});

			    	$('.ui-bar-' + current_theme.bars).each(function(){
			        	$(this).removeClass('ui-bar-' + current_theme.bars).addClass('ui-bar-' + theme.bars);
			    	});

				// Update the data-theme attribute and relevant css classes for the children of the ui-select div
				$('div.ui-select').each(function(index){

					$(this).find('div')
							.attr('data-theme', theme.buttons)
							.removeClass('ui-btn-up-' + current_theme.buttons)
							.addClass('ui-btn-up-' + theme.buttons);

					$(this).find('select').attr('data-theme', theme.buttons);
				});

			    	// Update the data-theme attribute and relevant css classes for the children of the parent of slider input element
				$('input[data-type="range"]').parent().each(function(index){
					
					$(this).find('input')
							.attr('data-theme',theme.buttons)
							.attr('data-track-theme',theme.buttons)
							.removeClass('ui-body-' + current_theme.body)
							.addClass('ui-body-' + theme.body);

				 	$(this).find('div[role="application"]').removeClass('ui-btn-down-' + current_theme.buttons)
				 						.addClass('ui-btn-down-' + theme.buttons);

				    	$(this).find('.ui-slider-bg').removeClass('ui-btn-hover-' + current_theme.action)
				    					.addClass('ui-btn-hover-' + theme.action);

				 	$(this).find('a').attr('data-theme', theme.buttons)
				 				.removeClass('ui-btn-up-' + current_theme.buttons)
			    					.addClass('ui-btn-up-' + theme.bars);
				});

				// Update the data-theme attribute and relevant css classes of the parent of the save button
				$('button[type="button"]').parent()
								.attr('data-theme', theme.action)
								.removeClass('ui-btn-up-', current_theme.action)
								.addClass('ui-btn-up-', theme.action);

			    	// Update the data-theme attribute of the save button
				$('button[type="button"]').attr('data-theme', theme.action);


			    	// Update the data-theme attribute and relevant css classes of the prev button element
			    	$('#prev').attr('data-theme', theme.controls)
			    			.removeClass('ui-btn-up-' + current_theme.buttons)
			    			.removeClass('ui-btn-up-' + current_theme.controls)
			    			.addClass('ui-btn-up-' + theme.controls);    

		        	// Update the data-theme attribute and relevant css classes of the playpause button element
		        	$('#playpause').attr('data-theme', theme.controls)
		        			.removeClass('ui-btn-up-' + current_theme.buttons)
		        			.removeClass('ui-btn-up-' + current_theme.controls)
		        			.addClass('ui-btn-up-' + theme.controls);        

		        	// Update the data-theme attribute and relevant css classes of the next button element
		        	$('#next').attr('data-theme', theme.controls)
		        			.removeClass('ui-btn-up-' + current_theme.buttons)
		        			.removeClass('ui-btn-up-' + current_theme.controls)
		        			.addClass('ui-btn-up-' + theme.controls); 

			    	// Update the data-theme and data-divider-theme attributes for the divs with data-role=page
				$('div[data-role="page"]').each(function(index){
					$(this).attr('data-theme',theme.buttons);
					$(this).attr('data-divider-theme',theme.bars);
				});

				// Update the data-theme and data-divider-theme attributes for the divs with data-role=header
				$('div[data-role="header"]').each(function(index){
					$(this).attr('data-theme',theme.buttons);
					$(this).attr('data-divider-theme',theme.bars);
				});

				// Update the data-theme and data-divider-theme attributes for the divs with data-role=content
				$('div[data-role="content"]').each(function(index){
					$(this).attr('data-theme',theme.buttons)
					.attr('data-divider-theme',theme.bars);
				});

				// Update the data-theme and data-divider-theme attributes for the divs with data-role=footer
				$('div[data-role="footer"]').each(function(index){
					$(this).attr('data-theme',theme.buttons)
					.attr('data-divider-theme',theme.bars);
				});

				// Update the data-theme attribute and the relevant css classes for any data-role=button elements
				$('*[data-role="button"]').each(function(index){

					$(this).attr('data-theme',theme.buttons)
						.removeClass('ui-btn-up-' + current_theme.buttons)
						.addClass('ui-btn-up-' + theme.buttons);
				});

			    	// Update the data-theme and data-divider-theme attributes for the libraryNav ul
			    	$('#libraryNav').attr('data-theme', theme.buttons)
			    			.attr('data-divider-theme', theme.buttons);

			    	// Update the data-theme attribute and relevant css classes for the libraryNav list items
			    	$( '#libraryNav li' ).not( 'li[data-role="list-divider"]' ).each(function(index){

				    	$(this).attr('data-theme', theme.buttons)
				    		.removeClass('ui-btn-up-' + current_theme.buttons)
				    		.addClass('ui-btn-up-' + theme.buttons);
			    	});

			    	$( '#libraryNav li[data-role="list-divider"]' ).removeClass('ui-btn-up-' + current_theme.buttons)
			    							.removeClass('ui-btn-up-' + current_theme.bars)
			    							.removeClass('ui-bar-' + current_theme.bars)
			    							.addClass('ui-bar-' + theme.bars);

			    	// Update the data-theme and data-divider-theme attributes for the adminNav ul
			    	$('#adminNav').attr('data-theme', theme.buttons)
			    			.attr('data-divider-theme', theme.buttons);

			    	// Update the data-theme attribute and relevant css classes for the adminNav list items
			    	$( '#adminNav li' ).not( 'li[data-role="list-divider"]' ).each(function(index){

			    		$(this).attr('data-theme', theme.buttons)
			    			.removeClass('ui-btn-up-' + current_theme.buttons)
			    			.addClass('ui-btn-up-' + theme.buttons);
			    	});

			    	$( '#adminNav li[data-role="list-divider"]' ).removeClass('ui-btn-up-' + current_theme.buttons)
			    							.removeClass('ui-btn-up-' + current_theme.bars)
			    							.removeClass('ui-bar-' + current_theme.bars)
			    							.addClass('ui-bar-' + theme.bars);

			    	// hide the loading message
			    	$.mobile.loading( "hide" );

			    	$.mobile.loadingMessage = 'Loading';

			    	// close the settings dialog
				$('#settings').dialog('close');
		    	}
		});
	} 	
});

$('body').on('click', '#confirm_delete_yes', function() {

	var item_type 	= $('#item_type').val();
	var item_name 	= $('#item_name').val();

	//alert("item_type: "+item_type+"\item_name: "+item_name);

	if (item_type == "playlist") {

		post = { 'parameters' : [ { 'playlist_name' : item_name } ] }; 
		control_mpd('delete_playlist', post.parameters[0]);
		
		//history.go(-1);

		//$('.ui-dialog').dialog('close');

		// Go back to the playlists page
		history.go(-2);

		//alert(JSON.stringify($.mobile.urlHistory.stack));

		// Removing previous item from urlHistory stack (tracks page with ui-dialog hash)
//		$.mobile.urlHistory.stack.pop();

		// Decrement urlHistory activeIndex 
//		$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 

		// Removing previous item from urlHistory stack (tracks page)
//		$.mobile.urlHistory.stack.pop();

		// Decrement urlHistory activeIndex 
//		$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 

		// Removing previous item from urlHistory stack (playlists page cause that's where we're going)
//		$.mobile.urlHistory.stack.pop();

		// Decrement urlHistory activeIndex 
//		$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 

		// The data-url attribute has to be set on the data-role="page" div on the /playlists page so the 
		// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
		// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
//		change_page('/playlists', 'pop', false, false, false, null, null, true, false);

	} else if (item_type == "station") {

		var station_id = $('#item_id').val();

		//alert("station_id: "+station_id);

		$.mobile.loadingMessage = 'Deleting Station'; 
		$.mobile.loading( "show" );

		$.ajax({

		   type: "POST",
		   url: "/stations/delete",
		   async: true,
		   data: "station_id="+station_id,
		   
		   success: function(msg){

				$.mobile.loading( "hide" );
				$.mobile.loadingMessage = 'Loading';

		   		// go back to the stations page
				history.go(-2);

				//$('#stations').remove();
				
				//$('.ui-dialog').dialog('close');

				//change_page('/stations', 'pop', false, false, false, null, null, true, false);

				// The data-url attribute has to be set on the data-role="page" dive on the /stations page so the 
				// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
				// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
//				change_page('/stations', 'pop', false, false, true, null, null, true, false);
		   }
		});
	
	} else if (item_type == "user") {

		var user_id = $('#item_id').val();

		//alert("user_id: "+user_id);

		$.mobile.loadingMessage = 'Deleting User Account'; 
		$.mobile.loading( "show" );

		$.ajax({

		   type: "POST",
		   url: "/user/"+user_id+"/delete",
		   async: true,
		   data: "user_id="+user_id,
		   
		   success: function(msg){

				$.mobile.loading( "hide" );
				$.mobile.loadingMessage = 'Loading';

		   		// go back to the users page
				history.go(-2);

				//$('#stations').remove();
				
				//$('.ui-dialog').dialog('close');

				//post_data = "user_id="+user_id;

				// The data-url attribute has to be set on the data-role="page" dive on the /admin/users page so the 
				// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
				// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
//				change_page('/admin/users', 'pop', false, false, true, null, null, true, false);
		   }
		});
	
	} else {

		// nothing to do
	}

});

$('body').on('click', '#confirm_delete_no', function() {

	//history.go(-1);

	$('.ui-dialog').dialog('close');
});

$('body').on('click', '#apply_settings_yes', function() {

    /*$('.ui-dialog').dialog('close');

    // Seeing strange things when doing it this way
	setTimeout(function() { $.mobile.loadingMessage = 'Loading';
					$.mobile.changePage("index.php", { 
					allowSamePageTransition: false,
					reloadPage: true,
					showLoadMsg: true,
					transition:'reverse',
					changeHash: true }).done(function(){ location.reload(true); }); }, 1000);*/

	// give the closing transitions a second or two to finish
	setTimeout(function(){

		$.mobile.loadingMessage = 'Please wait';
		$.mobile.loading( "show" );

		setTimeout(function() {
		
			location.reload(true);

		}, 500);

	}, 2000);

	//$('.ui-dialog').dialog('close');
});

$('body').on('click', '#apply_settings_no', function() {

	//$('.ui-dialog').dialog('close');
});

$('body').on('click', '#create_theme_save', function(evt, ui) {

	evt.preventDefault();

	var theme_name 			= $('#theme_name').val();
	var icon_color			= $('#icon_color').val();
	var bars_letter_code 		= $('#bars_letter_code').val();
	var buttons_letter_code 	= $('#buttons_letter_code').val();
	var body_letter_code 		= $('#body_letter_code').val();
	var controls_letter_code 	= $('#controls_letter_code').val();
	var action_letter_code 		= $('#action_letter_code').val();
	var active_state_letter_code 	= $('#active_state_letter_code').val();

	if ((typeof theme_name != "undefined") && (theme_name != '')) {

		$.mobile.loadingMessage = 'Saving Theme';
		$.mobile.loading( "show" );

		$.ajax({
		   type: "POST",
		   url: "/settings/create/theme",
		   async: true,
		   data: "theme_name="+theme_name+"&icon_color="+icon_color+"&bars_letter_code="+bars_letter_code+"&buttons_letter_code="+buttons_letter_code+"&body_letter_code="+body_letter_code+"&controls_letter_code="+controls_letter_code+"&action_letter_code="+action_letter_code+"&active_state_letter_code="+active_state_letter_code,
		   success: function(msg){

				$.mobile.loading( "hide" );
				$.mobile.loadingMessage = 'Loading';

		   		// go back to the stations page
				history.go(-1);

				//$('#settings').remove();

				//change_page('/settings', 'slideup', true, false, true, null, null, true, false);
		   }
		});

	} else {
		
		$('#theme_name').css("border","2px solid #CC0000");
	}
});

/*$('body').on('click', '#create_playlist_save', function(evt, ui) {
	
	evt.preventDefault();

	var playlist_name = $('#playlist_name').val();

	if ((typeof playlist_name != "undefined") && (playlist_name != '')) {
	
		post = { "parameters" : [ { "playlist_name" : playlist_name } ] };

		control_mpd('save_playlist', post.parameters[0]);

		// go back to the queue page
		//history.go(-1);

        	$('.ui-dialog').dialog('close');

		//$('#queue').remove();

		//change_page('/queue', 'slidedown', false, false, true, null, null, true, false);

	} else {

		$('#playlist_name').closest('div')
			.css("border","2px solid #CC0000")
			.after('<div class="required-field-error">Playlist Name field is required.</div>');
	}
});*/

/*$('body').on('click', '#save_admin_payments', function(evt, ui) {

	evt.preventDefault();

	var paypal_sandbox_mode 	= $('#paypal_sandbox_mode').val();
	var sandbox_master_account 	= $('#sandbox_master_account').val();
	var sandbox_api_username 	= $('#sandbox_api_username').val();
	var sandbox_api_password 	= $('#sandbox_api_password').val();
	var sandbox_api_signature 	= $('#sandbox_api_signature').val();
	var paypal_master_account 	= $('#paypal_master_account').val();
	var paypal_api_username 	= $('#paypal_api_username').val();
	var paypal_api_password 	= $('#paypal_api_password').val();
	var paypal_api_signature 	= $('#paypal_api_signature').val();

	//alert("paypal_sandbox_mode="+paypal_sandbox_mode+"\nsandbox_master_account="+sandbox_master_account+"\nsandbox_api_username="+sandbox_api_username+"\nsandbox_api_password="+sandbox_api_password+"\nsandbox_api_signature="+sandbox_api_signature+"\npaypal_master_account="+paypal_master_account);

	if (((typeof sandbox_master_account != "undefined") && (sandbox_master_account != '')) && 
		((typeof sandbox_api_username != "undefined") && (sandbox_api_username != '')) && 
		((typeof sandbox_api_password != "undefined") && (sandbox_api_password != '')) && 
		((typeof sandbox_api_signature != "undefined") && (sandbox_api_signature != '')) &&
		((typeof paypal_master_account != "undefined") && (paypal_master_account != '')) && 
		((typeof paypal_api_username != "undefined") && (paypal_api_username != '')) &&
		((typeof paypal_api_password != "undefined") && (paypal_api_password != '')) && 
		((typeof paypal_api_signature != "undefined") && (paypal_api_signature != ''))) {

		$.mobile.loadingMessage = 'Saving'; 
		$.mobile.loading( "show" );

		$.ajax({

		   type: "POST",
		   url: "/admin/payments",
		   async: true,
		   data: "paypal_sandbox_mode="+paypal_sandbox_mode+"&sandbox_master_account="+sandbox_master_account+"&sandbox_api_username="+sandbox_api_username+"&sandbox_api_password="+sandbox_api_password+"&sandbox_api_signature="+sandbox_api_signature+"&paypal_master_account="+paypal_master_account+"&paypal_api_username="+paypal_api_username+"&paypal_api_password="+paypal_api_password+"&paypal_api_signature="+paypal_api_signature,
		   
		   success: function(msg){

				$.mobile.loading( "hide" );
				$.mobile.loadingMessage = 'Loading';

		   		// go back to the stations page
				history.go(-1);

				//$('#stations').remove();

				//change_page('/stations', 'slidedown', true, false, true, null, null, true, false);
		   }
		 });

	} else {

		if ((typeof sandbox_master_account == "undefined") || (sandbox_master_account == '')) {
			
			$('#sandbox_master_account').css("border","2px solid #CC0000");
		}

		if ((typeof sandbox_api_username == "undefined") || (sandbox_api_username == '')) {
			
			$('#sandbox_api_username').css("border","2px solid #CC0000");
		}

		if ((typeof sandbox_api_password == "undefined") || (sandbox_api_password == '')) {
			
			$('#sandbox_api_password').css("border","2px solid #CC0000");
		}

		if ((typeof sandbox_api_signature == "undefined") || (sandbox_api_signature == '')) {
			
			$('#sandbox_api_signature').css("border","2px solid #CC0000");
		}

		if ((typeof paypal_master_account == "undefined") || (paypal_master_account == '')) {
			
			$('#paypal_master_account').css("border","2px solid #CC0000");
		}

		if ((typeof paypal_api_username == "undefined") || (paypal_api_username == '')) {
			
			$('#paypal_api_username').css("border","2px solid #CC0000");
		}

		if ((typeof paypal_api_password == "undefined") || (paypal_api_password == '')) {
			
			$('#paypal_api_password').css("border","2px solid #CC0000");
		}

		if ((typeof paypal_api_signature == "undefined") || (paypal_api_signature == '')) {
			
			$('#paypal_api_signature').css("border","2px solid #CC0000");
		}
	}
});*/

/*$('body').on('click', '#save_admin_payments', function(evt, ui) {

	evt.preventDefault();

	var paypal_sandbox_mode 	= $('#paypal_sandbox_mode').val();
	var sandbox_master_account 	= $('#sandbox_master_account').val();
	var sandbox_api_username 	= $('#sandbox_api_username').val();
	var sandbox_api_password 	= $('#sandbox_api_password').val();
	var sandbox_api_signature 	= $('#sandbox_api_signature').val();
	var paypal_master_account 	= $('#paypal_master_account').val();
	var paypal_api_username 	= $('#paypal_api_username').val();
	var paypal_api_password 	= $('#paypal_api_password').val();
	var paypal_api_signature 	= $('#paypal_api_signature').val();

	//alert("about to try the email regex logic");

	// regular expression to test the email addresses to confirm they are of a valid form
    var email_regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var sandbox_master_account_valid_email = email_regex.test(sandbox_master_account);
    var paypal_master_account_valid_email = email_regex.test(paypal_master_account);

    //alert("sandbox_master_account_valid_email: "+sandbox_master_account_valid_email+"\npaypal_master_account_valid_email: "+paypal_master_account_valid_email);


	//alert("paypal_sandbox_mode="+paypal_sandbox_mode+"\nsandbox_master_account="+sandbox_master_account+"\nsandbox_api_username="+sandbox_api_username+"\nsandbox_api_password="+sandbox_api_password+"\nsandbox_api_signature="+sandbox_api_signature+"\npaypal_master_account="+paypal_master_account);

	if (((typeof sandbox_master_account != "undefined") && (sandbox_master_account != '')) && 
		((typeof sandbox_api_username != "undefined") && (sandbox_api_username != '')) && 
		((typeof sandbox_api_password != "undefined") && (sandbox_api_password != '')) && 
		((typeof sandbox_api_signature != "undefined") && (sandbox_api_signature != '')) &&
		((typeof paypal_master_account != "undefined") && (paypal_master_account != '')) && 
		((typeof paypal_api_username != "undefined") && (paypal_api_username != '')) &&
		((typeof paypal_api_password != "undefined") && (paypal_api_password != '')) && 
		((typeof paypal_api_signature != "undefined") && (paypal_api_signature != '')) && 
		(( sandbox_master_account_valid_email === true ) && ( paypal_master_account_valid_email === true ))) {

		// If there is at least something in each field, then we don't need to anticipate a CodeIgniter form validation returning false, which would reshow the page
		$('#admin_payments_form').submit();

	} else {

		//$('#admin_payments_form').attr('data-transition', 'none');
		//$('#admin_payments_form').removeAttr('data-direction');
		//$('#admin_payments_form').removeAttr('data-rel');

		$('#admin_payments_form').submit();
	}
});*/

$('body').on('click', '#close_station', function(evt, ui) {

	evt.preventDefault();

	// The data-url attribute has to be set on the data-role="page" div on the /playlists page so the 
	// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
	// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
	//change_page('/stations', 'slidedown', false, false, true, null, null, true, false);

	//console.log( $.mobile.urlHistory.stack );

	/*for( var index in $.mobile.urlHistory.stack ) {

	  if ( $.mobile.urlHistory.stack.hasOwnProperty( index ) ) {
	    alert( JSON.stringify( $.mobile.urlHistory.stack.hasOwnProperty[index] ) ) ;
	  }
	}*/

	var howfar = 0;

	for( var index in $.mobile.urlHistory.stack ) {
	    
	    //alert( JSON.stringify( $.mobile.urlHistory.stack[ index ] ) ) ;

	    if ( $.mobile.urlHistory.stack[ index ].url.indexOf('stations#&ui-state=dialog') > 0 ) {

	    	howfar += 1;
	    }
	}

	//alert(howfar);

	// We have to go back one extra because of the dialog
	window.history.go( -1 * ( howfar + 1 ) );

	// Removing previous item from urlHistory stack (tracks page with ui-dialog hash)
	//$.mobile.urlHistory.stack.pop();

	// Decrement urlHistory activeIndex 
	//$.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1; 
});

$('body').on('click', '#close_database', function(evt, ui) {

	evt.preventDefault();

	var howfar = 0;

	for( var index in $.mobile.urlHistory.stack ) {
	    
	    //alert( JSON.stringify( $.mobile.urlHistory.stack[ index ] ) ) ;

	    if ( $.mobile.urlHistory.stack[ index ].url.indexOf('setup/database') > 0 ) {

	    	howfar += 1;
	    }
	}

	//alert(howfar);

	window.history.go( -1 * (howfar) );
});

$('body').on('click', '#close_payments', function(evt, ui) {

	evt.preventDefault();

	var howfar = 0;

	for( var index in $.mobile.urlHistory.stack ) {
	    
	    //alert( JSON.stringify( $.mobile.urlHistory.stack[ index ] ) ) ;

	    if ( $.mobile.urlHistory.stack[ index ].url.indexOf('admin/payments') > 0 ) {

	    	howfar += 1;
	    }
	}

	//alert(howfar);

	window.history.go( -1 * (howfar) );
});

$('body').on('click', '#close_user', function(evt, ui) {

	evt.preventDefault();

	var howfar = 0;

	for( var index in $.mobile.urlHistory.stack ) {
	    
	    //alert( JSON.stringify( $.mobile.urlHistory.stack[ index ] ) ) ;

	    if ( $.mobile.urlHistory.stack[ index ].url.indexOf('edit/user') > 0 ) {

	    	howfar += 1;
	    }
	}

	//alert(howfar);

	window.history.go( -1 * (howfar) );
});

/*$('body').on('click', '#station_save', function(evt, ui) {

	evt.preventDefault();

	var uploaded_icon_id 		= $('#station_icon_id').val();
	var station_id 				= $('#station_id').val();
	var station_url 			= $('#station_url').val();
	var station_name 			= $('#station_name').val();
	var station_description 	= $('#station_description').val();
	var station_visibility 		= $('#station_visibility').val();

	//alert("station_id="+station_id+"\nstation_url="+station_url+"\nuploaded_icon_id="+uploaded_icon_id+"\nstation_name="+station_name+"\nstation_description="+station_description+"\nstation_visibility="+station_visibility);

	if (((typeof station_url != "undefined") && (station_url != '')) && 
		((typeof station_name != "undefined") && (station_name != '')) && 
		((typeof station_description != "undefined") && (station_description != ''))) {

		$.mobile.loadingMessage = 'Saving Station'; 
		$.mobile.loading( "show" );

		$.ajax({

		   type: "POST",
		   url: "/stations/save",
		   async: true,
		   data: "station_id="+station_id+"&station_url="+station_url+"&uploaded_icon_id="+uploaded_icon_id+"&station_name="+station_name+"&station_description="+station_description+"&station_visibility="+station_visibility,
		   
		   success: function(msg){

				$.mobile.loading( "hide" );
				$.mobile.loadingMessage = 'Loading';

		   		// go back to the stations page
				history.go(-1);

				//$('#stations').remove();

				//change_page('/stations', 'slidedown', true, false, true, null, null, true, false);
		   }
		 });

	} else {

		if ((typeof station_url == "undefined") || (station_url == '')) {
			
			$('#station_url').css("border","2px solid #CC0000");
		}

		if ((typeof station_name == "undefined") || (station_name == '')) {
			
			$('#station_name').css("border","2px solid #CC0000");
		}

		if ((typeof station_description == "undefined") || (station_description == '')) {
			
			$('#station_description').css("border","2px solid #CC0000");
		}
	}
});*/


// **********************************************************************************
// Device orientation change event handlers
// **********************************************************************************

//$('body').on('orientationchange', '#index', function(event, ui) {
$( window ).on( 'orientationchange', function( event ) {

	setTimeout(function() {
	
		//alert( "This device is in " + event.orientation + " mode!\n window.width(): "+$(window).width()+"\n index.width(): "+$('#index').width() );
	
		$('#playerCurrentlyPlayingDiv').css('max-width', ($(window).width() * .9));
		//$('#playerCurrentlyPlayingDiv').css('max-width', ($(window).width() * .9)).trigger( 'updatelayout' );
	
		//alert( $('#playerCurrentlyPlayingDiv').css('max-width') );

		$('#playerCurrentlyPlayingDiv').width( $( window ).width() * .9 );

		//alert( $('#playerCurrentlyPlayingDiv').width() );

	}, 400);

});

// **********************************************************************************
// Page swipe event handlers
// **********************************************************************************

/*$('body').on('swipeleft', function(evt, ui) {

	//alert("swipeleft: $.event.special.swipeleft.horizontalDistanceThreshold = "+$.event.special.swipeleft.horizontalDistanceThreshold);
 	
 	// This prevents the double and triple...even quadruple firing of the swipeleft events.  It's also 
	// an attempt to prevent the swipe left event from firing when a user is adjusting the volume or
	// crossfade sliders.
 	if (!just_swiped_left && !waiting_to_adjust_xfade && !waiting_to_adjust_volume) {
	
	 	just_swiped_left = true;

		$('#jukebox #next').trigger('click');

		setTimeout(function(){ just_swiped_left = false; }, 1000);
	}
});

$('body').on('swiperight', function(evt, ui) {

	//alert("swiperight: $.event.special.swiperight.horizontalDistanceThreshold = "+$.event.special.swiperight.horizontalDistanceThreshold);

	// This prevents the double and triple...even quadruple firing of the swiperight events.  It's also 
	// an attempt to prevent the swipe right event from firing when a user is adjusting the volume or
	// crossfade sliders.
 	if (!just_swiped_right && !waiting_to_adjust_xfade && !waiting_to_adjust_volume) {
	
	 	just_swiped_right = true;

		$('#jukebox #prev').trigger('click');

		setTimeout(function(){ just_swiped_right = false; }, 1000);
	}
});*/


// **********************************************************************************
// Page taphold event handlers
// **********************************************************************************

$('html,body').on('taphold', function(evt, ui) {

	//alert("taphold triggered");
});	


$( 'body' ).on( 'click', '#volume_crossfade_form_save', function( evt, ui ) {

	evt.preventDefault();

	var is_admin_user 	= $('#is_admin_user').val();
	
	var new_volume 		= $('#volume_control').val();
	var new_crossfade 	= $('#xfade_control').val();
	var new_volume_fade 	= $('#volume_fade_control').val();

	if (((typeof new_volume == "undefined") || (new_volume == '')) && is_admin_user) {
		
		new_volume = 5;
	} 
	
	if (((typeof new_crossfade == "undefined") || (new_crossfade == ''))) {
		
		new_crossfade = 5;
	}

	if (((typeof new_volume_fade == "undefined") || (new_volume_fade == ''))) {
		
		new_volume_fade = 5;
	}

	$.mobile.loadingMessage = 'Saving Settings';
	$.mobile.loading( "show" );

	$.ajax({
		type: "POST",
		url: "/settings/save/volume",
		async: true,
		data: "volume="+new_volume+"&crossfade="+new_crossfade+"&volume_fade="+new_volume_fade,
		success: function(msg){

			saved = $.parseJSON(msg);

			// hide the loading message
			$.mobile.loading( "hide" );

			$.mobile.loadingMessage = 'Loading';

			// close the settings dialog
			$('#volume_crossfade').dialog('close');
		}	
	}); 	
});

$( 'body' ).on( 'click', '#settingsPopupMenu ul li #volume_crossfade_button', function( evt, ui ) {

	$.mobile.changePage("/settings/volume", { 
		allowSamePageTransition: true,
		transition: "pop",
		reloadPage: false,
		showLoadMsg: false,
		changeHash: true
	}); 
});



$( 'body' ).on( 'click', '#settingsPopupMenu ul div[data-role="collapsible-set"] div[data-role="collapsible"] ul li a', function( evt, ui ) {

	var settingSection = evt.target.getAttribute('data-id');

	var dataItemId = evt.target.getAttribute('data-item-id');
	var dataItemName = evt.target.getAttribute('data-item-name');

	//alert("dataItemId: "+dataItemId+"\ndataItemName: "+dataItemName);

	if ( settingSection == "changeLanguage" ) {

		if ( typeof dataItemId != "undefined" ) {

			$.mobile.loadingMessage = 'Setting Language';
			$.mobile.loading( "show" );

			var currently_selected_theme_id = $( '#currently_selected_theme_id' ).val();

			$.ajax({
			   type: "POST",
			   url: "/settings/save/language",
			   async: true,
			   data: "language_id="+dataItemId+"&theme_id="+currently_selected_theme_id,
			   success: function(msg){
				
				saved = $.parseJSON(msg);

				// Update the list item icon so that the selected one receives the check mark
				$( 'div[data-role="collapsible-set"] ul#availableLanguages li a' ).each( function( index ){
						
					if ( $( this ).attr('data-item-id') == dataItemId ) {
			
						$( this ).closest('li').attr('data-icon', 'checked');
						$( this ).closest('li').find( 'div span.ui-icon' ).attr('class', 'ui-icon ui-icon-check ui-icon-shadow');
								
						//alert( $( this ).attr('data-item-name') );
	
					} else {
							
						$( this ).closest('ul').find( 'li[data-icon="check"]' ).attr('data-icon', 'minus');
						$( this ).closest('li').find( 'div span.ui-icon-check' ).attr('class', 'ui-icon ui-icon-minus ui-icon-shadow');
					}
				});

				// hide the loading message
				$.mobile.loading( "hide" );

				$.mobile.loadingMessage = 'Loading';

				//$( '#settingsPopupMenu' ).popup( 'close' );

				$.mobile.changePage("/settings/apply", { 
					allowSamePageTransition: true,
					transition: "pop",
					reloadPage: false,
					showLoadMsg: false,
					changeHash: true
				} ); 
			    }
			});
		} 

	} else if ( settingSection == "changeTheme" ) {

		if ( dataItemId == "0" ) {

			$.mobile.changePage("/settings/custom/theme", { 
				allowSamePageTransition: true,
				transition: "pop",
				reloadPage: false,
				showLoadMsg: false,
				changeHash: true
			} ); 
		
		} else {

			if ( typeof dataItemId != "undefined" ) {

				$.mobile.loadingMessage = 'Applying Theme';
				$.mobile.loading( "show" );

				var currently_selected_language_id = $( '#currently_selected_language_id' ).val();

				$.ajax({
				   type: "POST",
				   url: "/settings/save/theme",
				   async: true,
				   data: "theme_id="+dataItemId+"&language_id="+currently_selected_language_id,
				   success: function(msg){

					saved = $.parseJSON(msg);

					var current_theme = new Object;

					current_theme.body 	= theme.body;
					current_theme.bars 	= theme.bars;
					current_theme.buttons 	= theme.buttons;
					current_theme.controls 	= theme.controls;
					current_theme.actions 	= theme.actions;
					current_theme.active 	= theme.active;

					theme.body 		= saved.body;
					theme.bars 		= saved.bars;
					theme.buttons 		= saved.buttons;
					theme.controls 		= saved.controls;
					theme.actions 		= saved.actions;
					theme.active 		= saved.active;

					//alert("theme.body: "+theme.body+"\ntheme.bars: "+theme.bars+"\ntheme.buttons: "+theme.buttons+"\ntheme.controls: "+theme.controls+"\ntheme.actions: "+theme.actions+"\ntheme.active: "+theme.active);
					
					$.mobile.loadingMessageTheme = theme.bars;

					$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

					$('.ui-body-' + current_theme.body).each(function(){
						$(this).removeClass('ui-body-' + current_theme.body).addClass('ui-body-' + theme.body);    
					});

					$('.ui-overlay-' + current_theme.body).each(function(){
						$(this).removeClass('ui-overlay-' + current_theme.body).addClass('ui-overlay-' + theme.body);    
					});

					$('.ui-bar-' + current_theme.bars).each(function(){
					        $(this).removeClass('ui-bar-' + current_theme.bars).addClass('ui-bar-' + theme.bars);
					});

					// Update the data-theme attribute and relevant css classes for the children of the ui-select div
					$('div.ui-select').each(function(index){

						$(this).find('div').attr('data-theme', theme.buttons)
									.removeClass('ui-btn-up-' + current_theme.buttons)
									.addClass('ui-btn-up-' + theme.buttons);

						$(this).find('select').attr('data-theme', theme.buttons);
					});

					// Update the data-theme attribute and relevant css classes for the children of the parent of slider input element
					$('input[data-type="range"]').parent().each(function(index){
							
					$(this).find('input').attr('data-theme',theme.buttons)
								.attr('data-track-theme',theme.buttons)
								.removeClass('ui-body-' + current_theme.body)
								.addClass('ui-body-' + theme.body);

					$(this).find('div[role="application"]').removeClass('ui-btn-down-' + current_theme.buttons)
						 				.addClass('ui-btn-down-' + theme.buttons);

					$(this).find('.ui-slider-bg').removeClass('ui-btn-hover-' + current_theme.actions)
						    								.addClass('ui-btn-hover-' + theme.actions);

					$(this).find('a').attr('data-theme', theme.buttons)
						 		.removeClass('ui-btn-up-' + current_theme.buttons)
					    			.addClass('ui-btn-up-' + theme.bars);
					});

					// Update the data-theme attribute and relevant css classes of the parent of the save button
					$('button[type="button"]').parent().attr('data-theme', theme.actions)
										.removeClass('ui-btn-up-', current_theme.actions)
										.addClass('ui-btn-up-', theme.actions);

					// Update the data-theme attribute of the save button
					$('button[type="button"]').attr('data-theme', theme.actions);

					// Update the data-theme attribute and relevant css classes of the prev button element
					$('#prev').attr('data-theme', theme.controls)
					    		.removeClass('ui-btn-up-' + current_theme.buttons)
					    		.removeClass('ui-btn-up-' + current_theme.controls)
					    		.addClass('ui-btn-up-' + theme.controls);    

				        // Update the data-theme attribute and relevant css classes of the playpause button element
				        $('#playpause').attr('data-theme', theme.controls)
				        		.removeClass('ui-btn-up-' + current_theme.buttons)
				        		.removeClass('ui-btn-up-' + current_theme.controls)
				        		.addClass('ui-btn-up-' + theme.controls);        

				        // Update the data-theme attribute and relevant css classes of the next button element
				        $('#next').attr('data-theme', theme.controls)
				        		.removeClass('ui-btn-up-' + current_theme.buttons)
				        		.removeClass('ui-btn-up-' + current_theme.controls)
				        		.addClass('ui-btn-up-' + theme.controls); 

					// Update the data-theme and data-divider-theme attributes for the divs with data-role=page
					$('div[data-role="page"]').each(function(index){
						$(this).attr('data-theme',theme.buttons);
						$(this).attr('data-divider-theme',theme.bars);
					});

					// Update the data-theme and data-divider-theme attributes for the divs with data-role=header
					$('div[data-role="header"]').each(function(index){
						$(this).attr('data-theme',theme.buttons);
						$(this).attr('data-divider-theme',theme.bars);
					});

					// Update the data-theme and data-divider-theme attributes for the divs with data-role=content
					$('div[data-role="content"]').each(function(index){
						$(this).attr('data-theme',theme.buttons)
							.attr('data-divider-theme',theme.bars);
					});

					// Update the data-theme and data-divider-theme attributes for the divs with data-role=footer
					$('div[data-role="footer"]').each(function(index){
						$(this).attr('data-theme',theme.buttons)
							.attr('data-divider-theme',theme.bars);
					});

					// Update the data-theme attribute and the relevant css classes for any data-role=button elements
					$('*[data-role="button"]').each(function(index){

						$(this).attr('data-theme',theme.buttons)
							.removeClass('ui-btn-up-' + current_theme.buttons)
							.addClass('ui-btn-up-' + theme.buttons);
					});

					// Update the data-theme and data-divider-theme attributes for the libraryNav ul
					$('#libraryNav').attr('data-theme', theme.buttons)
					    		.attr('data-divider-theme', theme.buttons);

					// Update the data-theme attribute and relevant css classes for the libraryNav list items
					$( '#libraryNav li' ).not( 'li[data-role="list-divider"]' ).each(function(index){

						$(this).attr('data-theme', theme.buttons)
						    	.removeClass('ui-btn-up-' + current_theme.buttons)
						    	.addClass('ui-btn-up-' + theme.buttons);
					});

					$( '#libraryNav li[data-role="list-divider"]' ).removeClass('ui-btn-up-' + current_theme.buttons)
					    						.removeClass('ui-btn-up-' + current_theme.bars)
					    						.removeClass('ui-bar-' + current_theme.bars)
					    						.addClass('ui-bar-' + theme.bars);

					// Update the data-theme and data-divider-theme attributes for the adminNav ul
					$('#adminNav').attr('data-theme', theme.buttons)
					    		.attr('data-divider-theme', theme.buttons);

					// Update the data-theme attribute and relevant css classes for the adminNav list items
					$( '#adminNav li' ).not( 'li[data-role="list-divider"]' ).each(function(index){

						$(this).attr('data-theme', theme.buttons)
					    		.removeClass('ui-btn-up-' + current_theme.buttons)
					    		.addClass('ui-btn-up-' + theme.buttons);
					});

					$( '#adminNav li[data-role="list-divider"]' ).removeClass('ui-btn-up-' + current_theme.buttons)
					    						.removeClass('ui-btn-up-' + current_theme.bars)
					    						.removeClass('ui-bar-' + current_theme.bars)
					    						.addClass('ui-bar-' + theme.bars);

					// Update the data-theme attribute and relevant css classes for this popup menu's list items
					$( '#settingsPopupMenu ul li' ).not( 'div[data-role="collapsible"] li' ).each( function( index ){

						$( this ).attr('data-theme', theme.buttons )
						    		.removeClass( 'ui-btn-up-' + current_theme.buttons )
						    		.addClass( 'ui-btn-up-' + theme.buttons );
					});

					// Update the data-theme attribute and relevant css classes for this popup menu's list items
					$( 'div[data-role="collapsible"] li' ).each( function( index ){

						$( this ).attr('data-theme', theme.actions )
						    		.removeClass( 'ui-btn-up-' + current_theme.actions )
						    		.addClass( 'ui-btn-up-' + theme.actions );
					});

					// Update the data-theme attribute and relevant css classes for this popup menu's list items
					$( 'div[data-role="collapsible-set"]' ).each( function( index ){

						$( this ).attr('data-theme', theme.actions );
					});

					// Update the data-theme attribute and relevant css classes for this popup menu's list items
					$( 'div[data-role="collapsible-set"] a' ).not( 'div[data-role="collapsible-set"] li a' ).each( function( index ){

						$( this ).attr('data-theme', theme.actions )
						    		.removeClass( 'ui-btn-up-' + current_theme.actions )
						    		.addClass( 'ui-btn-up-' + theme.actions );
					});

					// Update the list item icon so that the selected one receives the check mark
					$( 'div[data-role="collapsible-set"] ul#availableThemes li a' ).each( function( index ){
						
						if ( $( this ).attr('data-item-id') == dataItemId ) {
			
							$( this ).closest('li').attr('data-icon', 'checked');
							$( this ).closest('li').find( 'div span.ui-icon' ).attr('class', 'ui-icon ui-icon-check ui-icon-shadow');
						} else {

							$( this ).closest('ul').find( 'li[data-icon="check"]' ).attr('data-icon', 'minus');
							$( this ).closest('li').find( 'div span.ui-icon-check' ).attr('class', 'ui-icon ui-icon-minus ui-icon-shadow');
						}
					});

					// hide the loading message
					$.mobile.loading( "hide" );

					$.mobile.loadingMessage = 'Loading';
				    }
				});
			} 
		}

	} else {


	}
});

$( 'body' ).on( 'popupafterclose', '#settingsPopupMenu', function( event, ui ) {

	$( '#settings_button' ).removeClass('ui-btn-hover-' + theme.actions)
												.addClass('ui-btn-up-' + theme.buttons);

	$('#settingsPopupMenu .ui-collapsible').each( function( index ) {

		$( this ).trigger( 'collapse' );
	});
});

$( 'body' ).on( "sortstart", "#queueTracksList", function( event, ui ) {

	// Global variable to hold the 0 based index of to position of item being sorted.  Need to subtract one because index() uses a 1 based index
	fromIndexOfItemBeingSorted = ( $( event.target ).children().index( ui.item[0] ) - 1);

	var itemBeingMoved = $( event.target ).children().eq( fromIndexOfItemBeingSorted );
	
	$('#queueTracksList').listview('refresh');
});

$( 'body' ).on( "sortupdate", "#queueTracksList", function( event, ui ) {

	var itemId = ui.item.context.id;

	// Global variable to hold the 0 based index of to position of item being sorted.  Need to subtract one because index() uses a 1 based index
	toIndexOfItemBeingSorted = ( $( event.target ).children().index( ui.item[0] ) - 1);

	//console.log("from_pos: "+fromIndexOfItemBeingSorted);
	//console.log("to_pos: "+toIndexOfItemBeingSorted);

	//console.log( playlist.tracks );

	movedTrack = playlist.tracks[ fromIndexOfItemBeingSorted ];

	var elementBeingMoved = $( '#queueTrack_'+fromIndexOfItemBeingSorted );

	movedTrack.mpd_index = toIndexOfItemBeingSorted.toString();

	//console.log(movedTrack);

	// Remove the track from the locally stored json playlist so we can insert it in it's new position
	delete playlist.tracks[ fromIndexOfItemBeingSorted ];

	// If moving a track down in the queue
	if ( fromIndexOfItemBeingSorted < toIndexOfItemBeingSorted ) {

		//console.log("moving track down in the queue");

		// Iterate throught he items in the playlist.tracks object, decrement mpd_index and return the new object
		playlist.tracks = $.map( playlist.tracks, function( item ) {

			// First make sure the item is defined
			if ( typeof item != 'undefined' ) {

				// We need to decrement the mpd_index of all items in between
				if ( ( parseInt(item.mpd_index) > fromIndexOfItemBeingSorted ) && ( parseInt(item.mpd_index) <= toIndexOfItemBeingSorted ) ) {

					//console.log(parseInt(item.mpd_index) +" > "+ fromIndexOfItemBeingSorted +" && "+parseInt(item.mpd_index) +" <= "+toIndexOfItemBeingSorted);

					var original_index = item.mpd_index;

					var new_index = ( parseInt( item.mpd_index ) - 1 ).toString();

					item.mpd_index = new_index; 

					//alert($( '#queueTrack_'+original_index ).attr( 'id'));

					$( '#queueTrack_'+original_index ).attr( 'data-queue-track-index', new_index )
														.attr( 'id', 'queueTrack_'+new_index );

					//alert($( '#queueTrack_'+new_index ).attr( 'id') );
				}

				return item;
			}

			// returning null will remove any undefined items from the object
			return null;
		});

	} else if ( fromIndexOfItemBeingSorted > toIndexOfItemBeingSorted ) { // moving a track up in the queue

		//console.log("moving track up in the queue");

		for ( i=(playlist.tracks.length - 1); i>=0; i-- ) {

			if ( typeof playlist.tracks[i] != 'undefined' ) {

				// We need to increment the mpd_index of all items in between
				if ( ( parseInt(playlist.tracks[i].mpd_index) >= toIndexOfItemBeingSorted ) && ( parseInt(playlist.tracks[i].mpd_index) < fromIndexOfItemBeingSorted ) ) {

					var original_index = playlist.tracks[i].mpd_index;

					var new_index = ( parseInt( playlist.tracks[i].mpd_index ) + 1 ).toString();

					//alert($( '#queueTrack_'+original_index ).attr( 'id'));

					$( '#queueTrack_'+original_index ).attr('data-queue-track-index', new_index )
														.attr('id', 'queueTrack_'+new_index );

					//alert($( '#queueTrack_'+new_index ).attr( 'data-queue-track-title' ));
				}
			}
		}

		// Iterate throught he items in the playlist.tracks object, decrement mpd_index and return the new object
		playlist.tracks = $.map( playlist.tracks, function( item ) {

			// First make sure the item is defined
			if ( typeof item != 'undefined' ) {

				// We need to increment the mpd_index of all items in between
				if ( ( parseInt(item.mpd_index) >= toIndexOfItemBeingSorted ) && ( parseInt(item.mpd_index) < fromIndexOfItemBeingSorted ) ) {

					console.log(parseInt(item.mpd_index) +" >= "+ toIndexOfItemBeingSorted +" && "+parseInt(item.mpd_index) +" < "+fromIndexOfItemBeingSorted);

					item.mpd_index = ( parseInt( item.mpd_index ) + 1 ).toString();
				}

				return item;
			}

			// returning null will remove any undefined items from the object
			return null;
		});

	} else {

		// I don't think we need to do anything in any other case.
	}

	elementBeingMoved.attr( 'data-queue-track-index', toIndexOfItemBeingSorted )
						.attr( 'id', 'queueTrack_'+toIndexOfItemBeingSorted );

	//alert($( '#queueTrack_'+toIndexOfItemBeingSorted ).attr('data-queue-track-title'));

	playlist.tracks.splice( ( toIndexOfItemBeingSorted ), 0, movedTrack );

	//console.log( playlist.tracks );

	// Get the post parameters ready to send to the control_mpd function
	post = { 'parameters' : [ { 'from_pos' : fromIndexOfItemBeingSorted, 'to_pos' : toIndexOfItemBeingSorted } ] }; 
	
	// Call the track_move function through control_mpd
	control_mpd( 'move_track', post.parameters[0] ); 

	ui.item[0].dataset.queueTrackIndex = toIndexOfItemBeingSorted;

	//playlist = $.parseJSON( get_mpd_playlist() );
});

$( 'body' ).on( "sortstop", "#queueTracksList", function( event, ui ) {

	// Remove the action ui-btn-down class from the list element and put the default btn-up class back
	$( ui.item[0] ).removeClass('ui-btn-hover-' + theme.action)
					.addClass('ui-btn-up-' + theme.buttons);

    $('#queueTracksList').listview('refresh');
});

$('body').on( 'click', '#queue #queueTracksList li', function(evt, ui) {

	var $li = $( evt.target ).closest('li');

	var queueTrackIndex = $li.attr('data-queue-track-index');
	var queueTrackFile = $li.attr('data-queue-track-file');
	var queueTrackLink = $li.attr('data-queue-track-link');
	var liElementId = $li.attr('id');

	idOfQueueTrackJustClicked = 'queueTrack_'+queueTrackIndex;

	var listItem = $( '#'+this.id );

	var queueTrackTrueIndex = ( $('#queueTracksList li').index( listItem ) - 1);

	//console.log("#queueTracksList li click handler - queueTrackIndex: "+queueTrackIndex);
	//console.log("#queueTracksList li click handler - queueTrackTrueIndex: "+queueTrackTrueIndex);
	//console.log("#queueTracksList li click handler - queueTrackFile: "+queueTrackFile);

	options = { "corners": true, "shadow": true, "history": false, "positionTo": this, "transition": "pop" }; 

	$( "#queueTrackPopupMenu" ).popup( "open", options );

	$( '#queueTrackPopupMenu a[data-id="playQueueTrack"]' ).attr( 'data-queue-track-index', queueTrackIndex )
								.attr( 'data-queue-track-true-index', queueTrackTrueIndex )
								.attr( 'data-queue-track-file', queueTrackFile )
								.attr( 'data-queue-track-link', queueTrackLink );

	$( '#queueTrackPopupMenu a[data-id="removeFromQueue"]' ).attr( 'data-queue-track-index', queueTrackIndex )
								.attr( 'data-queue-track-true-index', queueTrackTrueIndex )
								.attr( 'data-source-id', liElementId );
});	

$( 'body' ).on( 'click', '#queueTrackPopupMenu ul li a[data-id="playQueueTrack"]', function( evt ) {

	var queueTrackIndex = evt.target.getAttribute('data-queue-track-index');

	var trackIndex = ( $( evt.target ).children().index( this ) - 1);

	var queueTrackTrueIndex = evt.target.getAttribute('data-queue-track-true-index');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#queueTrack_'+queueTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
										.addClass('ui-btn-up-' + theme.buttons);

	//console.log("In playQueueTrack queueTrackIndex: "+queueTrackIndex);
	//console.log("In playQueueTrack queueTrackTrueIndex: "+queueTrackTrueIndex);

	$( '#queueTrackPopupMenu' ).one( 'popupafterclose', function( event ) {
	    
		//bump_track( queueTrackIndex );
		skipto( queueTrackIndex );

	    	//console.log("inside popupafterclose - bumping track with queueTrackIndex: "+queueTrackIndex);
		// bump the track to top of queue and play it
		//setTimeout(function() { skipto( queueTrackIndex ); }, 250); 

		$("html,body").animate({scrollTop:0}, 1200, function() {
		
			//$.mobile.fixedToolbars.show();

			// The data-url attribute has to be set on the data-role="page" div on the /playlists page so the 
			// location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
			// change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
			change_page('home', $.mobile.defaultPageTransition, false, false, true, null, null, true, false);

			//change_page('/', 'slide', false, false);
			//$( '#header-back-link' ).trigger( 'click' );
		});
	});

	//bump_track( queueTrackIndex );

	$( '#queueTrackPopupMenu' ).popup( 'close' );
});

$( 'body' ).on( 'click', '#queueTrackPopupMenu ul li a[data-id="removeFromQueue"]', function( evt, ui ) {

	var queueTrackIndex = evt.target.getAttribute('data-queue-track-index');

	var trackIndex = ( $( evt.target ).children().index( this ) - 1);

	var queueTrackTrueIndex = evt.target.getAttribute('data-queue-track-true-index');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#queueTrack_'+queueTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
						.addClass('ui-btn-up-' + theme.buttons);

	var queueTrackId = evt.target.getAttribute('data-source-id');

	$( '#queueTrackPopupMenu' ).one( 'popupafterclose', function( event ) {

		// Try to remove the track from the server side playlist first
		post = { "parameters" : [ { 	"index" 	: queueTrackTrueIndex,
						"id" 		: queueTrackId	} ] };
									 
		control_mpd('remove', post.parameters[0]);
	});

	$( '#queueTrackPopupMenu' ).popup( 'close' );
});

$( 'body' ).on( 'popupafterclose', '#queueTrackPopupMenu', function( event, ui ) {

	if ( idOfQueueTrackJustClicked !== "" ) {

		$( '#'+idOfQueueTrackJustClicked ).removeClass('ui-btn-hover-' + theme.action)
							.addClass('ui-btn-up-' + theme.buttons);
	}
});

$( 'body' ).on( "sortstart", "#playlistTracks", function(event, ui) {

	// Global variable to hold the 0 based index of to position of item being sorted.  Need to subtract one because index() uses a 1 based index
	fromIndexOfItemBeingSorted = ( $( event.target ).children().index( ui.item[0] ) - 1);

	var itemBeingMoved = $( event.target ).children().eq( fromIndexOfItemBeingSorted );
	
	$('#playlistTracks').listview('refresh');
});

$( 'body' ).on( "sortupdate", "#playlistTracks", function( event, ui ) {

	var itemId = ui.item.context.id;

	var playlistName = ui.item.context.parentElement.dataset.playlistName;

	// Global variable to hold the 0 based index of to position of item being sorted.  Need to subtract one because index() uses a 1 based index
	toIndexOfItemBeingSorted = ( $( event.target ).children().index( ui.item[0] ) - 1);

	// Get the post parameters ready to send to the control_mpd function
	post = { 'parameters' : [ { 'playlist_name' : playlistName, 'from_pos' : fromIndexOfItemBeingSorted, 'to_pos' : toIndexOfItemBeingSorted } ] }; 
	
	// Call the track_move function through control_mpd
	control_mpd( 'track_move', post.parameters[0] ); 

	ui.item[0].dataset.playlistTrackIndex = toIndexOfItemBeingSorted;
});

$( 'body' ).on( "sortstop", "#playlistTracks", function( event, ui ) {

	// Remove the action ui-btn-down class from the list element and put the default btn-up class back
	$( ui.item[0] ).removeClass('ui-btn-hover-' + theme.action)
					.addClass('ui-btn-up-' + theme.buttons);

    $('#playlistTracks').listview('refresh');
});

$('body').on( 'click', '#playlistTracksPage #playlistTracks li', function(evt, ui) {

	var $li = $( evt.target ).closest('li');

	var playlistTrackIndex = $li.attr('data-playlist-track-index');
	var playlistTrackFile = $li.attr('data-playlist-track-file');
	var liElementId = $li.attr('id');

	idOfPlaylistTrackJustClicked = 'playlistTrack_'+playlistTrackIndex;

	var listItem = $( '#'+this.id );

	var truePlaylistTrackIndex = ( $('#playlistTracks li').index( listItem ) - 1);

	options = { "corners": true, "shadow": true, "history": false, "positionTo": this, "transition": "pop" }; 

	$( "#playlistTrackPopupMenu" ).popup( "open", options );

	$( '#playlistTrackPopupMenu a[data-id="addTrackToQueue"]' ).attr( 'data-playlist-track-index', playlistTrackIndex )
																.attr( 'data-playlist-track-file', playlistTrackFile );

	$( '#playlistTrackPopupMenu a[data-id="removeFromPlaylist"]' ).attr( 'data-playlist-track-index', playlistTrackIndex )
																	.attr( 'data-playlist-track-true-index', truePlaylistTrackIndex )
																	.attr( 'data-source-id', liElementId );
});	

$('body').on( 'click', '#settings_button', function(evt, ui) {

	options = { "corners": true, "shadow": true, "history": false, "positionTo": this, "transition": "slidedown" }; 

	$( "#settingsPopupMenu" ).popup( "open", options );	
});

$( 'body' ).on( 'click', '#playlistTrackPopupMenu ul li a[data-id="addTrackToQueue"]', function( evt ) {

	var playlistTrackIndex = evt.target.getAttribute('data-playlist-track-index');

	var trackIndex = ( $( evt.target ).children().index( this ) - 1);

	var playlistTrackFile = evt.target.getAttribute('data-playlist-track-file');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#playlistTrack_'+playlistTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
												.addClass('ui-btn-up-' + theme.buttons);

	// Get the post parameters ready to send to the control_mpd function
	post = { 'parameters' : [ { 'file' : playlistTrackFile } ] }; 
	
	// Call the add track to queue function with control_mpd
	control_mpd( 'add', post.parameters[0] ); 
	
	$( '#playlistTrackPopupMenu' ).popup( 'close' );
});

$( 'body' ).on( 'click', '#playlistTrackPopupMenu ul li a[data-id="removeFromPlaylist"]', function( evt, ui ) {

	var playlistTrackIndex = evt.target.getAttribute('data-playlist-track-index');

	var trackIndex = ( $( evt.target ).children().index( this ) - 1);

	var playlistTrackTrueIndex = evt.target.getAttribute('data-playlist-track-true-index');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#playlistTrack_'+playlistTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
												.addClass('ui-btn-up-' + theme.buttons);

	var playlistTrackId = evt.target.getAttribute('data-source-id');

	$( '#playlistTrackPopupMenu' ).popup( 'close' );

	$( '#'+playlistTrackId ).slideUp('slow', function() {

		var playlistName = $('#playlistTracks').attr('data-playlist-name');

		// Get the post parameters ready to send to the control_mpd function
		post = { 'parameters' : [ { 'playlist_name' : playlistName, 'pos' : playlistTrackTrueIndex } ] }; 
		
		// Call the track_move function through control_mpd
		control_mpd( 'track_remove', post.parameters[0] ); 

		$( '#'+playlistTrackId ).remove();
	});
});


$( 'body' ).on( 'click', '#albumTracksPage #albumTracks li', function(evt, ui) {

	var $li = $( evt.target ).closest('li');

	var albumTrackIndex = $li.attr('data-album-track-index');
	var albumTrackFile = $li.attr('data-album-track-file');
	var liElementId = $li.attr('id');

	idOfAlbumTrackJustClicked = 'albumTrack_'+albumTrackIndex;

	var options = { "corners": true, "shadow": true, "history": false, "positionTo": this, "transition": "pop" }; 

	$( "#albumTrackPopupMenu" ).popup( "open", options );

	$( '#albumTrackPopupMenu ul li a[data-id="addTrackToQueue"]' ).attr( 'data-album-track-index', albumTrackIndex )
																	.attr( 'data-album-track-file', albumTrackFile );

	$( '#albumTrackPopupMenu ul div #availablePlaylists a[data-id="addTrackToPlaylist"]' ).attr( 'data-album-track-index', albumTrackIndex )
																							.attr( 'data-album-track-file', albumTrackFile )
																							.attr( 'data-source-id', liElementId );
});	

$( 'body' ).on( 'popupafterclose', '#playlistTrackPopupMenu', function( event, ui ) {

	if ( idOfPlaylistTrackJustClicked !== "" ) {

		$( '#'+idOfPlaylistTrackJustClicked ).removeClass('ui-btn-hover-' + theme.action)
												.addClass('ui-btn-up-' + theme.buttons);
	}
});

$( 'body' ).on( 'popupafterclose', '#albumTrackPopupMenu', function( event, ui ) {

	if ( idOfAlbumTrackJustClicked !== "" ) {

		$( '#'+idOfAlbumTrackJustClicked ).removeClass('ui-btn-hover-' + theme.action)
											.addClass('ui-btn-up-' + theme.buttons);
	}
});

/*$( 'body' ).on( 'expand collapse', '[data-role="collapsible"]', function( evt ) {

	evt.preventDefault();

	$( this ).find('div').attr('aria-hidden', false);

    $( this ).find('div').slideToggle(500);
});*/

$( 'body' ).on( 'click', '#albumTrackPopupMenu ul li a[data-id="addTrackToQueue"]', function( evt ) {

	var albumTrackIndex = evt.target.getAttribute('data-album-track-index');

	var albumTrackFile = evt.target.getAttribute('data-album-track-file');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#albumTrack_'+albumTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
										.addClass('ui-btn-up-' + theme.buttons);

	// Get the post parameters ready to send to the control_mpd function
	post = { 'parameters' : [ { 'file' : albumTrackFile } ] }; 
	
	// Call the add track to queue function with control_mpd
	control_mpd( 'add', post.parameters[0] ); 
	
	$( '#albumTrackPopupMenu' ).popup( 'close' );
});

$( 'body' ).on( 'click', '#albumTrackPopupMenu ul div #availablePlaylists a[data-id="addTrackToPlaylist"]', function( evt, ui ) {

	var $li = $( evt.target ).closest('li');

	var playlistName = $li.attr('data-playlist-name');

	var albumTrackIndex = evt.target.getAttribute('data-album-track-index');

	var albumTrackFile = evt.target.getAttribute('data-album-track-file');

	// Remove the action ui-btn-hover class from the list element and add the default btn-up class back
	$( '#albumTrack_'+albumTrackIndex ).removeClass('ui-btn-hover-' + theme.action)
										.addClass('ui-btn-up-' + theme.buttons);

	// Get the post parameters ready to send to the control_mpd function
	post = { 'parameters' : [ { 'playlist_name' : playlistName, 'file' : albumTrackFile } ] }; 
	
	// Call the track_move function through control_mpd
	control_mpd( 'track_add', post.parameters[0] );

	$( '#albumTrackPopupMenu' ).popup( 'close' );
});


var mouseProto = $.ui.mouse.prototype,
  _mouseInit = mouseProto._mouseInit,
  touchHandled;

/**
* Simulate a mouse event based on a corresponding touch event
* @param {Object} event A touch event
* @param {String} simulatedType The corresponding mouse event
*/
function simulateMouseEvent (event, simulatedType) {

	var screenX, screenY, clientX, clientY;

	// touch event
	if ( event.originalEvent.type.match(/^touch/) ) {

		// For mobile devices (touchstart will be the original event)
		screenX = event.touches[0].screenX;
		screenY = event.touches[0].screenY;
		clientX = event.touches[0].clientX;
		clientY = event.touches[0].clientY;

	} else if ( event.originalEvent.type.match(/^(mouse|click)/ ) ) {  // mouse event

		// For desktop browsers (mousedown will be the original event)
		screenX = event.originalEvent.screenX;                  
		screenY = event.originalEvent.screenY;                     
		clientX = event.originalEvent.clientX;   
		clientY = event.originalEvent.clientY; 

	} else {

		screenX = 0;             
		screenY = 0;                     
		clientX = 0;   
		clientY = 0; 
	}
	
	//alert( "screenX: "+screenX+"\nscreenY: "+screenY+"\nclientX: "+clientX+"\nclientY: "+clientY );

	var simulatedEvent = document.createEvent('MouseEvents');

	// Initialize the simulated mouse event using the touch event's coordinates
	simulatedEvent.initMouseEvent(
	  simulatedType,    // type
	  true,             // bubbles                    
	  true,             // cancelable                 
	  window,           // view                       
	  1,                // detail                     
	  screenX,    		// screenX                    
	  screenY,    		// screenY                    
	  clientX,    		// clientX                    
	  clientY,    		// clientY                    
	  false,            // ctrlKey                    
	  false,            // altKey                     
	  false,            // shiftKey                   
	  false,            // metaKey                    
	  0,                // button                     
	  null              // relatedTarget              
	);

	// Dispatch the simulated event to the target element
	event.target.dispatchEvent( simulatedEvent );
}


$('body').on( 'taphold', '#playlistTracksPage #playlistTracks li a.move .ui-btn-inner', function(evt, ui) {

	//evt.preventDefault();

	// Generate a half of a second worth of haptic feedback
	if ( typeof navigator.notification != 'undefined' ) {
		navigator.notification.vibrate(50);
	}

	//$( "#playlistTracks" ).sortable( "option", "disabled", false );

	var $li = $( evt.target ).closest('li');

	// Remove the default ui-btn-down class from the list element
	//$( liElement ).removeClass('ui-btn-down-' + theme.buttons);

    // Update the style of the list element so that it is visually noticeable that something is happening
    //$( liElement ).addClass('ui-btn-up-' + theme.action);

    // Simulate the mouseover event
    //simulateMouseEvent(evt, 'mouseover');

    // Simulate the mousemove event
    //simulateMouseEvent(evt, 'mousemove');

    // Simulate the mousedown event
    //simulateMouseEvent(evt, 'mousedown');

    //return false;
});

$('body').on('focusin', '#artists input[data-type="search"], #albums input[data-type="search"]', function(evt, ui) {

	$.mobile.loadingMessage = "Retrieving all";

	$.mobile.loading( "show" );

	// Set the retrieve option to all so it pulls the rest of the items to lazy load
	$( "#index" ).lazyloader( "option", "retrieve", "all" )
					.lazyloader( "refresh", "parameter", "retrieve" )
					.lazyloader( "loadMore", 0 );
});
