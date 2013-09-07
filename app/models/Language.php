<?php

class Language extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'languages';

        /**
         * The inverse relationship between a Language and UserPreferences
         *
         * @var UsersPreferences
         */
        public function preferences()
        {	
        	return $this->hasMany('UsersPreferences', 'language_id');
	}
}
