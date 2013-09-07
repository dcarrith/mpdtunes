<?php

class Theme extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'themes';

        /**
         * The inverse relationship between a Theme and UserPreferences
         *
         * @var User
         */
        public function preferences()
        {
                return $this->hasMany('UsersPreferences', 'theme_id');
        }

        /**
         * The inverse relationship between User and Theme
         *
         * @return User
         */
        public function creator()
        {
		return $this->belongsTo('User', 'creator_id');
	}
}
