<?php

class SessionController extends BaseController {	

        public function __construct() {

                parent::__construct();
	}

    	public function clear() {

        	$section = Request::get('section');
        	$section = $section ? $section : '';

		if ($section == "") {

			Session::forget('genres_listed_so_far');
			Session::forget('artists_listed_so_far');
			Session::forget('albums_listed_so_far');
			Session::forget('playlist_tracks_listed_so_far');
			Session::forget('queue_tracks_listed_so_far');
			Session::forget('playlists_listed_so_far');
			Session::forget('stations_listed_so_far');

			return Response::make(1, 200);	
		}

		//$this->firephp->log($section, "section");
                //$allSessionVariables = Session::all();
		//var_dump($allSessionVariables);
		//$this->firephp->log($allSessionVariables, "allSessionVariables");

        	switch($section) {

            		case 'genres' :
                		if (Session::has('genres_listed_so_far')) {
                    			Session::forget('genres_listed_so_far');
                		}
                	break;
            		
			case 'artists' :
                		if (Session::has('artists_listed_so_far')) {
					$this->firephp->log(Session::get('artists_listed_so_far'), "forgetting artists_listed_so_far");
                    			Session::forget('artists_listed_so_far');
                		}
                	break;
            	
			case 'albums' :
                		if (Session::has('albums_listed_so_far')) {
					$this->firephp->log(Session::get('albums_listed_so_far'), "forgetting albums_listed_so_far");
                    			Session::forget('albums_listed_so_far');
                		}
                	break;
            	
			case 'playlist' :
                		if (Session::has('playlist_tracks_listed_so_far')) {
                    			Session::forget('playlist_tracks_listed_so_far');
                		}
                	break;
            	
			case 'queue' :
                		if (Session::has('queue_tracks_listed_so_far')) {
                    			Session::forget('queue_tracks_listed_so_far');
                		}
                	break;
            	
			case 'playlists' :
                		if (Session::has('playlists_listed_so_far')) {
                    			Session::forget('playlists_listed_so_far');
                		}
                	break;
            	
			case 'stations' :
                		if (Session::has('stations_listed_so_far')) {
                    			Session::forget('stations_listed_so_far');
                		}
                	break;
            	
			default :

				/*
                		if (Session::has('genres_listed_so_far')) {
                    			Session::forget('genres_listed_so_far');
                		}
                		if (Session::has('artists_listed_so_far')) {
					$artists_listed_so_far = Session::get('artists_listed_so_far');
					$this->firephp->log($artists_listed_so_far, 'artists_listed_so_far');
					$this->firephp->log(Session::get('artists_listed_so_far'), "forgetting artists_listed_so_far");
                    			Session::forget('artists_listed_so_far');
					$this->firephp->log($artists_listed_so_far, 'artists_listed_so_far');
                		}
                		if (Session::has('albums_listed_so_far')) {
                    			Session::forget('albums_listed_so_far');
                		}
                		if (Session::has('tracks_listed_so_far')) {
                    			Session::forget('tracks_listed_so_far');
                		}
                		if (Session::has('queue_tracks_listed_so_far')) {
				 */	
					/*$queue_tracks_listed_so_far = Session::get('queue_tracks_listed_so_far');
					$this->firephp->log($queue_tracks_listed_so_far, 'queue_tracks_listed_so_far');

					$default_num_queue_tracks_to_display = $this->data['default_num_queue_tracks_to_display'];
					$this->firephp->log($default_num_queue_tracks_to_display, "setting queue_tracks_listed_so_far back to default");
					Session::put('queue_tracks_listed_so_far', $default_num_queue_tracks_to_display);

					$queue_tracks_listed_so_far = Session::get('queue_tracks_listed_so_far');
					$this->firephp->log($queue_tracks_listed_so_far, 'queue_tracks_listed_so_far');
                			*/

				/*	Session::forget('queue_tracks_listed_so_far');
				}
                		if (Session::has('playlists_listed_so_far')) {
                    			Session::forget('playlists_listed_so_far');
                		}
                		if (Session::has('stations_listed_so_far')) {
                    			Session::forget('stations_listed_so_far');
				}*/
				
                	break;          
        	}

		//var_dump(Session::all());

		//$saveResult = Session::save();
		//$this->firephp->log($saveResult, "saveResult");		

		$sessionId = Session::getId();		
		$this->firephp->log($sessionId, "sessionId");

		//$saved = Session::save();
		//$this->firephp->log($saved, "saved");

		//$sessionName = Session::getName();
		//$this->firephp->log($sessionName, "sessionName");

		//$sessionData = Session::getBag($sessionName);
		//$this->firephp->log($sessionData, "sessionData");

		//Session::write($sessionId, $data);

		//['session']->save();

                $allSessionVariables = Session::all();
                $this->firephp->log($allSessionVariables, "allSessionVariables");

        	//echo "1";

		return Response::make(1, 200);
    	}		
}
