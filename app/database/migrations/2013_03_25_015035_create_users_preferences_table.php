<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                /*
			CREATE TABLE IF NOT EXISTS mpdtunes.users_preferences (
  				user_id int(11) NOT NULL COMMENT 'the primary key of the users preferences table and foreign key into users table',
  				theme_id int(2) DEFAULT 1,
  				mode enum('streaming', 'remote-control', 'disc-jockey') DEFAULT 'streaming',
  				crossfade int(2) DEFAULT 5,
  				volume_fade int(2) DEFAULT 5,
  				language_id int(4) DEFAULT 1,
  				created datetime DEFAULT NULL,
  				modified datetime DEFAULT NULL,
  				PRIMARY KEY (user_id),
  				KEY ind_mpdtunes_users_preferences_theme_id (theme_id),
  				KEY ind_mpdtunes_users_preferences_language_id (language_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
                */

                Schema::create('users_preferences', function($table) {

                        $table->integer('user_id')->foreign()->references('id')->on('users');
			$table->integer('theme_id')->default(1)->foreign()->references('id')->on('themes');
                        $table->enum('mode', array('streaming', 'remote-control', 'disc-jockey'))->default('streaming');
                        $table->integer('crossfade')->default(5);
                        $table->integer('volume_fade')->default(5);
                        $table->integer('language_id')->default(1)->foreign()->references('id')->on('languages');
                        $table->timestamps();
                        $table->engine = 'InnoDB';
                        $table->primary('user_id');
                });

                DB::table('users_preferences')->insert(

                        array(
                                'user_id' => '1',
                                'theme_id' => '1',
				'mode' => 'streaming',
				'crossfade' => '0',
				'volume_fade' => '0',
				'language_id' => '1'
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
