<?php

class Role extends Eloquent {

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'roles';

        /**
         * The one-to-many relationship specifying that there are many users to a role
         *
         * @var Users
         */
        public function users()
        {
                return $this->hasMany('User', 'role_id');
        }
}
