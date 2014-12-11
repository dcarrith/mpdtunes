<?php

define('NO_ALBUM_ART_MD5', '74ec2ed1b5856df36c21263a7ab47f3d');
define('NO_ALBUM_ART_MD52', 'd89199131765f24bafae77ab8685f58a');

use Dcarrith\LxMPD\MPDConnection as MPDConnection;

class MPDTunesController extends BaseController {

    	function __construct() {

		parent::__construct();

		$this->data['demo_user_id'] = 3;

		// If not logged in, then redirect to login
                /*if (!Auth::check()) {
                        return Redirect::to('login');
                        exit();
                }*/

		// Auth::user() is already called once in the before auth filter, so we can call it again
		// here without causing another query to the database
		$this->user = Auth::user();

		$this->data['station'] = $this->user->station;

		$this->firephp->log($this->data['station']->toArray(), "this->user->station->toArray()");

		//Cache::flush();


		if (!Cache::has('role'.Session::getId())) {

			$this->role = Cache::rememberForever('role'.Session::getId(), function() {

				return $this->user->role;
			});

		} else {

			$this->role = Cache::get('role'.Session::getId());
		}

		// Check to see if role level is 99 and if so, then we know this is a master admin
		$this->data['admin_user'] = (($this->role->level == 99) ? true : false);

		$this->data['logged_in'] = true;
		$this->data['user_id'] = $this->user->id;

		$this->firephp->log($this->data['user_id'], "this data user_id");

                // Get and merge all the config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults('all', $this->firephp));

		//$this->firephp->log($this->data, 'this->data');

		// Get the UserPreferences for the authenticated user

		if (!Cache::has('preferences'.Session::getId())) {

			$this->preferences = Cache::rememberForever('preferences'.Session::getId(), function() {

				return $this->user->preferences;
			});

		} else {

			$this->preferences = Cache::get('preferences'.Session::getId());
		}

		if ($this->preferences) {

			if (!Cache::has('theme'.Session::getId())) {

				$this->theme = Cache::rememberForever('theme'.Session::getId(), function() {

					return $this->preferences->theme;
				});

			} else {

				$this->theme = Cache::get('theme'.Session::getId());
			}

			$this->data['theme_id'] = $this->theme->id;

			$this->data['currrent_theme_id'] = $this->theme->id;

			// Header and footer bars, navigation control bars, and main player controls section
			$this->data['theme_bars'] = $this->theme->bars;

			// Buttons and any links that are used to navigate throughout the application
			$this->data['theme_buttons'] = $this->theme->buttons;

			// Main background for the different pages
			$this->data['theme_body'] = $this->theme->body;

			// Play, pause, next and previous controls
			$this->data['theme_controls'] = $this->theme->controls;

			// Buttons that should stand out (matches the below ui-btn-active theme by default...but it doesn't have to)
			$this->data['theme_action'] = $this->theme->actions;

			// See below for an explanation of this setting
			$this->data['theme_active'] = $this->theme->active;

			// default mode is streaming
			$this->data['mode'] = $this->preferences->mode;

			// default crossfade value is 5
			$this->data['crossfade'] = $this->preferences->crossfade;

			// default volume fade is also 5
			$this->data['volume_fade'] = $this->preferences->volume_fade;

			// user's preferred language
			if (!Cache::has('language'.Session::getId())) {

				$this->language = Cache::rememberForever('language'.Session::getId(), function() {

					return $this->preferences->language;
				});

			} else {

				$this->language = Cache::get('language'.Session::getId());
			}

			$this->data['language_code'] = $this->language->code;

			// Set the language for the application
			App::setLocale( $this->language->code );

        	        // Get and merge all the words we need for the base controller into the main data array
        	        $this->data = array_merge($this->data, Langurator::getLocalizedWords("base"));
		}

		// default current_volume_fade to whatever was the default or in the user preferences
		$this->data['current_volume_fade'] = $this->data['volume_fade'];

		/* It doesn't look like the MPD object can be cached

		// First check to see if there is already an MPD object in cache
		if (!Cache::has('xMPD')) {

			// Get the variables we need to pass into the closure
			$mpd_host = $this->data['mpd_host'];
			$mpd_port = $this->data['mpd_port'];
			$mpd_password = $this->data['mpd_password'];

			$this->xMPD = Cache::rememberForever('xMPD', function() use ($mpd_host, $mpd_port, $mpd_password) {

				require_once($this->data['document_root'].'includes/php/classes/xMPD.php');

        		        // Instantiate the MPD object to be used by the derived controllers
	        	        return new xMPD( $mpd_host, $mpd_port, $mpd_password );
			});

			//var_dump($this->xMPD);
			//exit();

		} else {

			//Cache::forget('mpd');

			// Retrieve the MPD object from cache
			$this->xMPD = Cache::get('xMPD');

			//var_dump($this->xMPD);
			//exit();
		}
 		*/

		include "includes/php/library/mpd.inc.php";

		Config::set("lxmpd::host", $this->data['mpd_host']);
		Config::set("lxmpd::port", $this->data['mpd_port']);
		Config::set("lxmpd::password", $this->data['mpd_password']);

		//$connection = new MPDConnection( Config::get("lxmpd::host"), Config::get("lxmpd::port"), Config::get("lxmpd::password")	);
		//$this->xMPD = new LxMPD( $connection );

		Log::info( 'MPDTunesController', array( 'host' => Config::get('lxmpd::host')));
		Log::info( 'MPDTunesController', array( 'port' => Config::get('lxmpd::port')));
		Log::info( 'MPDTunesController', array( 'password' => Config::get('lxmpd::password')));

		// Resolve the LxMPD object out of the IoC container
		$this->xMPD = App::make('lxmpd');

		// Authenticate to MPD
		$this->xMPD->authenticate();

		// Refresh the xMPD properties with status and statistics from MPD
		$this->xMPD->refreshInfo();

		$this->firephp->log($this->xMPD, "xMPD");

		// Send all the statistics we have for MPD to the FirePHP console
		$this->firephp->log($this->xMPD->repeat, "repeat");
		$this->firephp->log($this->xMPD->random, "random");
		$this->firephp->log($this->xMPD->single, "single");
		$this->firephp->log($this->xMPD->consume, "consume");
		$this->firephp->log($this->xMPD->volume, "volume");
		$this->firephp->log($this->xMPD->playlist_id, "playlist_id");
		$this->firephp->log($this->xMPD->playlist_length, "playlist_length");
		$this->firephp->log($this->xMPD->song , "song");
		$this->firephp->log($this->xMPD->songid, "songid");
		$this->firephp->log($this->xMPD->nextsong, "nextsong");
		$this->firephp->log($this->xMPD->nextsongid, "nextsongid");
		$this->firephp->log($this->xMPD->time, "time");
		$this->firephp->log($this->xMPD->elapsed, "elapsed");
		$this->firephp->log($this->xMPD->bitrate , "bitrate");
		$this->firephp->log($this->xMPD->xfade, "xfade");
		$this->firephp->log($this->xMPD->mixrampdb, "mixrampdb");
		$this->firephp->log($this->xMPD->mixrampdelay, "mixrampdelay");
		$this->firephp->log($this->xMPD->audio, "audio");

		// Default repeat to off
		$this->data['repeat'] = 0;

		// Default shuffle to off
		$this->data['shuffle'] = 0;

		if ($this->xMPD->isConnected()) {

			// This is so we can determine whether or not to hightlight the repeat button as active or not
			$this->data['repeat'] = $this->xMPD->repeat;

			// This is so we can determine whether or not to hightlight the shuffle button as active or not
			$this->data['shuffle'] = $this->xMPD->random;
		}

		//$home_link = $this->data['base_protocol'] . $this->data['base_domain'] . "/home";
		$home_link = "/home";
		$this->firephp->log($home_link, "home_link");
		$this->data['home_link_data_ajax']	= "true";
		$this->data['home_link']		= $home_link;
	}

	public function confirmDelete() {

		$this->data['item_type'] = Request::get('item_type');
		$this->data['item_name'] = Request::get('item_name');
		$this->data['item_id'] = Request::get('item_id');

		// replace the %item% and %item_value% placeholders with the items to be confirmed for deletion
		$this->data['are_you_sure_i18n'] = str_replace("%item_type%", $this->data['item_type'], $this->data['are_you_sure_i18n']);
		$this->data['are_you_sure_i18n'] = str_replace("%item_name%", $this->data['item_name'], $this->data['are_you_sure_i18n']);
		$this->data['note_gone_forever_i18n'] = str_replace("%item_type%", $this->data['item_type'], $this->data['note_gone_forever_i18n']);

		$this->firephp->log($this->data, "data");

		return View::make('confirmDelete', $this->data);
	}

	public function getPlaylistTracks($playlistName, $tracksListedSoFar = 0, $tracksToRetrieve = 0, $context = "sync") {

		$this->firephp->log($playlistName, "playlist_name");
		$this->firephp->log($tracksListedSoFar, "tracksListedSoFar");
		$this->firephp->log($tracksToRetrieve, "tracksToRetrieve");

		// Variable to determine if we need to retrieve the rest of the tracks or not (in case of search filtering)
		$retrievingTheRest = false;
		if ($tracksToRetrieve == "all") {
			$retrievingTheRest = true;
		}

		$encodedPlaylistName = urlencode($playlistName);

		$this->data['playlist_name'] 		= $playlistName;
		$this->data['encoded_playlist_name'] 	= $encodedPlaylistName;
		$this->data['heading_name'] 		= $playlistName;

		$playlistTracks = array();

		// The playlist is current on the queue page
		if ($playlistName == "current") {

			$playlistTracks = $this->xMPD->playlist;

		} else {

			$playlistTracks = $this->xMPD->listplaylistinfo( $playlistName );
		}

		$tracksCount = count($playlistTracks);

		$this->firephp->log($tracksCount, "tracksCount");

		// Default this to zero in case this is a sync operation
		$defaultNumTracksToDisplay = 0;

		// We want to make sure we use the passed in values for a sync operation
		if ($context != "sync") {

			$defaultNumTracksToDisplay = $this->data['default_num_'.$context.'_tracks_to_display'];

			$tracksListedSoFarSession = Session::get($context.'_tracks_listed_so_far');

			$this->firephp->log($tracksListedSoFarSession, "tracksListedSoFarSession");

			if ($tracksListedSoFarSession > $defaultNumTracksToDisplay) {

				$defaultNumTracksToDisplay = $tracksListedSoFarSession;
			}

			$this->firephp->log($defaultNumTracksToDisplay, "defaultNumTracksToDisplay");

			//$this->firephp->log($playlistTracks, "playlistTracks");

			// Determine the length of the slice we need
			$sliceLength = ((!$retrievingTheRest) ? $tracksToRetrieve : $tracksCount - $defaultNumTracksToDisplay);

			$this->firephp->log($sliceLength, "sliceLength");

			$playlistTracks = array_slice( $playlistTracks, $tracksListedSoFar, $sliceLength );

			//$this->firephp->log($playlistTracks, "playlistTracks after the slice");
		}

		// There are certain elements that every track should have - otherwise, we'll filter them out
		$essentialTags = $this->xMPD->getEssentialTags();

		// We don't seem to need the Id and Pos for anything but the current playlist
		unset( $essentialTags[ array_search( "Id", $essentialTags ) ]);
		unset( $essentialTags[ array_search( "Pos", $essentialTags ) ]);

		// Filter out any bum track records
		$playlistTracks = array_filter($playlistTracks, function($track) use ($essentialTags) {

			return count( array_intersect( array_keys( $track ), $essentialTags )) == count( $essentialTags );
		});

		// Iterate over the entire array so we can add the supplemental track info to each item
		$playlistTracks = array_map( function($track, $index) use ($context, $defaultNumTracksToDisplay) {

			return $this->addSupplementaryTrackInfo($track, $index, $context, $defaultNumTracksToDisplay);

		}, $playlistTracks, array_keys($playlistTracks));

		$playlistTracks['count'] = (( $context == "sync" ) ? $tracksCount : $sliceLength );

		return $playlistTracks;
	}

	public function addSupplementaryTrackInfo($track, $index = null, $context = null, $displayed = null) {

		//$this->firephp->log($track, "track");

		if (strpos($track['file'], "http://") === 0) {

			$station = DB::table('stations')->where('url_hash', hash('sha512', $track['file']))->where('creator_id', Auth::user()->id)->first();

			$track['Title'] = $station->name;

			$stationsIcon = StationsIcon::find($station->icon_id);

			$track['Art'] = URL::to( $stationsIcon->baseurl . $stationsIcon->filename );

			$track['length'] = get_timer_display(0);

		} else {

			$track['Art'] = Request::root()."/".$this->getAlbumArt(	$track['file'], $track['Artist'], $track['Album'] );

			$track['length'] = get_timer_display($track['Time']);
		}

		// For now, there are only two contexts where we need to add info for the client side templates to use
		if (( $context == "queue" ) || ($context == "playlist" )) {

			// This is supplemental data for the client side template to use when building the HTML
			$track['id'] = $context.'Track_'.($index + $displayed);
			$track['index'] = ($index + $displayed);
			$track['href'] = '#'.$context.'TrackPopupMenu';
			$track['file'] = str_replace('"', '\\"', $track['file']);
			$track['theme_buttons'] = $this->data['theme_buttons'];
			$track['theme_icon_class'] = $this->data['theme_icon_class'];
			$track['anchorTitle'] = $this->data['taphold_then_drag_to_reorder_i18n'];
			$track['mpd_index'] = (isset($track['Pos']) ? $track['Pos'] : $index);
		}

				// This is what the json response used to look like for a query for playlist
				/*
				// the playlist is just a JSON-style object.  we need to double escape double quotes.
				$json_playlist .= '{	"type"		: "file",
							"url" 		: "'.$filename.'",
							"oggurl" 	: "'.$ogg_file_url.'",
							"artist" 	: "'.str_replace("\"", "\\\"", $playlist_track['Artist']).'",
							"album" 	: "'.str_replace("\"", "\\\"", $playlist_track['Album']).'",
							"title" 	: "'.str_replace("\"", "\\\"", $playlist_track['Title']).'",
							"art" 		: "'.$album_art_filename.'",
							"file" 		: "'.$playlist_track['file'].'",
							"time"		: "'.$playlist_track['Time'].'",
							"mpd_index" 	: "'.$mpd_playlist_index.'"	},';

				$json_playlist = '{ "tracks" : [' . rtrim($json_playlist, ",") . "] }";
				*/

		return $track;
	}

	function getAlbumArt( $filepath, $artist, $album ) {

		if ( !isset( $filepath )){

			return $this->data['default_no_album_art_image'];
		}

		// The absolute path to the music file
		$absolute_path = $this->data['music_dir'] . $filepath;

		// Generate a sha1 based on the artist and album names
		$filename = sha1( $artist . " - " . $album );

		// Concatenate the relative path to where the art file would be if it exists
		$art_file = $this->data['art_dir'] . $filename . '.jpeg';

		// Concatenate the absoluate path to where the art file would be if it exists
		$art_file_abs = $this->data['document_root'] . $art_file;

		// If no album art cache file exists yet, then create an album art cache file
		if ( !File::exists( $art_file_abs )) {

			try {
				$id3 = LetID3::analyze($absolute_path);

				$album_art_data = LetID3::getAlbumArtData($id3);

				// If we weren't able to extract any album art data, then we have to use the default image
				if ( !isset( $album_art_data )) {

					$album_art_data = File::get( $this->data['document_root'] . ltrim( $this->data['default_no_album_art_image'] ));
				}

				Image::make( $album_art_data )->resize( 64, 64 )->save( $art_file_abs, 70 );

        		} catch (Exception $error) {

				print($error->getMessage());
        		}
		}

		return $art_file;
	}
}
