<?php

use Illuminate\Database\Migrations\Migration;

class CreateThemesColorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                /*
			CREATE TABLE IF NOT EXISTS mpdtunes.themes_colors (
  				letter_code varchar(3) NOT NULL,
  				name varchar(32) NOT NULL,
  				PRIMARY KEY (letter_code),
  				KEY ind_mpdtunes_theme_colors_name (name)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/                

		Schema::create('themes_colors', function($table) {

                        $table->string('letter_code', 3)->primary();
                        $table->string('name', 32)->nullable()->default(null);
                        $table->engine = 'InnoDB';
                });

                /*
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('a', 'Charcoal Grey');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('b', 'Grey and Baby Blue');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('c', 'White and Silverish Grey');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('d', 'White and Grey');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('e', 'Yellow and White');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('f', 'Black and Dark Grey');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('g', 'True Green');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('h', 'Hunter Green');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('i', 'Celedon Green');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('j', 'True Blue');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('k', 'Skyline Blue');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('l', 'Greyish Blue');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('m', 'Midnight Blue');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('n', 'Neon Reddish-Orange');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('o', 'Orange');
			INSERT INTO mpdtunes.themes_colors (letter_code, name) VALUES ('p', 'Platinum');
                */

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'a',
                                'name' => 'Charcoal Grey'
                        )
                );

		DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'b',
                                'name' => 'Grey and Baby Blue'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'c',
                                'name' => 'White and Silverish Grey'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'd',
                                'name' => 'White and Grey'
                        )       
                );

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'e',
                                'name' => 'Yellow and White'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'f',
                                'name' => 'Black and Dark Grey'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'g',
                                'name' => 'True Green'
                        )       
                );

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'h',
                                'name' => 'Hunter Green'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'i',
                                'name' => 'Celedon Green'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'j',
                                'name' => 'True Blue'
                        )       
                );

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'k',
                                'name' => 'Skyline Blue'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'l',
                                'name' => 'Greyish Blue'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'm',
                                'name' => 'Midnight Blue'
                        )       
                );

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'n',
                                'name' => 'Neon Reddish-Orange'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'o',
                                'name' => 'Orange'
                        )       
                );  

                DB::table('themes_colors')->insert(

                        array(
                                'letter_code' => 'p',
                                'name' => 'Platinum'
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
