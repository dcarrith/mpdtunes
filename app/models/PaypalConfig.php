<?php

class PaypalConfig extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'paypal_configs';	

	protected $primaryKey = 'user_id';

	public $timestamps = false;

	// Take care of retrieving and decrypting/decompressing the users config and return an associative array
	public function config() {

		// Get the encrypted config from the database
		$encryptedConfig = $this->config;

		// Decrypt the serialized array data
		$paypalConfig = Crypt::decrypt( $encryptedConfig );	
		
		// Unserialize the array into the associative array
		$paypalConfig = unserialize( $paypalConfig );

		return $paypalConfig;
	}
}
