<?php

class UsersPreferences extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'users_preferences';

	protected $primaryKey = 'user_id';

        /**
         * The inverse relationship between a User and a UsersPreferences
         *
         * @var User
         */
        public function user()
        {
		return $this->belongsTo('User', 'user_id');
        }

        /**
         * The relationship between a UsersPreferences and a theme
         *
         * @return Theme
         */
        public function theme()
        {
		return $this->belongsTo('Theme', 'theme_id');
	}

        /**
         * The relationship between a UsersPreferences and a Langage
         *
         * @return Language
         */
        public function language()
        {
		return $this->belongsTo('Language', 'language_id');
	}
}
