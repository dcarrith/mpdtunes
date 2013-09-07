<?php

class UsersConfig extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'users_configs';	

	protected $primaryKey = 'user_id';

	public $timestamps = false;

        /**
         * The inverse one-to-one relationship from UsersConfig to User 
         *
         * @var User
         */
	public function user()
    	{
        	return $this->belongsTo('User');
    	}

	// Take care of retrieving and decrypting/decompressing the users config and return an associative array
	public function config() {

		// Store the config in the local scope
		$encryptedConfig = $this->config;

		// Decrypt the encrypted users config
		$usersConfig = Crypt::decrypt( $encryptedConfig );

		// If the site encryption key is different than the other one, then the user must want to change their site's encryption key
		if ( Config::get( 'app.key' ) != Config::get( 'defaults.default_encryption_key' )) {

			// Get the user's paypal config before setting the new encryption key
			$paypalConfig = PaypalConfig::find( $this->user_id );

			$paypalConfigs = null;

			if ( $paypalConfig ) {

				$paypalConfigs = $paypalConfig->config();	
			}

			// Set the app.key to the new key (this will only be for this single request - you'll have to update it in app/config/app.php
			Config::set( 'app.key', Config::get( 'defaults.default_encryption_key' ));

			// Set the key for the instance of the Encryptor object to be the new key
			Crypt::setKey( Config::get( 'defaults.default_encryption_key' ));
	
			$this->config = Crypt::encrypt( $usersConfig );

			$this->save();

			if ( isset($paypalConfigs) ) {

				$paypalConfig->config = Crypt::encrypt( serialize( $paypalConfigs ));

				$paypalConfig->save();
			}
		}

		// Unserialize the decrypted users config array
		$usersConfig = unserialize( $usersConfig );
	
		return $usersConfig;
	}
}
