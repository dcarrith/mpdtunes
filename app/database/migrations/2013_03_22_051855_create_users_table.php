<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		/*
			CREATE TABLE IF NOT EXISTS mpdtunes.users (
  				id int(11) NOT NULL AUTO_INCREMENT COMMENT 'the auto-incrementing primary key of the users table',
  				first_name varchar(64) DEFAULT NULL,
  				last_name varchar(64) DEFAULT NULL,
  				email varchar(128) DEFAULT NULL,
  				password varchar(128) DEFAULT NULL,
  				password_salt varchar(128) DEFAULT NULL,
  				role int(2) DEFAULT 3,
  				created datetime DEFAULT NULL,
  				modified datetime DEFAULT NULL,
  				active tinyint(1) DEFAULT 0,
  				PRIMARY KEY (id),
  				KEY ind_mpdtunes_users_email (email),
  				KEY ind_mpdtunes_users_password (password),
  				KEY ind_mpdtunes_users_active (active)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/
		Schema::create('users', function($table) {
    		
			$table->increments('id');
    			$table->string('first_name', 64)->nullable()->default(null);
    			$table->string('last_name', 64)->nullable()->default(null);
    			$table->string('email', 128)->unique();
                        $table->string('password', 128)->index();
    			$table->integer('role_id')->default(3)->foreign()->references('id')->on('roles');
    			$table->integer('station_id')->nullable()->default(null)->foreign()->references('id')->on('stations');
			$table->tinyInteger('active')->default(0)->index();
			$table->timestamps();
			$table->engine = 'InnoDB';
		});

		DB::table('users')->insert(

  			array(
      				'id' => '1',
      				'first_name' => 'MPDTunes',
      				'last_name' => 'Administrator',
      				'email' => 'admin@mpdtunes.com',
      				'password' => '$2y$08$Gp1Y6dUJzM3VjZk4xLvUUe0WNNLAHosMRMIWQZ3wGo92Xx2rSub.a',
      				'role_id' => 1,
      				'station_id' => 1,
				'active' => 1
  			)
		);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
