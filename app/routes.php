<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Auto detect all controllers
//Route::controller(Controller::detect());

// This groups all routes that do not require authentication
Route::group(array(), function() {

	Route::get('login', array('uses' => 'LoginController@getLogin'));

        // This will route requests to www.domain.com/login to the Login controller's postIndex action
        Route::post('login', array('as' => 'login', 'uses' => 'LoginController@postLogin'));

        Route::get('register', array('uses' => 'RegisterController@getIndex'));

        // This will route requests to www.domain.com/login to the Login controller's postIndex action
        Route::post('register', array('as' => 'register', 'uses' => 'RegisterController@postIndex'));

	// This will route requests to the registration success page
	Route::get('registration/success', array('uses' => 'RegisterController@success'));
});


// This groups all routes that require authentication
Route::group(array('before' => 'auth'), function() {
	
	// This will route requests to www.domain.com to the Home controller's index action
	Route::get('', array('as' => '', 'uses' => 'HomeController@index'));

    	// This will route requests to www.domain.com/home to the Home controller's index action
	Route::get('home', array('as' => 'home', 'uses' => 'HomeController@index'));

        // This will route requests to www.domain.com/queue to the Queue controller's index action
        Route::get('queue', array('as' => 'queue', 'uses' => 'QueueController@index'));
	
        // This will route requests to www.domain.com/queue/more to the Queue controller's more action
        Route::post('queue/more', array('uses' => 'QueueController@more'));

        // This will route requests to www.domain.com/queue to the Queue controller's index action
        Route::get('artists', array('as' => 'artists', 'uses' => 'ArtistsController@index'));

        // This will route requests to www.domain.com/artists/more to the Artist controller's more action
        Route::post('artists/more', array('uses' => 'ArtistsController@more'));

        // This will route requests to www.domain.com/genres to the Genres controller's index action
        Route::get('genres', array('as' => 'genres', 'uses' => 'GenresController@index'));

        // This will route requests to www.domain.com/playlists to the Playlists controller's index action
        Route::get('playlists', array('as' => 'playlists', 'uses' => 'PlaylistsController@index'));

	Route::get('playlist/create', array('uses' => 'PlaylistsController@create'));

	Route::post('playlist/save', array('uses' => 'PlaylistsController@postCreate'));

        // This will route requests to www.domain.com/stations to the Stations controller's index action
        Route::get('stations', array('as' => 'stations', 'uses' => 'StationsController@index'));

        // This will route requests to www.domain.com/genre/somegenre/artists to the Artists controller's index action
        Route::get('genre/{genrename}/artists', 'ArtistsController@index')->where('genrename', '.*');

        // This will route requests to www.domain.com/artists/someartist/albums to the Album controller's index action
        //Route::get('artist/(:any)/albums', array('as' => 'albums', 'uses' => 'AlbumsController@index'));

        // This will route requests to www.domain.com/artists/someartist/albums to the Album controller's index action
        Route::get('artist/{artistname}/albums', 'AlbumsController@index')->where('artistname', '.*');

        // This will route requests to www.domain.com/queue to the Album controller's more action
        Route::post('albums/more', array('uses' => 'AlbumsController@more'));

	// This will route requests to www.domain.com//artist/someartist/album/somealbum/tracks to the Tracks controller's index action
        Route::get('artist/{artistname}/album/{albumname}/tracks', 'AlbumTracksController@index')->where('artistname', '.*')->where('albumname', '.*');

        // This will route requests to www.domain.com/playlist/someplaylist/tracks to the PlaylistTracks controller's index action
        Route::get('playlist/{playlistname}/tracks', 'PlaylistTracksController@index')->where('playlistname', '.*');

	// This will route requests to www.domain.com/playlist/someplaylist/tracks/more to the PlaylistTracks controller's more action
	Route::post('playlist/tracks/more', 'PlaylistTracksController@more');

        // This will route requests to www.domain.com/stations/add to the Stations controller's getStation action
        Route::get('stations/add', array('uses' => 'StationsController@getStation'));

        // This will route requests to www.domain.com/stations/add to the Stations controller's postStation action
        Route::post('stations/add', array('uses' => 'StationsController@postStation'));

        // This will route requests to www.domain.com/stations/edit/id to the Stations controller's getStation action
        Route::get('stations/edit/{stationid}', array('uses' => 'StationsController@getStation'))->where('stationid', '[0-9]+');

        // This will route requests to www.domain.com/stations/edit/id to the Stations controller's postStation action
        Route::post('stations/edit/{stationid}', array('uses' => 'StationsController@postStation'))->where('stationid', '[0-9]+');

	Route::post('stations/delete', array('uses' => 'StationsController@delete'));

	Route::post('user/{userid}/delete', array('uses' => 'AdminController@delete'))->where('userid', '[0-9]+');

	// GET /foo/a123/asdf
	// GET /foo/z123/asdf
	// GEt /foo/df123/asdkfjsd
	/*Route::get('artist/{name}/albums', function($name)
	{
		return "Hello ".$name."!";
	})->where('name', '.*');  // Anything*/

	//Route::options('home/clearSession', array('as' => 'clearSession', 'uses' => 'Home@clearSession'));

	//Route::options('home/queryMpd', array('as' => 'queryMpd', 'uses' => 'Home@queryMpd'));

	Route::post('settings/save/{what}', array('uses' => 'SettingsController@save'))->where('what', '.*');
	
	Route::get('settings/custom/{what}', array('uses' => 'SettingsController@custom'))->where('what', '.*');

	Route::post('settings/create/{what}', array('uses' => 'SettingsController@create'))->where('what', '.*');
	
	Route::get('settings/volume', array('uses' => 'SettingsController@volume'));

	Route::get('settings/apply', array('uses' => 'SettingsController@apply'));

        Route::post('session/clear', array('uses' => 'SessionController@clear'));

	Route::get('uploader', array('as' => 'uploader', 'uses' => 'UploadsController@index'));

	Route::post('upload/music', array('uses' => 'UploadsController@uploadMusic'));

	Route::post('upload/station/icon', array('uses' => 'UploadsController@uploadStationsIcon'));

	Route::get('admin/account', array('uses' => 'AdminController@getAccount'));

	Route::post('admin/account', array('uses' => 'AdminController@postAccount'));

	Route::get('admin', array('uses' => 'AdminController@index'));

	Route::get('admin/payments', array('uses' => 'AdminController@getPayments'));
	
	Route::post('admin/payments', array('uses' => 'AdminController@postPayments'));

	Route::get('admin/users', array('uses' => 'AdminController@users'));

	Route::get('admin/edit/user/{userid}', array('uses' => 'AdminController@getUser'))->where('userid', '.*');

	Route::post('admin/edit/user/{userid}', array('uses' => 'AdminController@postUser'))->where('userid', '.*');

	Route::post('musicpd/playlist/{operation}', array('uses' => 'MPDController@playlist'))->where('operation', '.*');

	Route::post('musicpd/playlist/{operation}/{what}', array('uses' => 'MPDController@playlist'))->where('operation', '.*')->where('what', '.*');

	Route::post('musicpd/control/{operation}', array('uses' => 'MPDController@control'))->where('operation', '.*');

        Route::post('musicpd/query', array('uses' => 'MPDController@query'));

	Route::get('{section}/confirm_delete', array('uses' => 'MPDTunesController@confirmDelete'))->where('section', 'admin|playlist|station');
});
