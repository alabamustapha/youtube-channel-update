<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//use Spinner;

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
  Route::get('/', function () {

      $access_tokens = App\AccessToken::all();

      $directories = File::directories('thumbnails');

      return view("welcome", compact('directories'));
  });

  Route::post('/', 'AppController@updateChannelVideo');


  Route::get('getAccessToken', 'AppController@getAccessToken');

  Route::get('manageThumbnails', 'AppController@getThumbnailsFolder');

  Route::post('deleteThumbnailsFolder', 'AppController@deleteThumbnailsFolder');

  Route::get('AuthorizedChannels', 'AppController@AuthorizedChannels');

  Route::post('getAccessToken/{id}', 'AppController@updateAccessToken');

  Route::post('revokeToken/{id}', 'AppController@revokeToken');

  Route::post('updateChannelsTable', 'AppController@updateChannelsTable');

  Route::get('getAccessToken/oauth2callback', 'AppController@saveToken');

  Route::post('createThumbnailsFolder', 'AppController@CreateThumbnailsFolder');


  Route::get('/home', 'HomeController@index');

});
