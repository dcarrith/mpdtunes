<?php

use Illuminate\Database\Schema\Blueprint;
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

                Schema::create('users_configs', function(Blueprint $table) {

                        $table->integer('user_id');
                        $table->binary('config');
			//$table->timestamps();
                        $table->engine = 'InnoDB';	
			$table->primary('user_id');
			//$table->foreign('user_id')->references('id')->on('users');
                });

		/*
			INSERT INTO musxc.users_configs (user_id, config) VALUES (1,'eyJpdiI6IkUreFBzb2xZa2dYRkpHT2tOeUczUmRPVWR1SGRJSndjUU9STkw4Ukp1QUk9IiwidmFsdWUiOiJDWkF5Y1NFUHg3Y2hhS0lia2x2dlwvd09RTVZSRUZvSDZ2WFAza3dWT2RlM2xJKzhHQnJUaXpUaHZCSVZHSDQyWnM2OXJ4elE3TTRaRjJpelh2RStwR2FmOHhcL1Z6a2x1YWFqNlZMNFU3cjFvRURod3ZleDl1MEg4K21KVDN0WDdKNWRwWTVTRGFZdlpZM1QrMld0VG1jbjhETFhVTmdUTDFpNklERURUajVTa1VuSTdrMXVWMGZqeWpJQlQ5Y1hOYVl0ZTNJaEo1Smo4eW5vZngxdjh0c3dGM211Q1JiQUlBWnZrdlhZVXQ2ekk2Z2g4SlFHRkRyaDB1MzB1RXQ0YzBQOEdCVWQyZDdSM01ZVzJ2aDJTTk9YVkR1MjV1bVdSZnZXZTdmOFhDZEJndWxoWXhFZmw4NnlcL1ZPNGNjVHZsVEFXN25ES0NFTWdMOTV0QktUajdSbE5WWllLWUwySzJSazIwakRTbit1MkM1cndQSFBReDBSOXg0MGlKYnBHVUFhXC9LMkc2aVhUVHhielwvRU9DcjBHNE53OThlK1wvZEtjSDBOQ05KSytrZHREUWhabEVCZ1wvczRXVloybGk2QW9DKzlZSkVcL1R0bDJWZWZRNGFIYkxQQ2R3PT0iLCJtYWMiOiI5MzQxODUzODMzZmJmMjRiZjE5MzJiNmNkNzcxYzk1NGFmOTgwNmY5Yzk4ZTY0YjE2YWRiZGI2ZTUxNDcxM2E3In0=');

		*/

                DB::table('users_configs')->insert(

                        array(
                                'user_id' => '1',
                                'config' => 'eyJpdiI6IkUreFBzb2xZa2dYRkpHT2tOeUczUmRPVWR1SGRJSndjUU9STkw4Ukp1QUk9IiwidmFsdWUiOiJDWkF5Y1NFUHg3Y2hhS0lia2x2dlwvd09RTVZSRUZvSDZ2WFAza3dWT2RlM2xJKzhHQnJUaXpUaHZCSVZHSDQyWnM2OXJ4elE3TTRaRjJpelh2RStwR2FmOHhcL1Z6a2x1YWFqNlZMNFU3cjFvRURod3ZleDl1MEg4K21KVDN0WDdKNWRwWTVTRGFZdlpZM1QrMld0VG1jbjhETFhVTmdUTDFpNklERURUajVTa1VuSTdrMXVWMGZqeWpJQlQ5Y1hOYVl0ZTNJaEo1Smo4eW5vZngxdjh0c3dGM211Q1JiQUlBWnZrdlhZVXQ2ekk2Z2g4SlFHRkRyaDB1MzB1RXQ0YzBQOEdCVWQyZDdSM01ZVzJ2aDJTTk9YVkR1MjV1bVdSZnZXZTdmOFhDZEJndWxoWXhFZmw4NnlcL1ZPNGNjVHZsVEFXN25ES0NFTWdMOTV0QktUajdSbE5WWllLWUwySzJSazIwakRTbit1MkM1cndQSFBReDBSOXg0MGlKYnBHVUFhXC9LMkc2aVhUVHhielwvRU9DcjBHNE53OThlK1wvZEtjSDBOQ05KSytrZHREUWhabEVCZ1wvczRXVloybGk2QW9DKzlZSkVcL1R0bDJWZWZRNGFIYkxQQ2R3PT0iLCJtYWMiOiI5MzQxODUzODMzZmJmMjRiZjE5MzJiNmNkNzcxYzk1NGFmOTgwNmY5Yzk4ZTY0YjE2YWRiZGI2ZTUxNDcxM2E3In0='

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
		Schema::drop('users_configs');
	}

}
