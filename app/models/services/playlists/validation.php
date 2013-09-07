<?php

// File: app/models/services/playlists/validation.php

namespace Services\Playlists;

use Services\Validation as ValidationService;

use App;
use Auth;
use Config;
use Configurator;
use Langurator;
use MPD;
use Request;
use Validator;

class Validation extends ValidationService {

	protected $phrases = array();

	/**
	* Create a new validation service instance.
	*
	* @param  array  $input
	* @return void	
	*/
	public function __construct($input)
	{
		parent::__construct($input);
	
		// Get and merge all the words we need for the Register controller into the main data array     
                $this->phrases = Langurator::getLocalizedWords("playlists");

                Validator::extend('isunique', function($attribute, $value, $parameters) {

	                require_once('includes/php/classes/mpd.class.php');
        	        require_once('includes/php/library/mpd.inc.php');

			$defaults = Configurator::getUsersDefaults();

                	// Instantiate the MPD object to be used by the derived controllers             
                	$MPD = new mpd(   $defaults['mpd_host'],
                        	          $defaults['mpd_port'], 
                                	  $defaults['mpd_password']        );

			return !$MPD->PlaylistExists( trim($value) );
                });

                $this->rules = array(	'playlist_name' => 'required|max:64|isunique' );

		$this->messages = array(	'playlist_name.required' 	=> $this->phrases['enter_playlist_name_i18n'],
						'playlist_name.max' 		=> $this->phrases['playlist_name_max_length_i18n'],
						'playlist_name.isunique'	=> $this->phrases['playlist_with_that_name_exists_i18n']	);
	}

	/**
	* Validate the playlist name before creation.
	*
	* @throws ValidateException
	* @return void
	*/
	public function creation() {
	
		$this->validate();
	}
}
