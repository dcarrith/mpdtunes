<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                /*
			CREATE TABLE IF NOT EXISTS mpdtunes.stations (
  				id int(11) NOT NULL AUTO_INCREMENT COMMENT 'the auto-incrementing primary key of the stations table',
  				name varchar(64) NOT NULL,
  				description varchar(128) DEFAULT NULL,
  				url varchar(255) NOT NULL,
  				url_hash varchar(128) NOT NULL,
  				icon_id int(11) DEFAULT 1,
  				owner int(11) DEFAULT NULL,
  				creator int(11) DEFAULT NULL,
  				created datetime DEFAULT NULL,
  				modified datetime DEFAULT NULL,
  				modified_by int(11) DEFAULT NULL,
  				visibility enum('public', 'private', 'shared') DEFAULT 'public',
  				PRIMARY KEY (id),
  				KEY ind_mpdtunes_stations_name (name),
  				KEY ind_mpdtunes_stations_url_hash (url_hash),
  				KEY ind_mpdtunes_stations_creator (creator)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
                */

                Schema::create('stations', function(Blueprint $table) {

                        $table->increments('id');
                        $table->string('name', 64)->index();
                        $table->string('description', 128);
                        $table->string('url', 255);
			$table->string('url_hash', 255)->index();
			$table->integer('icon_id')->default(1)->foreign()->references('id')->on('stations_icons');
			$table->integer('creator_id')->nullable()->default(NULL)->foreign()->references('id')->on('users');
			$table->timestamps();
			$table->enum('visibility', array('public', 'private', 'shared'))->default('public');			
                        $table->engine = 'InnoDB';
                });

                /*
			INSERT INTO stations (id, name, description, url, url_hash, icon_id, owner, creator, created) VALUES
(1, 'The Original MPDTunes OGG Radio Stream', 'This is the radio stream from the master admin of MPDTunes', 'http://www.mpdtunes.com:6601/mpd.ogg', '07680a4bd6a6e326aa21fda6f569d1adcaff03c6087e5aabefdae79f3585c2b5229bf09e8a63b8a095bae4e22f8df848ef59e5e360b31fffbbae8393afdc95ff', 1, 1, 1, '2013-03-25 10:12:14');
			INSERT INTO stations (id, name, description, url, url_hash, icon_id, owner, creator, created) VALUES
(2, 'NPR - 24 Hour Program Streaming', 'When NPR carries a news event live, this Program Stream will also carry the same live coverage.', 'http://nprdmp.ic.llnwd.net/stream/nprdmp_live01_mp3', '43e506d4ef1859c8926bde54b10600e1fa5d51e75f515c4c74a08bcf776eb03c053f9dcc190fc453b82441d86851350f7fe02ee013b63b3fc1a7fc55c5af3d2c', 1, NULL, 1, '2013-03-26 10:12:14');
                */

                DB::table('stations')->insert(

                        array(
                                'id' => '1',
                                'name' => 'The Original MPDTunes OGG Radio Stream',
                                'description' => 'This is the radio stream from the master admin of MPDTunes',
                                'url' => 'http://www.mpdtunes.com:6601/mpd.ogg',
				'url_hash' => '5172d9d94b27052561adac80cac32bc14b09ec19139aae8c27274edd5b15d8b961169cffafe93eff2841e8e1a22a2cfc7d946a5e89f0ae844ed66a0726cc4c07',
				'icon_id' => '1',
				'visibility' => 'public'
                        )
                );

                DB::table('stations')->insert(

                        array(
                                'id' => '2',
                                'name' => 'NPR - 24 Hour Program Streaming',
                                'description' => 'When NPR carries a news event live, this Program Stream will also carry the same live coverage.',
                                'url' => 'http://nprdmp.ic.llnwd.net/stream/nprdmp_live01_mp3',
                                'url_hash' => 'e13364982c889b13b54d0e7b60943b0b1c17e94804d929a1380c37a03a6ac62acd2f72b7dde37624b5c9b57a689e2855c45554c9a4c470bba0eb0710604e469f',
                                'icon_id' => '2',
				'creator_id' => '1',
                                'visibility' => 'public'
                        )
                );

                DB::table('stations')->insert(

                        array(
                                'id' => '3',
                                'name' => 'KPFA 94.1 FM',
                                'description' => 'KPFA pacifica radio',
                                'url' => 'http://streams1.kpfa.org:8000/kpfa_64',
                                'url_hash' => '53a7684f1ea21b89b92b0feb5a02e34dbfd177df7a24e193f64e279cc6a45b23c3b525d5ab544ad10c676443ef90ed4627eb38aa7949a2659e332a39da324432',
                                'icon_id' => '3',
				'creator_id' => '1',
                                'visibility' => 'public'
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
		Schema::drop('stations');
	}

}
