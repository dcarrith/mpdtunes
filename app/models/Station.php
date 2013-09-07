<?php

class Station extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'stations';

        /**
         * The relationship between a User and a Users_Config
         *
         * @return string
         */
        public function stationsIcon()
        {
                return $this->belongsTo('StationsIcon', 'icon_id');
        }

        /**
         * The inverse relationship between User and Station
         *
         * @return User
         */
        public function owner()
        {
		return $this->hasOne('User');
	}

        /**
         * The inverse relationship between User and Station
         *
         * @return User
         */
        public function creator()
        {
		return $this->belongsTo('User', 'creator_id');
	}

	//
}
