<?php

class HomeController extends MPDTunesController {	

	public function index($clear=false) {

		//$redis = Redis::connection();
		//$redis->set('name', 'Dave');
		//$name = $redis->get('name');
	
		//Cache::forever('name', 'Dave');
		//$name = Cache::get('name');
		
                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("player"));

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("home"));


		// include the current track determination code that's common among home.php and queue.php
		include('partials/init_current_mpd_track_data.php');

		$this->data['json_playlist'] = $mpd_playlist_as_json;


		$languages = Language::all();
	
        	//$this->firephp->log($languages, "languages");

        	$this->data['language_options'] = array();

        	$this->data['selected_language']= "";

        	$default_language = $this->language;

        	foreach($languages as $language) {

            		$this->data['language_options'][$language->id] = $language->name;

            		if ($language->code == $default_language) {
                    
                		$this->data['selected_language'] = $language->id;
            		}
        	}



		$themes	= Theme::all();
		
        	$this->data['theme_options'] = array();

        	$this->data['selected_theme'] = "";

        	foreach($themes as $theme) {

            		$this->data['theme_options'][$theme->id] = $theme->name;

            		if ($theme->id == $this->data['currrent_theme_id']) {
                    
                		$this->data['selected_theme'] = $theme->id;
            		}
        	}

        	$this->data['theme_options'][0] = "Create a New Theme...";

                //$allSessionVariables = Session::all();
                //$this->firephp->log($allSessionVariables, "allSessionVariables");

                //$sessionId = Session::getId();
                //$this->firephp->log($sessionId, "sessionId");

          	//$this->registerCookieToucher();
		//$app = $this->app;
		//$app['session']->save();

                //$saved = Session::save();
                //$this->firephp->log($saved, "saved");

                //$sessionName = Session::getName();
                //$this->firephp->log($sessionName, "sessionName");

		return View::make('home', $this->data);
	}
}
