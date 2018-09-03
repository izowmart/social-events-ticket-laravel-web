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
Route::group(['prefix'=>'payments'], function () {
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
Route::group(['middleware' => 'guest'], function() {

Route::get('/admin_login', 'AdminAuth\LoginController@showLoginForm');
Route::post('/admin_login', 'AdminAuth\LoginController@login');

//Password reset routes
Route::get('/admin_password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
Route::post('/admin_password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
Route::get('/admin_password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
Route::post('/admin_password/reset', 'AdminAuth\ResetPasswordController@reset');

});

//Only logged in users can access or send requests to these pages
Route::group(['middleware' => 'admin_auth'], function(){

Route::post('/admin_logout', 'AdminAuth\LoginController@logout');
Route::get('/admin_register', 'AdminAuth\RegisterController@showRegistrationForm');
Route::post('admin_register', 'AdminAuth\RegisterController@register');

Route::get('/admin_home', function(){
  return view('admin.home');  
});
});



/*
|--------------------------------------------------------------------------
| Event Organizer auth routes
|--------------------------------------------------------------------------
|
*/
//Logged in users cannot access or send requests these pages
Route::group(['middleware' => 'guest'], function() {

Route::get('/event_organizer_login', 'EventOrganizerAuth\LoginController@showLoginForm')->name('event_organizer_login_form');
Route::post('/event_organizer_login', 'EventOrganizerAuth\LoginController@login')->name('event_organizer_login_form_process');

//Password reset routes
Route::get('/event_organizer_password/reset', 'EventOrganizerAuth\ForgotPasswordController@showLinkRequestForm')->name('event_organizer_email_form');
Route::post('/event_organizer_password/email', 'EventOrganizerAuth\ForgotPasswordController@sendResetLinkEmail')->name('event_organizer_email_form_process');
Route::get('/event_organizer_password/reset/{token}', 'EventOrganizerAuth\ResetPasswordController@showResetForm')->name('event_organizer_reset_form');
Route::post('/event_organizer_password/reset', 'EventOrganizerAuth\ResetPasswordController@reset')->name('event_organizer_reset_form_process');

});

//Only logged in users can access or send requests to these pages
// Route::group(['middleware' => 'event_organizer_auth'], function(){

Route::post('/event_organizer_logout', 'EventOrganizerAuth\LoginController@logout')->name('event_organizer_logout');
Route::get('/event_organizer_register', 'EventOrganizerAuth\RegisterController@showRegistrationForm')->name('event_organizer_registration_form');
Route::post('/event_organizer_register', 'EventOrganizerAuth\RegisterController@register')->name('event_organizer_registration_form_process');

Route::get('/event_organizer_home', function(){
  return view('/event_organizer.home');  
});

// });