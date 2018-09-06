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

Route::get('adverts','Api\AdvertController@index');
Route::post('adverts_view','Api\AdvertController@advert_view');
Route::post('adverts','Api\AdvertController@store');

Route::get('countries','Api\CountryController@index');

Route::get('get_events','Api\EventController@index');

Route::post('get_notifications','Api\NotificationController@index');

Route::get('get_posts','Api\PostController@index');
Route::post('store_post','Api\PostController@store');
Route::post('delete_post','Api\PostController@delete');
Route::post('like_post','Api\PostController@like');
Route::post('report_abuse','Api\PostController@get_abuse');
Route::post('get_abuse','Api\PostController@report_abuse');

Route::get('get_venues','Api\VenueController@index');
