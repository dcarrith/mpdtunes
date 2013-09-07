<?php

use Illuminate\Database\Migrations\Migration;

class CreateStationsIconsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
			CREATE TABLE IF NOT EXISTS mpdtunes.stations_icons (
  				id int(11) NOT NULL AUTO_INCREMENT COMMENT 'the auto-incrementing primary key of the stations icons table',
  				filename varchar(37) DEFAULT NULL,
  				filepath varchar(255) DEFAULT NULL,
  				baseurl varchar(255) DEFAULT NULL,
  				creator int(11) DEFAULT 1,
  				created datetime DEFAULT NULL,
  				modified datetime DEFAULT NULL,
  				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/

                Schema::create('stations_icons', function($table) {

                        $table->increments('id');
                        $table->string('filename', 64);
                        $table->string('filepath', 255);
                        $table->string('baseurl', 255);
                        $table->integer('creator_id')->default(1)->foreign()->references('id')->on('users');
                        $table->timestamps();
                        $table->engine = 'InnoDB';
                });

		/*
			INSERT INTO mpdtunes.stations_icons (filename, filepath, baseUrl, creator, created, modified) VALUES ('default_no_station_icon.jpg', '/var/www/mpdtunes.com/htdocs/images/', 'images/', 1, '2011-11-02 11:04:00', '2011-11-02 11:04:00');
		*/

                DB::table('stations_icons')->insert(

                        array(
                                'id' => '1',
                                'filename' => 'default_no_station_icon.jpg',
                                'filepath' => '/var/www/mpdtunes.com/htdocs/images/',
                                'baseurl' => 'images/',
                                'creator_id' => '1'
                        )
                );

                DB::table('stations_icons')->insert(

                        array(
                                'id' => '2',
                                'filename' => '5d6b5be66a6bd39e4bc283a0afeb5087.jpeg',
                                'filepath' => '/var/www/mpdtunes.com/htdocs/public/mpd/master/',
                                'baseurl' => 'mpd/master/',
                                'creator_id' => '1'
                        )
                );

                DB::table('stations_icons')->insert(

                        array(
                                'id' => '3',
                                'filename' => '542cb57e25187488aa2c523503f666c2.jpeg',
                                'filepath' => '/var/www/mpdtunes.com/htdocs/public/mpd/master/',
                                'baseurl' => 'mpd/master/',
                                'creator_id' => '1'
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
