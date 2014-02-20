<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalConfigsTable extends Migration {
        
	/**
	* Run the migtrations
	*
	* @return void
	*/
	public function up()
	{

		/*
                        CREATE TABLE IF NOT EXISTS mpdtunes.paypal_configs (
                                user_id int(11) NOT NULL COMMENT 'the foreign key of the primary key to the users table',
                                config blob NOT NULL,
                                PRIMARY KEY (user_id)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                */

                Schema::create('paypal_configs', function(Blueprint $table) {

                        $table->integer('user_id');
                        $table->binary('config');
                        //$table->timestamps();
                        $table->engine = 'InnoDB';
                        $table->primary('user_id');
                        //$table->foreign('user_id')->references('id')->on('users');
                });

                /*
                        INSERT INTO musxc.paypal_configs (user_id, config) VALUES (1,'eyJpdiI6InZpVzhYK2RxRit0ZHpneDcrcGp5VTl2dlhQY1pwUnpDT1hPTFN3c3FLc2s9IiwidmFsdWUiOiJINjZ1eFNVcThSazFMRVp2ZVd1UzhWOXpORG1vcW9cLzc0MHRTaVNCTjRtRmU4NkRrcDQ5YXh3REU2bjBMTHlQNnBXNXFBNWptOVpSU3hDKzBVN2hESWxsOVZFV1dTbE44NllLenJURG1TeFNFQTVSd0NyRGZCdjBya1d5TXFTcXFJQVN3SFlvaVJ1TmFqSVVGZXZxQ1FqMFwvdE12anlUMStldTlvXC9RYXNmVkFRbVY5Y045bEpKbDg3Qmk5ZnVFeTZtUmVrUlgzRm9UVnVXcURsWUlLWWRGdU1aK2tOeDRTUWpPb29TRGJScTYzS0Y2K1dtUDErajVwdXJ3cGwxZ1lxSkdqdzlqaTRNd2ROcnc5Q1VibENodWhaeDJXSXlFTktCV29PK1wvMFNiVXNRT2paQWt5SkN0Qk53Z1VIc1plXC93VjhVZFBEeTBicTRpb3F5ZDFiaGk5U3hYVkJaXC9GR2hxMVA4bVRkUnBIR3NpaEQ0WENEMkMzU1A5eGNsQUtZbklUQThxUTliT1lDblFMRWk3c1wvdnN3WlB0MEtwODFwXC9kSlk5b1RvdDRISkFoeXgwYndEa2wwalJtOThySXJaYmdNM085RjNLYlF3RmpudThlQnlJY0xDeCtQSHpac2hrMHBzbmVWTVJQZHVDRTh5QTkyaWdCdGNFQzNjak5JR2Myb1NNR29RKzBxdDlEK1ZMb21WZlVLZTVXNVVTRStmOUhnTk81aTBwall2T0diNkJlVEVJSVg0SWdCem1sVTlVelVzdVd3aTZXaVBhT0ZqcHlPQlwvUEZUcEt6XC9BVm9wTTMyRjN0blVJb3VcLzJkZmJ1V2xjS0NtMFpmYWJtZEx2TXVvVHVLbm9xWnN6a2JEcFhTbFZLRXJndXZFc1lVZ2VZMWlZYXdrZjNSN2RSSUEzbGY0KzFcL2Z4Um9Dd1NoY2krd0V6WTQ1U1JzVjVMMWdoSjhKRFQxYXc9PSIsIm1hYyI6IjQ5OTc5MjQ0NTZkNzg3NWY4ZWI3NDViOWQwZDViN2U0Y2I1M2EzYmQ2ZjkxOGZjZTVjNWQyOTU4YjEzY2E4MzIifQ==');
                */

                DB::table('paypal_configs')->insert(

                        array(
                                'user_id' => '1',
                                'config' => 'eyJpdiI6InZpVzhYK2RxRit0ZHpneDcrcGp5VTl2dlhQY1pwUnpDT1hPTFN3c3FLc2s9IiwidmFsdWUiOiJINjZ1eFNVcThSazFMRVp2ZVd1UzhWOXpORG1vcW9cLzc0MHRTaVNCTjRtRmU4NkRrcDQ5YXh3REU2bjBMTHlQNnBXNXFBNWptOVpSU3hDKzBVN2hESWxsOVZFV1dTbE44NllLenJURG1TeFNFQTVSd0NyRGZCdjBya1d5TXFTcXFJQVN3SFlvaVJ1TmFqSVVGZXZxQ1FqMFwvdE12anlUMStldTlvXC9RYXNmVkFRbVY5Y045bEpKbDg3Qmk5ZnVFeTZtUmVrUlgzRm9UVnVXcURsWUlLWWRGdU1aK2tOeDRTUWpPb29TRGJScTYzS0Y2K1dtUDErajVwdXJ3cGwxZ1lxSkdqdzlqaTRNd2ROcnc5Q1VibENodWhaeDJXSXlFTktCV29PK1wvMFNiVXNRT2paQWt5SkN0Qk53Z1VIc1plXC93VjhVZFBEeTBicTRpb3F5ZDFiaGk5U3hYVkJaXC9GR2hxMVA4bVRkUnBIR3NpaEQ0WENEMkMzU1A5eGNsQUtZbklUQThxUTliT1lDblFMRWk3c1wvdnN3WlB0MEtwODFwXC9kSlk5b1RvdDRISkFoeXgwYndEa2wwalJtOThySXJaYmdNM085RjNLYlF3RmpudThlQnlJY0xDeCtQSHpac2hrMHBzbmVWTVJQZHVDRTh5QTkyaWdCdGNFQzNjak5JR2Myb1NNR29RKzBxdDlEK1ZMb21WZlVLZTVXNVVTRStmOUhnTk81aTBwall2T0diNkJlVEVJSVg0SWdCem1sVTlVelVzdVd3aTZXaVBhT0ZqcHlPQlwvUEZUcEt6XC9BVm9wTTMyRjN0blVJb3VcLzJkZmJ1V2xjS0NtMFpmYWJtZEx2TXVvVHVLbm9xWnN6a2JEcFhTbFZLRXJndXZFc1lVZ2VZMWlZYXdrZjNSN2RSSUEzbGY0KzFcL2Z4Um9Dd1NoY2krd0V6WTQ1U1JzVjVMMWdoSjhKRFQxYXc9PSIsIm1hYyI6IjQ5OTc5MjQ0NTZkNzg3NWY4ZWI3NDViOWQwZDViN2U0Y2I1M2EzYmQ2ZjkxOGZjZTVjNWQyOTU4YjEzY2E4MzIifQ=='
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
		Schema::drop('paypal_configs');	
	}
}
