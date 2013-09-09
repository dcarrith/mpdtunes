<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersStationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                /*
			CREATE TABLE IF NOT EXISTS mpdtunes.users_stations (
  				user_id int(11) NOT NULL COMMENT 'part of the composite primary key and foreign key into users table',
  				station_id int(11) NOT NULL COMMENT 'the second part of the composite key and foreign key into stations table',
  				PRIMARY KEY (user_id, station_id),
  				KEY ind_mpdtunes_users_stations_user_id (user_id),
  				KEY ind_mpdtunes_users_stations_station_id (station_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
                */

                Schema::create('users_stations', function($table) {

                        $table->integer('user_id')->default(1)->foreign()->references('id')->on('users');
                        $table->integer('station_id')->default(1)->foreign()->references('id')->on('stations');
                        $table->primary(array('user_id', 'station_id'));
			$table->engine = 'InnoDB';
                });

                /*
                 	INSERT INTO users_stations (user_id, station_id) VALUES (1, 1);
                */

                DB::table('users_stations')->insert(

                        array(
                                'user_id' => '1',
                                'station_id' => '2'
                        )
                );

                DB::table('users_stations')->insert(

                        array(
                                'user_id' => '1',
                                'station_id' => '3'
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
