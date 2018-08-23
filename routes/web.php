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