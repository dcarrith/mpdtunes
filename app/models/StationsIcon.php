<?php

class StationsIcon extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'stations_icons';

        /**
         * The inverse relationship between StationsIcon and Station
         *
         * @return Station
         */
        public function station()
        {
		return $this->hasOne('Station');
	}

        /**
         * The inverse relationship between StationIcon and Station
         *
         * @return Station
         */
        /*public function stations()
        {
                return $this->hasMany('Station', 'icon_id');
        }*/

        /**
         * The inverse relationship between User and Station
         *
         * @return User
         */
        public function creator()
        {
		return $this->belongsTo('User', 'creator_id');
	}
}
