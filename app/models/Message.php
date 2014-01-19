<?php

class Message extends Eloquent {

        /**
         * define relationship to station
         */
         public function radioStation()
         {
                 return $this->belongsTo('radiostation');
         }

        /**
         * define relationship to listener
         */
         public function listener()
         {
                 return $this->belongsTo('listener');
         }

}
