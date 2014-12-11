<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/*
		Examples of how to use the User model

                // Retrieve User with id = 1
                $user = User::find(1);

                // Get the id of the role assigned to the User
                $role_id = $user->role;

                // Retrieve Role with id = $role_id
                $users_role = Role::find($role_id);

                // Retrieve all users of a particular role
                $users = $users_role->users;

                // Retrieve the usersConfig from the User object
                $users_config = $user->usersConfig;

                // Retrieve the User that a UserConfig object belongs to
                $user = $users_config->user;
	*/

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->username;
	}

        /**
         * The relationship between a User and a Users_Config
         *
         * @return string
         */
	public function usersConfig()
     	{
          	return $this->hasOne('UsersConfig');
     	}

	/**
         * Get the user's role from the Role model
         *
         * @return Role
         */
        public function role()
        {
                return $this->belongsTo('Role', 'role_id');
        }

        /**
         * The relationship between a User and the Station objects that have been created
         *
         * @return Result set of Station objects
         */
        public function stations()
        {
		//return $this->belongsToMany('Station', 'users_stations', 'user_id', 'station_id');
		return $this->hasMany('Station', 'creator_id');
	}

        /**
         * The relationship between a User and the StationIcon objects that have been created
         *
         * @return Result set of StationIcon objects
         */
        public function stationIcons()
        {
		//return $this->belongsToMany('Station', 'users_stations', 'user_id', 'station_id');
		return $this->hasMany('StationsIcon', 'creator_id');
	}

        /**
         * The relationship between a User and Station object that the User owns
         *
         * @return Station
         */
        public function station()
        {
 		return $this->belongsTo('Station');
	}

        /**
         * The relationship between a User and UserPreferences
         *
         * @return UsersPreferences
         */
        public function preferences()
        {
                return $this->hasOne('UsersPreferences', 'user_id');
        }

        /**
         * The relationship between a User and the Theme objects that have been created
         *
         * @return Result set of Theme objects
         */
        public function themes()
        {
		return $this->hasMany('Theme', 'creator_id');
	}

	/*public function roles()
    	{
        	return $this->belongsToMany('Role');
    	}*/

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

}
