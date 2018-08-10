<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get_adverts','Api\AdvertController@index');
Route::post('create_advert','Api\AdvertController@create');
Route::post('delete_advert','Api\AdvertController@delete');

Route::get('get_countries','Api\CountryController@index');
Route::post('create_country','Api\CountryController@create');
Route::post('delete_country','Api\CountryController@delete');

Route::get('get_events','Api\EventController@index');
Route::post('create_event','Api\EventController@create');
Route::post('delete_event','Api\EventController@delete');

Route::post('get_notifications','Api\NotificationController@index');

Route::get('get_posts','Api\PostController@index');
Route::post('store_post','Api\PostController@store');
Route::post('delete_post','Api\PostController@delete');
Route::post('like_post','Api\PostController@like');
Route::post('abuse','Api\PostController@abuse');

Route::get('get_venues','Api\VenueController@index');
Route::post('create_venue','Api\VenueController@create');
Route::post('delete_venue','Api\VenueController@delete');
