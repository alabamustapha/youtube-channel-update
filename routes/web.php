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


  $spinner = new Spinner();

  //$string = '{Hey|Howdy|Hi there|Hi} {there|mate|bud|buddy}, {{how are|how\'re} {you|ya}|how you doin\'|how {you|ya} {feeling|hanging}|you doing {OK|alright}}?';


//  echo $spinner::process($string);

 update_video_tags(["one", "two"]);
});
