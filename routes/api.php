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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $base_url = "App\Http\Controllers\\";
    $api->group(['prefix' => 'auth'], function ($api) use ($base_url) {

        $api->group(['prefix' => 'user'], function ($api) use ($base_url) {
            $api->get('users', $base_url . 'Api\AuthController@index');
            $api->post('register', $base_url . 'Api\AuthController@register_user');
            $api->post('login', $base_url . 'Api\AuthController@login_user');
            $api->post('reset_password_email', $base_url . 'Api\AuthController@reset_password_user');
            $api->post('update_auto_follow_status', $base_url . 'Api\AuthController@update_auto_follow_status');
            $api->post('update_fcm_token', $base_url . 'Api\AuthController@update_fcm_token');
        });

        $api->group(['prefix' => 'scanner'], function ($api) use ($base_url) {
            $api->get('scanners', $base_url . 'Api\ScannerAuthController@index');
            $api->post('register', $base_url . 'Api\ScannerAuthController@register');
            $api->post('login', $base_url . 'Api\ScannerAuthController@login');
            $api->post('reset_password_email', $base_url . 'Api\ScannerAuthController@reset_password');
        });
    });

    $api->group(['prefix'=> 'user'], function ($api) use ($base_url) {
        $api->get('adverts', $base_url . 'Api\AdvertController@index');
        $api->post('adverts_view', $base_url . 'Api\AdvertController@advert_view');

        $api->get('countries', $base_url . 'Api\CountryController@index');
        $api->get('events/{user_id}', $base_url . 'Api\EventController@index');
        $api->get('notifications/{user_id}', $base_url . 'Api\NotificationController@index');
        $api->post('notifications', $base_url . 'Api\NotificationController@markSeen');

        $api->get('venues/{user_id}', $base_url . 'Api\VenueController@index');
        $api->post('follow_venue', $base_url . 'Api\VenueController@follow_venue');

        $api->get('posts/{user_id}', $base_url . 'Api\PostController@index');
        $api->post('posts', $base_url . 'Api\PostController@store');
        $api->post('delete_post', $base_url . 'Api\PostController@delete');
        $api->post('like_post', $base_url . 'Api\PostController@like');
        $api->post('report_abuse', $base_url . 'Api\PostController@report_abuse');
        $api->get('{id}/relations', $base_url . 'Api\AuthController@user_relations');
        $api->post('follow', $base_url . 'Api\AuthController@follow');
    });

    $api->group(['prefix'=> 'scanner'], function ($api) use ($base_url) {
        $api->get('events/{scanner_id}',$base_url.'Api\EventController@scanner_events');

    });

    $api->get('payments/{user_id}/{event_id}', $base_url . 'Api\MulaPaymentController@initiate_payment');
});

