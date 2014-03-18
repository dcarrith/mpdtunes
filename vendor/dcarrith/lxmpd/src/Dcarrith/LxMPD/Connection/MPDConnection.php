<?php namespace Dcarrith\LxMPD\Connection;
/**
* MPDConnection.php: A class for opening a socket connection to MPD
*/

use Config;
use Dcarrith\LxMPD\Exception\MPDConnectionException as MPDConnectionException; 

/**
* This class is responsible for establishing a socket connection to MPD
* @package LxMPD
*/
class MPDConnection {

        // MPD Responses
        const MPD_OK = 'OK';
        const MPD_ERROR = 'ACK';

        // Connection, read, write errors
        const MPD_CONNECTION_FAILED = -1;
        const MPD_CONNECTION_NOT_OPENED = -2;
	const MPD_DISCONNECTION_FAILED = -6;      

	// Variable for storing properties available via PHP magic methods: __set(), __get(), __isset(), __unset()
	private $_properties = array();

        /**
         * Empty constructor
         * @return void
         */
        function __construct( $host, $port, $password ) {

		$this->host = $host;
		$this->port = $port;
		$this->password = $password;

		// Initialize the magic variables which are actually all stored in the _properties array
		$this->version = '0';
		$this->established = false;
		$this->socket = null;
		$this->local = true;
		$this->timeout = 5;
		$this->debug = false;
	}

        /**
         * Establishes a connection to the MPD server
         * @return bool
         */
        public function establish() {

                // Check whether the socket is already connected
                if( isset($this->established) && $this->established ) {
                        return true;
                }
                
		// Try to open the socket connection to MPD with a 5 second timeout
		if( !$this->socket = @fsockopen( $this->host, $this->port, $errn, $errs, 5 ) ) {

			// Throw an MPDConnectionException along with the connection errors
			throw new MPDConnectionException( 'Connection failed: '.$errs, self::MPD_CONNECTION_FAILED );
		}

                // Clear connection messages
                while( !feof( $this->socket ) ) {

                        $response = trim( fgets( $this->socket ) );

                        // If the connection messages have cleared
                        if( strncmp( self::MPD_OK, $response, strlen( self::MPD_OK ) ) == 0 ) {

				// Successully connected
                                $this->established = true;

                                // Parse the MPD version from the response and replace the ending 0 with an x 
                                $this->version = preg_replace('/[0]$/','x', current( sscanf( $response, self::MPD_OK . " MPD %s\n" )));

				// Connected successfully
				return true;
                        }

                        // Check to see if there is a connection error message that was sent in the response
                        if( strncmp( self::MPD_ERROR, $response, strlen( self::MPD_ERROR ) ) == 0 ) {

				// Parse out the error message from the response
                                preg_match( '/^ACK \[(.*?)\@(.*?)\] \{(.*?)\} (.*?)$/', $response, $matches );

				// Throw an exception and include the response errors
                                throw new MPDConnectionException( 'Connection failed: '.$matches[4], self::MPD_CONNECTION_FAILED );
                        }
                }
      
		// Throw a general connection failed exception 
		throw new MPDConnectionException( 'Connection failed', self::MPD_CONNECTION_FAILED );
        }

        /**
         * Closes the connection to the MPD server
         * @return bool
         */
        public function close() {

		// Make sure nothing unexpected happens
		try {
		
			// Check that a connection exists first
                	if( isset( $this->socket ) ) {

				// Close the socket
                        	fclose( $this->socket );

				// Unset the socket property
                        	unset($this->socket);

				// The connection is no longer established
				$this->established = false;
                	}

		} catch (Exception $e) {

			throw new MPDConnectionException( 'Disconnection failed: '.$e->getMessage(), self::MPD_DISCONNECTION_FAILED );		
		}

		// We'll assume it was successful
                return true;
        }
	
	/**
         * Sets the timeout for the stream socket connection to MPD
         * @return void
         */
        public function setStreamTimeout( $timeout ) {

		// Set the timeout in seconds
		stream_set_timeout( $this->socket, $timeout );
        }

	/**
         * Checks whether the connection object has a password to use for communicating with MPD
         * @return bool
         */
        public function hasPassword() {

                return !( is_null( $this->password ) && ( $this->password != "" ));
        }

	/**
	 * determineIfLocal tries to determine if the connection to MPD is local
	 * @return bool
	 */
	public function determineIfLocal() {

		// Default it to false
		$this->local = false;

		// Compare the MPD host a few different ways to try and determine if it's local to the Apache server
		if( 	( stream_is_local( $this->socket )) ||
			( $this->host == (isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : getHostByName( getHostName() ))) ||
			( $this->host == 'localhost' ) || 
			( $this->host == '127.0.0.1' )) {

			$this->local = true;
		}
	}

	/**
	 * PHP magic methods __get(), __set(), __isset(), __unset()
	 *
	 */
 
	public function __get($name) {

		if ( array_key_exists( $name, $this->_properties )) {
			return $this->_properties[$name];
		}

		$trace = debug_backtrace();

		trigger_error(	'Undefined property via __get(): ' . $name .
				' in ' . $trace[0]['file'] .
				' on line ' . $trace[0]['line'],
				E_USER_NOTICE	);
		
		return null;
	}

	public function __set( $name, $value ) {
		$this->_properties[$name] = $value;
	}

	public function __isset( $name ) {
		return isset( $this->_properties[$name] );
	}

	public function __unset( $name ) {
		unset( $this->_properties[$name] );
	}
}
