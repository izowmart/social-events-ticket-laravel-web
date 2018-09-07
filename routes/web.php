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


Route::get('/', 'HomeController@index')->name('home');
Route::group(['prefix' => 'payments'], function () {
    Route::post('encryption_url', 'HomeController@encryptData')->name('encryption_url');
    Route::post('success_url', 'HomeController@success')->name('success_url');
    Route::post('failure_url', 'HomeController@failure')->name('failure_url');
    Route::post('process_payment', 'HomeController@processPayment')->name('process_payment');
});


/*
|--------------------------------------------------------------------------
| Admin auth routes
|--------------------------------------------------------------------------
|
*/
//Logged in users cannot access or send requests these pages
Route::group(['middleware' => 'guest'], function () {
    Route::group(['prefix'=>'admin'], function () {
        Route::get('login', 'AdminAuth\LoginController@showLoginForm')->name('admin_login_form');
        Route::post('login', 'AdminAuth\LoginController@login')->name('admin_login_post');

        //Password reset routes
        Route::get('email',
            'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin_email_form');
        Route::post('email',
            'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('admin_email_post');
        Route::get('reset/{token}',
            'AdminAuth\ResetPasswordController@showResetForm')->name('admin_reset_form');
        Route::post('reset', 'AdminAuth\ResetPasswordController@reset')->name('admin_reset_post');

    });

    Route::group(['prefix'=>'event_organizer'], function () {
        Route::get('login',
            'EventOrganizerAuth\LoginController@showLoginForm')->name('event_organizer_login_form');
        Route::post('login',
            'EventOrganizerAuth\LoginController@login')->name('event_organizer_login_form_post');

        //Password reset routes
        Route::get('email',
            'EventOrganizerAuth\ForgotPasswordController@showLinkRequestForm')->name('event_organizer_email_form');
        Route::post('email',
            'EventOrganizerAuth\ForgotPasswordController@sendResetLinkEmail')->name('event_organizer_email_form_post');
        Route::get('reset/{token}',
            'EventOrganizerAuth\ResetPasswordController@showResetForm')->name('event_organizer_reset_form');
        Route::post('/reset',
            'EventOrganizerAuth\ResetPasswordController@reset')->name('event_organizer_reset_form_post');

    });


    Route::group(['prefix'=>'user'], function () {

        //android app users - password reset routes
        Route::get('reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');
        Route::post('reset', 'UserAuth\ResetPasswordController@reset');
    });

    Route::group(['prefix'=>'scanner'], function () {

        //android app users - password reset routes
        Route::get('reset/{token}', 'ScannerAuth\ResetPasswordController@showResetForm');
        Route::post('reset', 'ScannerAuth\ResetPasswordController@reset');
    });

});
//where the android app user is redirected to after password reset
Route::group(['middleware' => 'web'], function () {
    Route::get('user_home', 'HomeController@home_user');
});

Route::group(['middleware' => 'scanner', 'prefix'=>'scanner'], function () {
    Route::get('home', 'HomeController@home_scanner');
});

//Only logged in users can access or send requests to these pages
Route::group(['middleware' => 'admin_auth', 'prefix' => 'admin'], function () {

    Route::get('home', 'AdminPages\HomeController@index')->name('admin_home');
    Route::post('logout', 'AdminAuth\LoginController@logout')->name('admin_logout');
    Route::get('add', 'AdminAuth\RegisterController@showRegistrationForm')->name('add_admin');
    Route::post('add', 'AdminAuth\RegisterController@register')->name('add_admin_post');

    Route::get('admins', 'AdminPages\AdminsController@index')->name('admins');
    Route::get('countries', 'AdminPages\CountriesController@index')->name('countries');
    Route::get('countries/add', 'AdminPages\CountriesController@showAddForm')->name('add_country');
    Route::post('countries/add', 'AdminPages\CountriesController@store')->name('add_country_post');
    Route::get('towns', 'AdminPages\TownsController@index')->name('towns');
    Route::get('towns/add', 'AdminPages\TownsController@store')->name('add_town');

});


/*
|--------------------------------------------------------------------------
| Event Organizer auth routes
|--------------------------------------------------------------------------
|
*/

//Only logged in users can access or send requests to these pages
Route::group(['middleware' => 'event_organizer_auth',['prefix'=>'event_organizer']], function () {

    Route::post('logout', 'EventOrganizerAuth\LoginController@logout')->name('event_organizer_logout');
    Route::get('register',
        'EventOrganizerAuth\RegisterController@showRegistrationForm')->name('event_organizer_registration_form');
    Route::post('register',
        'EventOrganizerAuth\RegisterController@register')->name('event_organizer_registration_form_post');

    Route::get('home', function () {
        return view('event-organizer.home');
    });

});