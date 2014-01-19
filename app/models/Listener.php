<?php

class Listener extends Eloquent {

        /**
         * only expose these values to the frontend
         */
        protected $visible = array('session_id', 'name', 'connected');

        /**
         * define relationship to station
         */
         public function radioStation()
         {
                 return $this->belongsTo('radiostation');
         }

        /**
         * define relationship to messages
         */
         public function messages()
         {
                 return $this->hasMany('message');
         }
}
