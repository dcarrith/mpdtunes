<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
			CREATE TABLE IF NOT EXISTS mpdtunes.roles (
  				id int(2) NOT NULL AUTO_INCREMENT COMMENT 'the auto-incrementing primary key of the roles table',
  				name varchar(64) DEFAULT NULL,
  				level int(3) DEFAULT 0,
  				active tinyint(1) DEFAULT 1,
  				PRIMARY KEY (id),
 	 			KEY ind_mpdtunes_roles_level (level)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		*/
                Schema::create('roles', function($table) {

                        $table->increments('id');
                        $table->string('name', 24)->nullable()->default(null);
                        $table->integer('level')->default(0)->index();    
                        $table->tinyInteger('active')->default(1)->index();
                        $table->engine = 'InnoDB';
                });

		/*
			INSERT INTO mpdtunes.roles (name, level, active) VALUES ('Master', 99, 1);
			INSERT INTO mpdtunes.roles (name, level, active) VALUES ('Admin', 50, 1);
			INSERT INTO mpdtunes.roles (name, level, active) VALUES ('User', 20, 1);
			INSERT INTO mpdtunes.roles (name, level, active) VALUES ('Guest', 1, 1);
		*/

                DB::table('roles')->insert(

                        array(
                                'id' => '1',
                                'name' => 'Master',
                                'level' => '99',
                                'active' => 1
                        )
		);

		DB::table('roles')->insert(

                        array(
                                'id' => '2',
                                'name' => 'Admin',
                                'level' => '50',
                                'active' => 1
                        )
		);
	
		DB::table('roles')->insert(

                        array(
                                'id' => '3',
                                'name' => 'User',
                                'level' => '20',
                                'active' => 1
                        )
		);

		DB::table('roles')->insert(

                        array(
                                'id' => '4',
                                'name' => 'Guest',
                                'level' => '1',
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
