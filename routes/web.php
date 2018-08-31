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


// Auth::routes();

//Logged in users cannot access or send requests these pages
Route::group(['middleware' => 'guest'], function() {

Route::get('/admin_login', 'AdminAuth\LoginController@showLoginForm');
Route::post('/admin_login', 'AdminAuth\LoginController@login');

//Password reset routes
Route::get('admin_password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
Route::post('admin_password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
Route::get('admin_password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
Route::post('admin_password/reset', 'AdminAuth\ResetPasswordController@reset');

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