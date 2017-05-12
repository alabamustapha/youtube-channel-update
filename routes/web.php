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

Route::get('/', function () {

    $access_tokens = App\AccessToken::all();

 
     return view("welcome");
});

Route::post('/', 'AppController@updateChannelVideo');


Route::get('getAccessToken', 'AppController@getAccessToken');

Route::post('getAccessToken/{id}', 'AppController@updateAccessToken');

Route::post('updateChannelsTable', 'AppController@updateChannelsTable');

Route::get('getAccessToken/oauth2callback', 'AppController@saveToken');