<?php

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

Route::group(['prefix'=>'auth'], function () {

    Route::group(['prefix'=>'user'], function () {
       Route::get('users','Api\AuthController@index');
       Route::post('register','Api\AuthController@register_user');
       Route::post('login', 'Api\AuthController@login_user');
       Route::post('reset_password_email', 'Api\AuthController@reset_password_user');
    });

    Route::group(['prefix'=>'scanner'], function () {
        Route::get('scanners','Api\ScannerAuthController@index');
        Route::post('register','Api\ScannerAuthController@register');
        Route::post('login', 'Api\ScannerAuthController@login');
        Route::post('reset_password_email', 'Api\ScannerAuthController@reset_password');
    });

});

Route::get('adverts','Api\AdvertController@index');
Route::post('adverts_view','Api\AdvertController@advert_view');
Route::post('adverts','Api\AdvertController@store');
Route::get('countries','Api\CountryController@index');
Route::get('events','Api\EventController@index');
Route::get('notifications/{id}','Api\NotificationController@index');

Route::get('venues','Api\VenueController@index');

Route::get('posts','Api\PostController@index');
Route::post('posts','Api\PostController@store');
Route::post('delete_post','Api\PostController@delete');
Route::post('like_post','Api\PostController@like');
Route::post('report_abuse','Api\PostController@get_abuse');

Route::get('user/{id}/relations', 'Api\AuthController@user_relations');
Route::post('user/follow', 'Api\AuthController@follow');

