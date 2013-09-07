<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        	/*
                	CREATE TABLE IF NOT EXISTS mpdtunes.users_configs (
                	        user_id int(11) NOT NULL COMMENT 'the foreign key of the primary key to the users table',
                	        config blob NOT NULL,
                	        PRIMARY KEY (user_id)
                	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        	*/

                Schema::create('users_configs', function($table) {

                        $table->integer('user_id');
                        $table->binary('config');
			//$table->timestamps();
                        $table->engine = 'InnoDB';	
			$table->primary('user_id');
			//$table->foreign('user_id')->references('id')->on('users');
                });

		/*
			INSERT INTO musxc.users_configs (user_id, config) VALUES (1,'eyJpdiI6Imd0TWplZWFwTnpzU283a3RJbzluM2tkYzZpcjdhdFVcLzBISWptTXZhUXJ3PSIsInZhbHVlIjoiNDM5dXAxbzg3eGcxSXBXWTNrb1VyTUwwb2tJbE84QU53XC9hM1pLeHVjZmRWcXJaeGxrUllYSXdZaUM3SUR6a2NiSENZSTFpWUV5VytTd1dhbEJrWFVleFhNK3ZDOXJpMnExXC95NzFsZDNaNUhJTWNoN2V0YmpcLzMrWDFoTTBSY0g3Z1hjWEdjTXo5TWoxMkpsNTJ6U3htS3dpbkFFN0NqbWN6cjNUZUFJdGNsMHNzcFZYZmpFT0hQdExIQ21PZHRxTkE1UDVVTTBGSGdOSEVSVm94VEd2eUw1ajM3NStYWEVWTXZuT1Z4MmJqa3NmUGNIMytzUkkycjNwZDI1QlZuK1dtUXNwaWdBYThaYUNySDRGWTZKT2h2OFNaM0JEa0VFYWx5YUlOXC9pc3djSzNYbVpmZnpYeCtvaXhUVCtNS0tCQjdodk5Gd2d0MkdWdUVyQVRZRWZvc0Z4WlVKbERHSE95Vml3ekNuVk1PYXpVOGVuQkJXXC9EU1N2WmM4ZlhPMHdSd1N4OWZcL2dZTzJmZEJnZ0VYZG1yaHpWSTgwbUZaZVQ3QXNhQzZUUDJEVlRRZjlEZHhWaXNralJtMWFmdjhweDVqMk5kMU9sNnNMUnNKZkVQZmZNVFE9PSIsIm1hYyI6ImMzOTJlMjI4OWY3Yjk2ZTY3NmVmNjg3MTQ2ZGY4NDBiMjU5YWQyYWY0NGVjNzhmMjQzYzc3NDQzNjVhN2ZkNTUifQ==');
		*/

                DB::table('users_configs')->insert(

                        array(
                                'user_id' => '1',
                                'config' => 'eyJpdiI6Imd0TWplZWFwTnpzU283a3RJbzluM2tkYzZpcjdhdFVcLzBISWptTXZhUXJ3PSIsInZhbHVlIjoiNDM5dXAxbzg3eGcxSXBXWTNrb1VyTUwwb2tJbE84QU53XC9hM1pLeHVjZmRWcXJaeGxrUllYSXdZaUM3SUR6a2NiSENZSTFpWUV5VytTd1dhbEJrWFVleFhNK3ZDOXJpMnExXC95NzFsZDNaNUhJTWNoN2V0YmpcLzMrWDFoTTBSY0g3Z1hjWEdjTXo5TWoxMkpsNTJ6U3htS3dpbkFFN0NqbWN6cjNUZUFJdGNsMHNzcFZYZmpFT0hQdExIQ21PZHRxTkE1UDVVTTBGSGdOSEVSVm94VEd2eUw1ajM3NStYWEVWTXZuT1Z4MmJqa3NmUGNIMytzUkkycjNwZDI1QlZuK1dtUXNwaWdBYThaYUNySDRGWTZKT2h2OFNaM0JEa0VFYWx5YUlOXC9pc3djSzNYbVpmZnpYeCtvaXhUVCtNS0tCQjdodk5Gd2d0MkdWdUVyQVRZRWZvc0Z4WlVKbERHSE95Vml3ekNuVk1PYXpVOGVuQkJXXC9EU1N2WmM4ZlhPMHdSd1N4OWZcL2dZTzJmZEJnZ0VYZG1yaHpWSTgwbUZaZVQ3QXNhQzZUUDJEVlRRZjlEZHhWaXNralJtMWFmdjhweDVqMk5kMU9sNnNMUnNKZkVQZmZNVFE9PSIsIm1hYyI6ImMzOTJlMjI4OWY3Yjk2ZTY3NmVmNjg3MTQ2ZGY4NDBiMjU5YWQyYWY0NGVjNzhmMjQzYzc3NDQzNjVhN2ZkNTUifQ=='
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
