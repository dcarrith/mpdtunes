<?php 

use Services\Stations\Validation as StationValidationService;

class StationsController extends MPDTunesController {

	function __construct() {

		parent::__construct();

                // Get and merge the stations page config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("stations"));

                // Get and merge all the words we need for the Stations controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("stations"));
		
		//Session::regenerate();
	}

	public function index() {

		$user = Auth::user();

		$user_id = $user->id; 
		$this->data['user_id'] = $user_id;

		$station = $user->station;

		$stations = null;

		// Admins should be able to see all stations in the system so they can be administrators of them
		if ( Auth::user()->role_id == 1) {

			// Get all the stations created by this user
			$stations = Station::where('creator_id', '!=', 'NULL')->get();
		
			$this->firephp->log($stations->toArray(), "stations");

			// Get all stations for admins
			$usersStations = Station::select('stations.*')->join('users', 'users.station_id', '=', 'stations.id')->where('users.id', '!=', $user_id)->get();

			$this->firephp->log($usersStations->toArray(), "usersStations");

			// This probably isn't all that efficient, but it seems like it's the only way I can combine the two collections	
			foreach( $usersStations as $usersStation ) {

				$stations->add( $usersStation );
			}
		
		} else { 

			// Get all the stations created by this user
			$stations = Station::where('creator_id', '=', $user_id)->get();
		
			$this->firephp->log($stations->toArray(), "stations");
			
			// Get all the stations of active users that are public
			$publicStations = Station::select('stations.*')->join('users', 'users.station_id', '=', 'stations.id')->where('stations.visibility', '=', 'public')->where('users.id', '!=', $user_id)->where('users.active', '=', 1)->get();

			$this->firephp->log($publicStations->toArray(), "publicStations");	

			// This probably isn't all that efficient, but it seems like it's the only way I can combine the two collections	
			foreach( $publicStations as $publicStation ) {

				$stations->add( $publicStation );
			}
		}

		$this->firephp->log($stations->toArray(), "stations with public stations added in");
 
		// Lazily eager load the stationIcon objects - this will reduce the number of queries from 3 to 1 with 3 stations listed
                $stations->load('stationsIcon');

                $this->data['users_station_id'] = $station->id;

                $this->data['stations'] = $stations;

                $this->firephp->log($this->data, "data");

                return View::make('stations', $this->data);
	}

        public function getStation() {

		$user = Auth::user();
	
		// Default these to blank and null so we know whether or not it gets set
                $station_id = null;
                $station_url = "";
                $station_name = "";
                $station_description = "";
		$station_visibility_on_off = null;
		$station_icon_id = 1;
		$station_icon_url_path = "";
	
	        $this->data['saved_successfully'] = '';
		$this->data['station_url_input_disabled'] = array();
		$this->data['station_image_file_disabled'] = '';
		$this->data['station_name_input_disabled'] = array();
		$this->data['station_description_input_disabled'] = array();
		$this->data['station_visibility_input_options'] = array();
		$this->data['station_save_button_disabled'] = array();

	        // We want to make sure we only retrieve details once
                if (!Session::has('success')) {

			// The getStation action is shared between the add and edit routes
			if (Request::segment(2) == 'add') {

        			// Translate the default station visibility to checkbox values of on or off
        			$station_visibility_on_off = ((Config::get('defaults.default_user_station_visibility') == "public") ? "on" : "off");

        			// the default icon id for all stations is the generic station icon
        			$station_icon_id = 1;

        			// the default icon_url_path is set at the database level (i.e. the icon_id field defaults to 1, 
        			// which has an icon_url (i.e. base_url) of mpd/master/ and value 'default_no_station_icon.jpg' as icon
        			$station_icon_url_path = Request::root().Config::get('defaults.default_no_station_icon');

			} else if (Request::segment(2) == 'edit') {

        			// Get the id of the station to edit from the URL
        			$station_id = Request::segment(3);
        	
				$users_station_id = Auth::user()->station_id;

				// We don't want regular users to be able to change their station url, but admins can
				if (($station_id == $users_station_id) && (Auth::user()->role_id > 1)) {

					// Disable the station url text field so it can't be changed
					$this->data['station_url_input_disabled'] = array( "readonly"=>"readonly" );
					
					// Update the url special note to say that it can't be changed
					$this->data['url_special_note_i18n'] = $this->data['cant_be_changed_i18n'];
				}

				//$users_station = DB::table('users_stations')->where('user_id', $user->id)->where('station_id', $station_id)->get();
				//var_dump($users_station);
				//exit();

				$this->firephp->log($user->stations->toArray(), "user's stations");

				$this->data['deletable'] = false;
	
				// We don't want anyone to be able to edit anyone's station
				$allowed_to_edit = false;

				$user_owns_station = false;

				$users_stations = $user->stations->toArray();

				foreach($users_stations as $user_station) {

					// If the station being edited is one of the stations the user created, then let them edit it
					if ($user_station['id'] == $station_id) {
						
						$user_owns_station = true;			

						break;
					}
				}

				if(( $station_id == $users_station_id) || (Auth::user()->role_id == 1) || $user_owns_station) {

					$allowed_to_edit = true;
				}
			
				if (( $user_owns_station ) || ( Auth::user()->role_id == 1)) {

					$this->data['deletable'] = true;				
				}
				
				if ( !$allowed_to_edit ) {

					// Disable the station url text field so it can't be changed
					$this->data['station_url_input_disabled'] = array( "readonly"=>"readonly" );
					
					// Update the url special note to say that it can't be changed
					$this->data['url_special_note_i18n'] = $this->data['cant_be_changed_i18n'];

					$this->data['station_image_file_disabled'] = 'disabled="disabled"';
					$this->data['station_name_input_disabled'] = array( "readonly"=>"readonly" );
					$this->data['station_description_input_disabled'] = array( "readonly"=>"readonly" );
					$this->data['station_visibility_input_options'] = array( "readonly"=>"readonly" );
					$this->data['station_save_button_disabled'] = array( "disabled"=>"disabled" );
				}
	
        			// Retrieve the station being edited 
        			$station = Station::find($station_id);
        			$stationIcon = StationsIcon::find($station->icon_id);

				// Make sure the station_icon_id is set
				$station_icon_id = $stationIcon->id;

        			// Retrieve station values from the instance of the model
        			$station_id = $station->id;
        			$station_url = $station->url;
        			$station_name = $station->name;
        			$station_description = $station->description;

        			// Translate the station visibility to checkbox values of on or off
        			$station_visibility_on_off = (($station->visibility == "public") ? "on" : "off");

				$station_icon_url_path = Request::root()."/".$stationIcon->baseurl.$stationIcon->filename;
			}

                } else {

			$flashedInput = Input::old();
		
			$this->firephp->log($flashedInput, "flashedInput");

                        $this->firephp->log(Session::get('success'), 'successful?');

			// If the form was just submitted, then we should have all of the fields we need to re-display
                        if (Session::get('success')) {

                                $this->data['saved_successfully'] = true;

                                $station_id = Request::old('station_id');
			
				// If we are at the edit page, but we don't have a flashed station_id, then we came from the add form
				if ((Request::segment(2) == 'edit') && ($station_id == '')) {

        				// Get the id of the station to edit from the URL
        				$station_id = Request::segment(3);
        				$this->firephp->log($station_id, 'station_id was not set yet, so we have to retrieve it from the URL');
				}
        	
				$users_station_id = Auth::user()->station_id;

				// We don't want regular users to be able to change their station url, but admins can
				if (($station_id == $users_station_id) && (Auth::user()->role_id > 1)) {

					// Disable the station url text field so it can't be changed
					$this->data['station_url_input_disabled'] = array( "readonly"=>"readonly" );
					
					// Update the url special note to say that it can't be changed
					$this->data['url_special_note_i18n'] = $this->data['cant_be_changed_i18n'];
				} 

				$this->firephp->log($user->stations->toArray(), "user's stations");

				$this->data['deletable'] = false;
	
				// We don't want anyone to be able to edit anyone's station
				$allowed_to_edit = false;

				$user_owns_station = false;

				$users_stations = $user->stations->toArray();

				foreach($users_stations as $user_station) {

					// If the station being edited is one of the stations the user created, then let them edit it
					if ($user_station['id'] == $station_id) {
						
						$user_owns_station = true;			

						break;
					}
				}

				if(( $station_id == $users_station_id) || (Auth::user()->role_id == 1) || $user_owns_station) {

					$allowed_to_edit = true;
				}
			
				if (( $user_owns_station ) || ( Auth::user()->role_id == 1)) {

					$this->data['deletable'] = true;				
				}
				
				if ( !$allowed_to_edit ) {

					// Disable the station url text field so it can't be changed
					$this->data['station_url_input_disabled'] = array( "readonly"=>"readonly" );
					
					// Update the url special note to say that it can't be changed
					$this->data['url_special_note_i18n'] = $this->data['cant_be_changed_i18n'];

					$this->data['station_image_file_disabled'] = 'disabled="disabled"';
					$this->data['station_name_input_disabled'] = array( "readonly"=>"readonly" );
					$this->data['station_description_input_disabled'] = array( "readonly"=>"readonly" );
					$this->data['station_visibility_input_options'] = array( "readonly"=>"readonly" );
					$this->data['station_save_button_disabled'] = array( "disabled"=>"disabled" );
				}

				// We shouldn't need to do this
				//$station = Station::find($station_id);

				$station_url = Input::old('station_url');
				
				//$this->firephp-log($station_url, "old station_url");
				$station_url = (( !strpos($station_url, "://")) ? "http://".$station_url : $station_url);

				$station_name = Input::old('station_name');
				$station_description = Input::old('station_description');
				$station_visibility_on_off = Input::old('station_visibility');
				$station_icon_id = Input::old('station_icon_id');
				
				// We still need to get the StationsIcon object since all we have is the icon id
				$stationIcon = StationsIcon::find($station_icon_id);
                        }

			$station_icon_url_path = Request::root()."/".$stationIcon->baseurl.$stationIcon->filename;
                }

		if (!isset($station_visibility_on_off)) {

			$station_visibility_on_off = "off";
		}

		// Log the variables to firebug so we can verify they're all set as they should be by now
		$this->firephp->log($station_id, 'station_id');
		$this->firephp->log($station_name, 'station_name');
		$this->firephp->log($station_description, 'station_description');
		$this->firephp->log($station_url, 'station_url');
		$this->firephp->log($station_visibility_on_off, "station_visibility_on_off");

		if( isset( $stationIcon )) {

			$this->firephp->log($stationIcon->id, 'station_icon_id');
			$this->firephp->log($stationIcon->baseurl, 'station_icon_url');
			$this->firephp->log($stationIcon->filename, 'station_icon');
			$this->firephp->log($station_icon_url_path, 'station_icon_url_path');
		}

		// Set all the data variables below
		if (Request::segment(2) == 'edit') {
        			
			$this->data['mode'] = 'edit';
        		$this->data['form_action_url'] = '/stations/edit/'.$station_id;
        		$this->data['data_url'] = '/stations/edit/'.$station_id;
        		$this->data['edit_station_id'] = $station_id;
		
		} else {

			$this->data['mode'] = 'add';
			$this->data['form_action_url'] = '/stations/add';
			$this->data['data_url'] = '/stations/add';
		}

		$this->data['station_id'] = $station_id;
		$this->data['station_name'] = $station_name;
		$this->data['station_description'] = $station_description;
		$this->data['station_url'] = $station_url;

		$this->data['station_visibility'] = $station_visibility_on_off;

		// Default the visibility options to off
		$station_visibility_input_options = array('value'=>'off');
                
		// We need to set up the options to pass to the Form::input for the station visibility checkbox
                if ($station_visibility_on_off == "on") {

			// Set the visibility options to on
			$station_visibility_input_options = array('checked'=>'checked', 'value'=>'on');
                        
			// We will just array_merge this option into the other options being set in the view
                        $this->data['station_visibility_input_options'] = $station_visibility_input_options;
                }

		$this->firephp->log($station_visibility_input_options, 'station_visibility_input_options');

		// the default icon id for all stations is the generic station icon
		$this->data['icon_id'] = $station_icon_id;

		// the default icon_url_path is set at the database level (i.e. the icon_id field defaults to 1, 
		// which has an icon_url (i.e. base_url) of mpd/master/ and value 'default_no_station_icon.jpg' as icon
		$this->data['icon_url_path'] = $station_icon_url_path;

                return View::make('station', $this->data);
        }

	public function postStation() {

		$user = Auth::user();
		$user_id = $user->id;
	                			
		// Create a null station
		$station = null;

		$inputAll = Input::all();
		$this->firephp->log($inputAll, "inputAll");

                $posted_station_id 		= Input::get('station_id');
                $posted_station_url 		= Input::get('station_url');
                $posted_station_name 		= Input::get('station_name');
                $posted_station_description 	= Input::get('station_description');
		$posted_station_icon_id         = Input::get('station_icon_id');
                $posted_station_visibility 	= Input::get('station_visibility');
		$station_visibility             = (($posted_station_visibility == "on") ? 'public' : 'private');

                $this->firephp->log($posted_station_id, "posted_station_id");
                $this->firephp->log($posted_station_url, "posted_station_url");
                $this->firephp->log($posted_station_name, "posted_station_name");
                $this->firephp->log($posted_station_description, "posted_station_description");
		$this->firephp->log($posted_station_icon_id, "posted_station_icon_id");
                $this->firephp->log($posted_station_visibility, "posted_station_visibility");
		$this->firephp->log($station_visibility, "station_visibility");

		$posted_station_url = (( !strpos($posted_station_url, "://")) ? "http://".$posted_station_url : $posted_station_url);
		
                // Add the csrf before filter to guard against cross-site request forgery
                $this->beforeFilter('csrf');
                
		try {

			$validate = new StationValidationService(Input::all());
 
			// The getForm is shared between the add and edit routes
                	if (Request::segment(2) == 'add') {

                        	$this->data['mode'] = 'add';

                        	// Create a new station and populate then save it
                        	$station = new Station;

                        	// Set the default station icon id
                        	$station->icon_id = 1;

				// Set the creator_id 
				$station->creator_id = $user_id;

				$validate->add();
	
			} else if (Request::segment(2) == 'edit') {

                        	$this->data['mode'] = 'edit';

				// Get the id of the station to edit from the URL
				$edit_station_id = Request::segment(3);

				$users_station_id = $user->station_id;

				$this->firephp->log($edit_station_id, "edit_station_id");

				$this->firephp->log($user->stations->toArray(), "user's stations");
	
				// We don't want anyone to be able to edit anyone's station
				$allowed_to_edit = false;

				$user_owns_station = false;

				$users_stations = $user->stations->toArray();

				foreach($users_stations as $user_station) {

					// If the station being edited is one of the stations the user created, then let them edit it
					if ($user_station['id'] == $edit_station_id) {
						
						$user_owns_station = true;			

						break;
					}
				}

				if(( $edit_station_id == $users_station_id) || ($user->role_id == 1) || $user_owns_station) {

					$allowed_to_edit = true;
				}

				if( $allowed_to_edit ) {

					// Load in the existing station so we can update it
					$station = Station::find($edit_station_id);
				}

				$validate->edit();
			}

		} catch (ValidateException $errors) {
	
                	if (Request::segment(2) == 'add') {
		
				//Send the $validation object to the redirected page along with inputs
				return Redirect::to('/stations/add')->withErrors($errors->get())->withInput();
			
			} else {

				//Send the $validation object to the redirected page along with inputs
				return Redirect::to('/stations/edit/'.$edit_station_id)->withErrors($errors->get())->withInput();
			}
		}

		if (isset($station)) {

			$oldStationIcon = $station->stationsIcon;

			$posted_station_url_hash = hash('sha512', $posted_station_url); 

			$this->firephp->log($posted_station_url_hash, "posted_station_url_hash");

			$station->name = $posted_station_name;
			$station->description = $posted_station_description;
			$station->url = $posted_station_url;
			$station->url_hash = $posted_station_url_hash;
			$station->visibility = $station_visibility;
			$station->icon_id = $posted_station_icon_id;
			$station->save();

			$this->firephp->log($posted_station_url_hash, "forgetting the station with url hash");			

			$is_a_users_station = false;

			// http://demo.mpdtunes.com:16604/mpd.ogg
			$matches = array();
 
			$mpdogg = preg_match('/mpd\.ogg/i', $posted_station_url);

			$this->firephp->log($mpdogg, "mpdogg?");

			// get host name from URL
			preg_match('@^(?:http://|https://)?([^/]+)@i', $posted_station_url, $matches);
			$domain_port = $matches[1];
			$this->firephp->log($domain_port, "matched fqdn");

			$stream_domain = current(explode(":", $domain_port));

			$this->firephp->log($this->data['base_domain'], "base domain");

			// If the fqdn of the stream url is the same as the site's base_domain and the mpd.ogg exists, then
			// we'll assume that this is a user's station
			if ( ($mpdogg == 1) && ($this->data['base_domain'] == $stream_domain) ) {
					
				$is_a_users_station = true;

				$this->firephp->log($posted_station_url." is a user's station.", "message");
			}	

			// Remove the station entry from cache
			$forgotten = Cache::forget($posted_station_url_hash."_".($is_a_users_station ? "null" : $this->user->id));
			$this->firephp->log($forgotten, "forgotten?");

			if (($oldStationIcon->id > 1) && ($oldStationIcon->id != $posted_station_icon_id)) {
				
				// Delete the old stationIcon from the filesystem and the database
				unlink($oldStationIcon->filepath.$oldStationIcon->filename);
				$oldStationIcon->delete();
				
				// Remove the station's icon entry from cache
				Cache::forget($posted_station_url_hash."_".($is_a_users_station ? "null" : $this->user->id)."_icon");
			}		

			Session::flash('success', true);
			
			$this->firephp->log($station->id, "flashing station arrow id");

			Session::flash('station_id', $station->id);

			// We want to redirect to the edit page after we save the newly added station
			$edit_station_id = $station->id;
		}	

		// Redirect to itself - which will naturally arrive at the getAdd action - so, we need to set two session variables and inputs
		return Redirect::to('/stations/edit/'.$edit_station_id, 303)->withInput();
	}

	public function delete() {

		$user_id 	= Auth::user()->id;
		$station_id	= Request::get('station_id');

		if ((isset($station_id) && $station_id != '') && (isset($user_id) && $user_id != '')) {

			$this->firephp->log($user_id, "user_id");
			$this->firephp->log($station_id, "station_id");

			$station = Station::find($station_id);
			
			if (($user_id == $station->creator_id) || ($this->data['admin_user'])) {

				if (isset($station)) {

					$stationIcon = $station->stationsIcon;

					if ($stationIcon->id > 1) {
				
						// Delete the old stationIcon from the filesystem and the database
						unlink($stationIcon->filepath.$stationIcon->filename);
						$stationIcon->delete();
					}		

					$station->delete();
				}
			}
		}
	}
}
