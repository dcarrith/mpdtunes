<?php namespace Dcarrith\LetId3;

use \GetId3\GetId3Core as GetId3;

class LetId3 extends GetId3 {

	/*
	 * GetId3 seems to parse the ID3 tags into a flat array structure, so we need to keep track of when we find an essential 
	 * tag so we can then grab the value that's stored in the "data" element later on in the iteration of the array
	 * 
	 * ID3v1.x - http://en.wikipedia.org/wiki/ID3#ID3v1
	 *
	 * 	Title
	 * 	Artist
	 * 	Album
	 * 	Year
	 * 	Comment
	 * 	Track (only if last 2 bytes of Comment field are unused)
	 * 	Genre
	 *  
	 * ID3v2.x - http://id3.org/id3v2.3.0
	 *
	 * 	TIT2: The 'Title/Songname/Content description' frame is the actual name of the piece (e.g. "Adagio", "Hurricane Donna").
	 * 	TPE1: The 'Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group' is used for the main artist(s). They are seperated with the "/" character. 
	 * 	TPE2: The 'Band/Orchestra/Accompaniment' frame is used for additional information about the performers in the recording. 
	 * 	TALB: The 'Album/Movie/Show title' frame is intended for the title of the recording(/source of sound) which the audio in the file is taken from. 
	 * 	TCON: The 'Content type' [Genre], which previously was stored as a one byte numeric value only, is now a numeric string. You may use one or several of the types as ID3v1.1 did or, since the category list would be impossible to maintain with accurate and up to date categories, define your own.     
	 * 	TRCK: The 'Track number/Position in set' frame is a numeric string containing the order number of the audio-file on its original recording. This may be extended with a "/" character and a numeric string containing the total numer of tracks/elements on the original recording. E.g. "4/9".
	 * 	TYER: The 'Year' frame is a numeric string with a year of the recording. This frames is always four characters long (until the year 10000). 
	 * 	APIC: Attached picture
	 * 	
	 */

	// Variable for storing properties available via PHP magic methods: __set(), __get(), __isset(), __unset()
	private $_data = array();

	// This is the default array of tags we care about (not sure if we care about "filename", "filepath", and "filenamepath" yet)
	private $_essentialTags = array( "TIT2", "TPE1", "TALB", "TRCK", "APIC", "artist", "album", "track", "picture" );

	// This maps some of the interchangeable tags that we care about back and forth
	private $_alternativeTags = array( 	"TIT2" => "title", 
						"TPE1" => "artist", 
						"TALB" => "album", 
						"TRCK" => "track",
						"title" => "TIT2",
						"artist" => "TPE1",
						"album" => "TALB",
						"track" => "TRCK"	);
	
	// This is the list of tags that possibly need the values cleaned up a bit
	private $_needsCleaning = array(	"TIT2" => "title", 
						"TPE1" => "artist", 
						"TALB" => "album", 
						"title" => "TIT2",
						"artist" => "TPE1",
						"album" => "TALB"	);

        function __construct() { 

		parent::__construct();
	}

	/**
	 * Parses the APIC and picture id3 data into the 
	 * corresponding properties and then returns the data
	 *
	 * @param array $id3 - the analyzed id3 data array
	 * @return the binary picture data
	 */
	public function getAlbumArtData( $id3 ) {

		// Parse out only the "APIC" and "picture" data into the essentialTagData array
		$this->parseEssentialTagData($id3, array( "APIC", "picture" ));
		
		// Check for a picture via the __isset() magic method
		if( isset($this->picture) ) {
	
			// Retrieve the picture via the __get() magic method
			return $this->picture;
		
		} else if( isset($this->APIC) ) {

			// Retrieve the APIC via the __get() magic method
			return $this->APIC;
		}

		// If we couldn't find a picture, then just return null so it'll serve up the default image
		return null;
	}
	
	/**
	 * Recursive function for parsing out select tags from 
         * the analyzed id3 data array into the corresponding 
	 * magic properties
	 *
	 * @param array $id3 - the analyzed id3 data array
	 * @param array $target - the array of tags that are of interest
	 * @return void
	 */
	public function parseEssentialTagData( $id3, $target = null ) {

		// Use the passed in target parameter if it's set, otherwise, just use the default list of all essential tags
		$essentialTags = (isset($target) ? $target : $this->_essentialTags);

		// Loop through the id3 array as key and value pairs
		foreach( $id3 as $key => $value ) {

			// If it's not an array, then proceed with checks.  Otherwise recurse
			if ( !is_array($value) ) {
			
				if( $key == "data" ) {

					if ( isset($this->_currentTagKey) ) {

						// Create a variable through __set() magic method and name it based on value of _currentTagKey
						$variableName = $this->_currentTagKey;

						// Now use the variableName value for creating a variable via __set() magic method
						$this->$variableName = $value;

						// Unset the _currentTagKey
						unset($this->_currentTagKey);
					}
					
				} else {

					// If the key of this iteration is an essential tag, then we want to store the value
					if ( in_array( $key, $essentialTags )) {

						// Create a variable through __set() magic method and name it based on value of $key
						$$key = $value;
					}
				}

			} else {

				// If key is one that we care about, then let's save it
				if ( in_array( $key, $essentialTags )) {

					if ( $key !== 0 ) {

						// This is so we know when to start storing the value for key
						$this->_currentTagKey = $key;
					}
				}
			
				// Recursively call getEssentialTagData on the value that's an array
				$this->parseEssentialTagData( $value, $target );
			}
		}
	}

	/**
	 * Cleans up a tag so that it's stored properly
	 *
	 * @param string $tag - a tag value
	 * @return cleaned up tag
	 */
	public function clean( $tag ) {

		// I was seeing an issue with & being encoded to &amp; which would then make it so MPD couldn't find it
		$tag = html_entity_decode( $tag );

		// Replace /, <, >, | and : with nothing
		//$tag = preg_replace("/[\/|\>|\<|\||\:]/", "", $tag);

		// Replace ampersands with "and"
		//$tag = preg_replace("/[&]/", "and", $tag);

		return $tag;
	}

	/**
	 * dynamically retrieve attributes from the object.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get( $key ) {

		if ( array_key_exists( $key, $this->_data )) {

			if ( in_array( $key, $this->_needsCleaning )) {

				return $this->clean( $this->_data[$key] );

			} else {

				return $this->_data[$key];
			}
		
		} else {

			// If the $name isn't set, then let's see if has an alternative that may be set
			if( in_array( $key, $this->_alternativeTags ) ) {
			
				if ( array_key_exists( $this->_alternativeTags[ $key ], $this->_data )) {

					if ( in_array( $key, $this->_needsCleaning )) {

						return $this->clean( $this->_alternativeTags[ $key ] );

					} else {

						return $this->_alternativeTags[ $key ];
					}
				}
			}
		}

		$trace = debug_backtrace();

		trigger_error(	'Undefined property via __get(): ' . $key .
				' in ' . $trace[0]['file'] .
				' on line ' . $trace[0]['line'],
				E_USER_NOTICE	);
		
		return null;
	}

	/**
	 * dynamically set properties of the object.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function __set( $key, $value ) {

		$this->_data[ $key ] = $value;
	}

	/**
	 * Dynamically determine if a dynamic property exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __isset( $key ) {
		return isset( $this->_data[$key] );
	}

	/**
	 * Dynamically unset a dynamic property
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __unset( $key ) {
		unset( $this->_data[$key] );
	}
}
