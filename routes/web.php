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

        Route::get('register',
            'EventOrganizerAuth\RegisterController@showRegistrationForm')->name('event_organizer_register_form');
        Route::post('register',
            'EventOrganizerAuth\RegisterController@register')->name('event_organizer_register_form_post');


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

//Only logged in admin can access or send requests to these pages
Route::group(['middleware' => 'admin_auth', 'prefix' => 'admin'], function () {

    Route::get('home', 'AdminPages\HomeController@index')->name('admin_home');
    Route::post('logout', 'AdminAuth\LoginController@logout')->name('admin_logout');
    Route::get('add', 'AdminAuth\RegisterController@showRegistrationForm')->name('add_admin');
    Route::post('add', 'AdminAuth\RegisterController@register')->name('add_admin_post');

    Route::get('admins', 'AdminPages\AdminsController@index')->name('admins');

    Route::get('countries', 'AdminPages\CountriesController@index')->name('countries');
    Route::get('countries/add', 'AdminPages\CountriesController@showAddForm')->name('add_country');
    Route::post('countries/add', 'AdminPages\CountriesController@store')->name('add_country_post');
    Route::get('countries/edit/{name}', 'AdminPages\CountriesController@showEditForm')->name('edit_country');
    Route::post('countries/edit', 'AdminPages\CountriesController@update')->name('edit_country_post');
    Route::post('countries/delete', 'AdminPages\CountriesController@destroy')->name('delete_country');

    Route::get('towns', 'AdminPages\TownsController@index')->name('towns');
    Route::get('towns/add', 'AdminPages\TownsController@showAddForm')->name('add_town');
    Route::post('towns/add', 'AdminPages\TownsController@store')->name('add_town_post');
    Route::get('towns/edit/{coutry}/{town}', 'AdminPages\TownsController@showEditForm')->name('edit_town');
    Route::post('towns/edit', 'AdminPages\TownsController@update')->name('edit_town_post');
    Route::post('towns/delete', 'AdminPages\TownsController@destroy')->name('delete_town');    

    Route::get('adverts', 'AdminPages\AdvertsController@index')->name('adverts');
    Route::get('adverts/add', 'AdminPages\AdvertsController@showAddForm')->name('add_advert');
    Route::post('adverts/add', 'AdminPages\AdvertsController@store')->name('add_advert_post'); 
    Route::post('adverts/edit', 'AdminPages\AdvertsController@showEditForm')->name('edit_advert');
    Route::post('adverts/edit/update', 'AdminPages\AdvertsController@update')->name('edit_advert_post');
    Route::post('adverts/delete', 'AdminPages\AdvertsController@destroy')->name('delete_advert');  

    Route::get('venues', 'AdminPages\VenuesController@index')->name('venues');
    Route::get('venues/add', 'AdminPages\VenuesController@showAddForm')->name('add_venue');
    Route::post('venues/add', 'AdminPages\VenuesController@store')->name('add_venue_post');
    Route::post('venues/edit', 'AdminPages\VenuesController@showEditForm')->name('edit_venue');
    Route::post('venues/edit/update', 'AdminPages\VenuesController@update')->name('edit_venue_post');
    Route::post('venues/delete', 'AdminPages\VenuesController@destroy')->name('delete_venue');

    Route::get('users', 'AdminPages\UsersController@index')->name('users');

    Route::get('posts', 'AdminPages\PostsController@index')->name('posts');
    Route::post('posts/block', 'AdminPages\PostsController@block')->name('block_post');
    Route::post('abuses', 'AdminPages\AbusesController@index')->name('abuses');

    Route::get('event_organizers/unverified', 'AdminPages\EventOrganizersController@Unverifiedindex')->name('unverified_event_organizers');
    Route::get('event_organizers/verified', 'AdminPages\EventOrganizersController@Verifiedindex')->name('verified_event_organizers');
    Route::post('event_organizers/deactivate', 'AdminPages\EventOrganizersController@deactivate')->name('deactivate_event_organizer_post');
    Route::post('event_organizers/verify', 'AdminPages\EventOrganizersController@verify')->name('verify_event_organizer_post');
    Route::post('event_organizers/activate', 'AdminPages\EventOrganizersController@activate')->name('activate_event_organizer_post');

    Route::get('events/unverified', 'CommonPages\EventsController@Unverifiedindex')->name('admin_unverified_events');
    Route::get('events/verified/paid', 'CommonPages\EventsController@VerifiedPaidindex')->name('admin_verified_paid_events');
    Route::get('events/verified/free', 'CommonPages\EventsController@VerifiedFreeindex')->name('admin_verified_free_events');
    Route::post('events/deactivate', 'CommonPages\EventsController@deactivate')->name('admin_deactivate_event_post');
    Route::post('events/verify', 'CommonPages\EventsController@verify')->name('admin_verify_event_post');
    Route::post('events/activate', 'CommonPages\EventsController@activate')->name('admin_activate_event_post');
    
});

//Only logged in event organizer can access or send requests to these pages
Route::group(['middleware' => 'event_organizer_auth','prefix'=>'event_organizer'], function () {
    
    Route::get('home', 'EventOrganizerPages\HomeController@index')->name('event_organizer_home');

    Route::post('logout', 'EventOrganizerAuth\LoginController@logout')->name('event_organizer_logout');
    Route::get('events/unverified', 'CommonPages\EventsController@Unverifiedindex')->name('event_organizer_unverified_events');
    Route::get('events/verified/paid', 'CommonPages\EventsController@VerifiedPaidindex')->name('event_organizer_verified_paid_events');
    Route::get('events/verified/free', 'CommonPages\EventsController@VerifiedFreeindex')->name('event_organizer_verified_free_events');
    Route::post('events/deactivate', 'CommonPages\EventsController@deactivate')->name('event_organizer_deactivate_event_post');
    Route::post('events/verify', 'CommonPages\EventsController@verify')->name('event_organizer_verify_event_post');
    Route::post('events/activate', 'CommonPages\EventsController@activate')->name('event_organizer_activate_event_post');    
    Route::get('events/add', 'CommonPages\EventsController@showAddForm')->name('add_event');   
    Route::post('events/add', 'CommonPages\EventsController@store')->name('add_event_post');


});