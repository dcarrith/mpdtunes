<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListenersTable extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                Schema::create('listeners', function(Blueprint $table)
                {
                        $table->increments('id');
                        $table->integer('station_id');
                        $table->string('session_id');
                        $table->string('name');
                        $table->boolean('connected');
                        $table->timestamps();
                });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                Schema::drop('listeners');
        }

}
