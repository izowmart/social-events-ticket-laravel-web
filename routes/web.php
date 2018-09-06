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


use function foo\func;

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

    Route::get('/admin-login', 'AdminAuth\LoginController@showLoginForm')->name('admin_login_form');
    Route::post('/admin-login', 'AdminAuth\LoginController@login')->name('admin_login_form_process');

//Password reset routes
    Route::get('/admin-password/reset',
        'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('admin_email_form');
    Route::post('/admin-password/email',
        'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('admin_email_process');
    Route::get('/admin-password/reset/{token}',
        'AdminAuth\ResetPasswordController@showResetForm')->name('admin_reset_form');
    Route::post('/admin-password/reset', 'AdminAuth\ResetPasswordController@reset')->name('admin_reset_process');

    Route::get('/event-organizer-login',
        'EventOrganizerAuth\LoginController@showLoginForm')->name('event_organizer_login_form');
    Route::post('/event-organizer-login',
        'EventOrganizerAuth\LoginController@login')->name('event_organizer_login_form_process');

//Password reset routes
    Route::get('/event-organizer-password/reset',
        'EventOrganizerAuth\ForgotPasswordController@showLinkRequestForm')->name('event_organizer_email_form');
    Route::post('/event-organizer-password/email',
        'EventOrganizerAuth\ForgotPasswordController@sendResetLinkEmail')->name('event_organizer_email_form_process');
    Route::get('/event-organizer-password/reset/{token}',
        'EventOrganizerAuth\ResetPasswordController@showResetForm')->name('event_organizer_reset_form');
    Route::post('/event-organizer-password/reset',
        'EventOrganizerAuth\ResetPasswordController@reset')->name('event_organizer_reset_form_process');


//android app users - password reset routes
    Route::get('user/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');
    Route::post('user/reset', 'UserAuth\ResetPasswordController@reset');


});
//where the android app user is redirected to after password reset
Route::group(['middleware'=>'web'], function () {
    Route::get('user_home', 'HomeController@home_user');
});

//Only logged in users can access or send requests to these pages
Route::group(['middleware' => 'admin_auth'], function () {

    Route::get('/admin-home', 'AdminPages\HomeController@index')->name('admin_homepage');
    Route::post('/admin-logout', 'AdminAuth\LoginController@logout')->name('admin_logout');
    Route::get('/add-admin', 'AdminAuth\RegisterController@showRegistrationForm')->name('add_admin');
    Route::post('/add-admin', 'AdminAuth\RegisterController@register')->name('admin_registration_process');

    Route::get('/admins', 'AdminPages\AdminsController@index')->name('admins_table');
});


/*
|--------------------------------------------------------------------------
| Event Organizer auth routes
|--------------------------------------------------------------------------
|
*/

//Only logged in users can access or send requests to these pages
Route::group(['middleware' => 'event_organizer_auth'], function () {

    Route::post('/event-organizer-logout', 'EventOrganizerAuth\LoginController@logout')->name('event_organizer_logout');
    Route::get('/event-organizer-register',
        'EventOrganizerAuth\RegisterController@showRegistrationForm')->name('event_organizer_registration_form');
    Route::post('/event-organizer-register',
        'EventOrganizerAuth\RegisterController@register')->name('event_organizer_registration_form_process');

    Route::get('/event_organizer_home', function () {
        return view('/event-organizer.home');
    });

});