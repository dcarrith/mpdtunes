<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
			CREATE TABLE IF NOT EXISTS mpdtunes.languages (
  				id int(4) NOT NULL AUTO_INCREMENT COMMENT 'the primary key of the languages table',
  				code varchar(32) NOT NULL,
  				name varchar(32) NOT NULL,
  				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/

                Schema::create('languages', function(Blueprint $table) {

                        $table->increments('id');
                        $table->string('code', 32)->nullable()->default(null);
                        $table->string('name', 32)->nullable()->default(null);
                        $table->engine = 'InnoDB';
                });      
	
		/*
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (1, 'en', 'English');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (4, 'es', 'Spanish');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (5, 'fr', 'French');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (6, 'hi', 'Hindi');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (7, 'te', 'Telugu');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (8, 'ja', 'Japanese');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (9, 'nl', 'Dutch');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (11, 'ru', 'Russian');
			INSERT INTO mpdtunes.languages (id, code, name) VALUES (12, 'it', 'Italian');
		*/

		// Insert the default set of languages
                DB::table('languages')->insert(

                        array(
                                'id' => '1',
                                'code' => 'en',
                                'name' => 'English',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '2',
                                'code' => 'es',
                                'name' => 'Spanish',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '3',
                                'code' => 'fr',
                                'name' => 'French',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '4',
                                'code' => 'hi',
                                'name' => 'Hindi',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '5',
                                'code' => 'te',
                                'name' => 'Telugu',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '6',
                                'code' => 'ja',
                                'name' => 'Japanese',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '7',
                                'code' => 'nl',
                                'name' => 'Dutch',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '8',
                                'code' => 'ru',
                                'name' => 'Russian',
                        )
                );

                DB::table('languages')->insert(

                        array(
                                'id' => '9',
                                'code' => 'it',
                                'name' => 'Italian',
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
		Schema::drop('languages');
	}

}
