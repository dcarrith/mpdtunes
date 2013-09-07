<?php

use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
			CREATE TABLE IF NOT EXISTS mpdtunes.themes (
  				id int(11) NOT NULL AUTO_INCREMENT COMMENT 'the auto-incrementing primary key of the themes table',
  				bars varchar(3) DEFAULT 'a',
  				buttons varchar(3) DEFAULT 'a',
  				body varchar(3) DEFAULT 'a',
  				controls varchar(3) DEFAULT 'a',
  				actions varchar(3) DEFAULT 'g',
  				active varchar(3) DEFAULT 'g',
  				name varchar(48) DEFAULT 'Custom',
  				creator_id int(11) DEFAULT 1,
  				created datetime DEFAULT NULL,
  				modified datetime DEFAULT NULL,
  				PRIMARY KEY (id),
  				KEY ind_mpdtunes_themes_name (name),
  				KEY ind_mpdtunes_themes_creator_id (creator_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/		

                Schema::create('themes', function($table) {

                        $table->increments('id');
                        $table->string('bars', 3)->default('a');
                        $table->string('buttons', 3)->default('a');
                        $table->string('body', 3)->default('a');
                        $table->string('controls', 3)->default('a');
                        $table->string('actions', 3)->default('g');
			$table->string('active', 3)->default('g');
			$table->string('name', 48)->default('Custom');
			$table->integer('creator_id')->default(1)->foreign()->references('id')->on('users');
                        $table->timestamps();
                        $table->engine = 'InnoDB';
                });

		/*
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (1, 'a', 'a', 'a', 'a', 'g', 'g', 'Charcoal and Green', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (2, 'b', 'b', 'b', 'b', 'a', 'a', 'Greyish Blue and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (3, 'c', 'c', 'c', 'c', 'a', 'a', 'White and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (4, 'd', 'd', 'd', 'd', 'a', 'a', 'Light Grey and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (5, 'e', 'e', 'e', 'e', 'a', 'a', 'Yellow and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (6, 'g', 'g', 'g', 'g', 'a', 'a', 'True Green and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (7, 'h', 'h', 'h', 'h', 'a', 'a', 'Hunter Green and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (8, 'i', 'i', 'i', 'i', 'a', 'a', 'Celadon Green and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (9, 'j', 'j', 'j', 'j', 'a', 'a', 'True Blue and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (10, 'k', 'k', 'k', 'k', 'a', 'a', 'Skyline Blue and Charcoal', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (11, 'l', 'l', 'l', 'l', 'f', 'f', 'Greyish Blue and Black', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
			INSERT INTO mpdtunes.themes (id, bars, buttons, body, controls, actions, active, name, creator_id, created, modified) VALUES (12, 'f', 'f', 'f', 'f', 'k', 'k', 'Black and Skyline Blue', 1, '2011-10-20 12:44:00', '2011-10-20 12:44:00');
		*/
                DB::table('themes')->insert(

                        array(
                                'id' => '1',
                                'bars' => 'a',
                                'buttons' => 'a',
                            	'body' => 'a',
				'controls' => 'a',
				'actions' => 'g',
				'active' => 'g',
				'name' => 'Charcoal and Green',
				'creator_id' => '1'
                        )
                );

		DB::table('themes')->insert(

                        array(
                                'id' => '2',
                                'bars' => 'b',
                                'buttons' => 'b',
                                'body' => 'b',
                                'controls' => 'b',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Greyish Blue and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '3',
                                'bars' => 'c',
                                'buttons' => 'c',
                                'body' => 'c',
                                'controls' => 'c',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'White and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '4',
                                'bars' => 'd',
                                'buttons' => 'd',
                                'body' => 'd',
                                'controls' => 'd',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Light Grey and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '5',
                                'bars' => 'e',
                                'buttons' => 'e',
                                'body' => 'e',
                                'controls' => 'e',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Yellow and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '6',
                                'bars' => 'g',
                                'buttons' => 'g',
                                'body' => 'g',
                                'controls' => 'g',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'True Green and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '7',
                                'bars' => 'h',
                                'buttons' => 'h',
                                'body' => 'h',
                                'controls' => 'h',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Hunter Green and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '8',
                                'bars' => 'i',
                                'buttons' => 'i',
                                'body' => 'i',
                                'controls' => 'i',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Celadon Green and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '9',
                                'bars' => 'j',
                                'buttons' => 'j',
                                'body' => 'j',
                                'controls' => 'j',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'True Blue and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '10',
                                'bars' => 'k',
                                'buttons' => 'k',
                                'body' => 'k',
                                'controls' => 'k',
                                'actions' => 'a',
                                'active' => 'a',
                                'name' => 'Skyline Blue and Charcoal', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '11',
                                'bars' => 'l',
                                'buttons' => 'l',
                                'body' => 'l',
                                'controls' => 'l',
                                'actions' => 'f',
                                'active' => 'f',
                                'name' => 'Greyish Blue and Black', 
                                'creator_id' => '1'
                        )
                );

                DB::table('themes')->insert(

                        array(
                                'id' => '12',
                                'bars' => 'f',
                                'buttons' => 'f',
                                'body' => 'f',
                                'controls' => 'f',
                                'actions' => 'k',
                                'active' => 'k',
                                'name' => 'Black and Skyline Blue', 
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
