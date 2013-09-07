<?php  

$config = array();

// defaults for debugging and profiling
$config['debug'] 				    = false;
$config['profiling']				    = false;

$config['default_path_to_mpd_binary']               = "/usr/local/bin/mpd";

// default master admin user details
$config['default_master_admin_first_name']          = "MPDTunes";
$config['default_master_admin_last_name']           = "Administrator";
$config['default_master_admin_email']               = "admin@mpdtunes.com";
$config['default_master_admin_password']            = "o_tRy5dAi_wh";
$config['default_master_admin_role']                = 1;
$config['default_master_admin_active']              = 1;

// The default_encryption_key  will be the same as the encryption key set in app/config/app.php when things are in sync
// To change the app/config/app.php without breaking the site, you MUST FOLLOW THESE STEPS IN ORDER
// 1.) Enter a new encryption key for default_encryption_key below
// 2.) Load the site and log in
// 3.) The users specific configs that are encrypted will be opened with the original encryption key and then re-encrypted and re-saved with the new one 
// 4.) Update the encryption key in app/config/app.php to match the new one entered below
// Go ahead and try it with this one: %#QIp$bZzfc3Tz$Ydq1Y9uyN^%_FoJ23
//$config['default_encryption_key']		    = "%#QIp$bZzfc3Tz$Ydq1Y9uyN^%_FoJ23";
$config['default_encryption_key']		    = "mSvgWo%UGKDp@q#EIgY5_t3ki56wBsA!";

// whether or not to anonymize the master admin's directories at setup
// if false, then there will be a directory named "master" in each user directory
$config['default_anonymous_master_directories']     = false;

// default master admin settings
$config['default_master_mpd_server_ip']             = "127.0.0.1";
$config['default_master_mpd_port']                  = "6600";
$config['default_master_mpd_http_streaming_port']   = "6601";

// default general mpd settings used for allocating mpd instances for each new user
$config['default_mpd_server_ip']                    = "127.0.0.1";
$config['default_starting_mpd_port']                = "16600";
$config['default_starting_mpd_stream_port']         = "16601";

// This depends on the environment and is essential for the remote control feature to work
// Values can be "pulse" or "alsa". Setting it to none by default because the mpd instance won't 
// start if there is neither a pulse or an alsa output type installed on the system. 
$config['audio_local_output_type']                  = "none";

$config['audio_buffer_size']                        = "4096";
$config['buffer_before_play']                       = "10%";
$config['connection_timeout']                       = "30";
$config['max_connections']                          = "100";
$config['max_playlist_length']                      = "65535";
$config['max_command_list_size']                    = "2048";
$config['max_output_buffer_size']                   = "8192";

$config['default_user_station_visibility']          = "public";

// default base mpd directory - NO beginning slash 
$config['default_base_mpd_dir']                     = "mpd/";

// default base music directory - with beginning slash
$config['default_base_music_dir']                   = "/nfs/music/";

// default base uploads directory - with beginning slash - NOTE: this should be outside of a browser's reach
$config['default_base_uploads_dir']                 = "/nfs/music/uploads/";

// default base directory for queues - NO beginning slash
$config['default_base_queue_dir']                   = "queues/";

// default base cache art directory for storing 64x64 versions of album art - NO beginning slash
$config['default_base_cache_art_dir']               = "cache/art/";

// default icon to use when an album has no album art in the id3 tag - with beginning slash
$config['default_no_album_art_image']               = "/images/default_no_album_art.jpg";

/* the default icon_url_path is set at the database level (i.e. the icon_id field defaults to 1, 
 * which has an icon_url (i.e. base_url) of mpd/master/ and value 'default_no_station_icon.jpg' as icon
 * so, this default_no_station_icon configuration is just a place-holder for this comment really
 */
$config['default_no_station_icon']                  = "/images/default_no_station_icon.jpg";

// default user settings
$config['default_user_role']                        = 3;
$config['default_mode']                             = 'streaming';
$config['default_crossfade']                        = 0;
$config['default_volume_fade']                      = 0;

$config['default_demo_user_id']                     = 3;

// default theme settings
$config['default_theme_id']                         = 1;
$config['default_theme_bars']                       = 'a';
$config['default_theme_buttons']                    = 'a';
$config['default_theme_body']                       = 'a';
$config['default_theme_controls']                   = 'a';
$config['default_theme_action']                     = 'a';
$config['default_theme_active']                     = 'a';
$config['default_theme_alert']                      = 'r';

// Set to nothing to use the white icon set or ui-icon-alt to use the black icon set
$config['default_theme_icon_class']		    = 'ui-icon-alt';

$config['show_album_count_bubbles']                 = true;
$config['show_album_track_count_bubbles']           = true;
$config['show_playlist_track_count_bubbles']        = true;
$config['show_albums_total_length']		    = true;
$config['show_album_tracks_length']                 = true;
$config['show_playlist_tracks_length']              = true;
$config['show_playlists_total_length']              = true;

// this is used in the views as the default transition from page to page
$config['default_page_transition']                  = 'slide';

// this is used in the views as the default transition to open a dialog view
$config['default_dialog_transition']                = 'slidedown';

// this is used in the views as the default transition to open a dialog view
$config['default_alert_transition']                 = 'pop';

// this is used in the views as the default transition back to home
$config['default_home_transition']                  = 'flow';

$config['default_num_genres_to_display']            = 40;
$config['default_num_artists_to_display']           = 20;
$config['default_num_albums_to_display']            = 20;
$config['default_num_tracks_to_display']            = 50;
$config['default_num_queue_tracks_to_display']      = 20;
$config['default_num_playlists_to_display']         = 20;
$config['default_num_stations_to_display']          = 20;

return $config;

?>
