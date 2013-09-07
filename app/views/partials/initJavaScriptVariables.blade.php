<script type="text/javascript">

<?php if( $debug ) : ?>

	// This will enable us to only show console.log output when debug is enabled
	var debug = true;

<?php else : ?>

	var debug = false;

<?php endif; ?>

<?php if( $profiling ) : ?>

	// If debug is enabled then we need to turn off the ajax loading so the profiling can work 
	// for each controller and not just the home/index controller
	$.mobile.ajaxEnabled = false;

<?php endif; ?>

var defaultPageTransition = "slide";
var defaultDialogTransition = "pop";

// This is the global theme object to be used in the javascript of the application
var theme = new Object;

theme.body 	= '<?php echo $theme_body; 	?>';
theme.bars 	= '<?php echo $theme_bars; 	?>';
theme.buttons 	= '<?php echo $theme_buttons; 	?>';
theme.controls 	= '<?php echo $theme_controls; 	?>';
theme.action 	= '<?php echo $theme_action; 	?>';
theme.active 	= '<?php echo $theme_active; 	?>';

// set the default theme of the loading message to be the same as the bars
$.mobile.loadingMessageTheme = theme.bars;

// set the style to use for the active button state (button hover will give the desired effect
$.mobile.activeBtnClass = 'ui-btn-hover-'+theme.active;

// The playlist was generated server side as a JSON style string which will 
// automatically be a JSON object when evaluated by JavaScript (so no need to parse)
var playlist = <?php echo(($json_playlist == "") ? '""' : $json_playlist); ?>;

// The current track position on the MPD side of things.  This will keep the client somewhat in sync.
var current_track_position = <?php echo(($current_track_position > 0) ? $current_track_position : 0); ?>;

var current_track;

// This is the index into the playlist currently being played on the client side
var current_aud_pos;

// This context will be the parent object of further audio objects to come
//var context = new AudioContext();

// Any more than this horizontal displacement, and we will suppress scrolling
$.event.special.swipe.durationThreshold = 100;

// These control how big the swipe has to be in order for it to fire the events.  300 may be too much
$.event.special.swipe.horizontalDistanceThreshold = 250;

// This controls the vertical threshold of a swipe
$.event.special.swipe.verticalDistanceThreshold = 50;

// Any more time than this, and it isn’t a swipe
$.event.special.swipe.durationThreshold = 2000;

// These two variables are used to prevent double and triple...even quadruple firing of the swipeleft events.  they are checked and set in the swipeleft and swiperight event handlers
var just_swiped_left = false;
var just_swiped_right = false;

// This is reserved for future use
var mode = "streaming";

<?php if( isset( $mode )) : ?>
	
	mode = '<?php echo $mode; ?>';

<?php endif; ?>

// These two variables are used with the delayed calls to MPD so that the stepping works as it should
var waiting_to_adjust_volume = false;

// This variable is used with the delayed calls to MPD so that the xfade stepping works as it should
var waiting_to_adjust_xfade = false;

// This variable is used so that the volume fade stepping works as it should
var waiting_to_adjust_volume_fade = false;

// This is the currently set max crossfade value from the users_preferences table
var max_crossfade = <?php echo ($crossfade / 10); ?>;

// This the currently set volume fade value from the users_preferences table
var volume_fade = <?php echo ($volume_fade / 10); ?>;

// These two variables are used for simulating a fade in and fade out during the volume fade sequence
var out_volume_left_to_fade = volume_fade - 1;
var in_volume_left_to_fade = volume_fade - 1;

// This will help to control who gets to update the player progress bar
var crossfade_is_in_progress = false;

// This variable is used along with the simulation of the two channels being mixed together
var next_track_already_added = false;

// This variable is used by the progress and timeupdate events to determine whether or not the progress has reached 100 yet
var load_progress_complete = false;

// get a handle on the album art div element so we can update it when necessary
var art = $('#jukebox .albumart').get(0);

// ?
var trackTotalDuration = document.getElementById("trackTotalDuration");

// This variable is used so that we only update the track duration once for any given song
var track_duration_is_current = false;

// ?
var trackPlayDuration = document.getElementById("trackPlayDuration");

// ?
var playerCurrently = document.getElementById("playerCurrentlyPlayingDiv");

// We need to get a handle on the play/pause button
var play = $('#jukebox #playpause img').get(0);

// We need to get a handle on the repeat button so we know whether or not we should repeat
var repeat = $('#repeat').get(0);

var activePlayerSelector = "#playerOne";
var inactivePlayerSelector = "#playerTwo";

var primary_player = 1;
var primary_player_id = "playerOne";
var secondary_player_id = "playerTwo";	
var primary_audio_id = "jp_audio_0";
var secondary_audio_id = "jp_audio_1";
var track_position = 0;

<?php if (isset($current_track_playlist_index) && ($current_track_playlist_index != '')) : ?>

	// This sets the track position playlist index to be in sync with MPD
	track_position = <?php echo $current_track_playlist_index; ?>;

<?php endif; ?>

// These  variables are just being used while experimenting with load progress stuff
var loadProgressErrorOccurred = false;
var loadProgressPreviousValue = 0;
var loadProgressIncrement = 0;

// This variable is for keeping track of whether or not the player is playing
var playing = false;

// This variable is for keeping track of whether or not the player is paused
var paused = true;

// This is for keeping track of whether or not the track should be repeated
var repeat_track = false;

// This is for keeping track of whether or not the next song in the playlist should be random
var shuffle_queue = false;

// This is for future use
var repeat_album = false;

// This is for future use
var paused_position = 0;

// These two variables are used to try to keep things in sync when removing items from the queue 
// (and hence, the aud element's playlist)
var playlist_index_offset = 0;
var removed_from_queue = Array();

var fromIndexOfItemBeingSorted = -1;
var toIndexOfItemBeingSorted = -1;
var idOfPlaylistTrackJustClicked = "";
var idOfAlbumTrackJustClicked = "";
var idOfQueueTrackJustClicked = "";

var window_scroll_event_just_fired 	= false;
var mousewheel_event_just_fired 	= false;

// These are used to determine when the lazy loading should kick in (threshold from the bottom)
var default_genres_bottom_threshold		= 480;
var default_artists_bottom_threshold		= 640;
var default_albums_bottom_threshold		= 480;
var default_tracks_bottom_threshold		= 640;
var default_queue_tracks_bottom_threshold	= 640;
var default_playlists_bottom_threshold		= 480;
var default_stations_bottom_threshold		= 480;

// These are to control the default number of starting list items for each type of items
var default_genres_listed_so_far 	= 20;
var default_artists_listed_so_far 	= 20;
var default_albums_listed_so_far 	= 20;
var default_tracks_listed_so_far 	= 50;
var default_queue_tracks_listed_so_far 	= 20;
var default_playlists_listed_so_far 	= 20;
var default_stations_listed_so_far 	= 20;

// These are to track the number of list items have been displayed so far for each type of items
var genres_listed_so_far 		= default_genres_listed_so_far;
var artists_listed_so_far 		= default_artists_listed_so_far;
var albums_listed_so_far 		= default_albums_listed_so_far;
var tracks_listed_so_far 		= default_tracks_listed_so_far;
var queue_tracks_listed_so_far 		= default_queue_tracks_listed_so_far;
var playlists_listed_so_far 		= default_playlists_listed_so_far;
var stations_listed_so_far 		= default_stations_listed_so_far;

// These are to control the default number of list items to pull each time we need display more
var genres_to_retrieve 			= 20;
var artists_to_retrieve 		= 20;
var albums_to_retrieve 			= 20;
var tracks_to_retrieve 			= 50;
var queue_tracks_to_retrieve 		= 20;
var playlists_to_retrieve 		= 20;
var stations_to_retrieve 		= 20;

// These control whether or not there is already a thread active to pull new items
var in_process_of_getting_more_genres 		= false;
var in_process_of_getting_more_artists 		= false;
var in_process_of_getting_more_albums 		= false;
var in_process_of_getting_more_tracks 		= false;
var in_process_of_getting_more_queue_tracks 	= false;
var in_process_of_getting_more_playlists 	= false;
var in_process_of_getting_more_stations 	= false;

// For demonstrations of the different easing equations, check the developer's site: http://gsgd.co.uk/sandbox/jquery/easing/
var default_easin_equation	= "easeInExpo";
var default_easout_equation	= "easeOutExpo";
var default_easin_duration 	= 1000;
var default_easout_duration 	= 1000;

// For truncation of title and album names on the Home and Queue pages
var max_track_title_string_length 	= 32;
var max_album_name_string_length 	= 32;

var recaptcha_public_key = '<?php echo $recaptcha_public_key; ?>';
 
</script>
