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
Route::get('/test_payment', 'MulaPaymentController@index')->name('payment_home');
Route::group(['prefix' => 'payments'], function () {
    // Route::get('https://beep2.cellulant.com:9212:/checkout/v2/modal')
    Route::post('encryption_url', 'MulaPaymentController@encryptData')->name('encryption_url');
    Route::post('success_url', 'MulaPaymentController@success')->name('success_url');
    Route::post('mobile_success_url', 'MulaPaymentController@mobile_success')->name('mobile_success_url');
    Route::post('failure_url', 'MulaPaymentController@failure')->name('failure_url');
    Route::post('mobile_failure_url', 'MulaPaymentController@mobile_failure')->name('mobile_failure_url');
    Route::post('process_payment', 'MulaPaymentController@processPayment')->name('process_payment');
});


Route::group(['middleware' => 'guest'], function () {
    Route::group(['prefix'=>'admin'], function () {
        Route::get('login', 'AdminAuth\LoginController@showLoginForm')->name('admin_login_form');
        Route::post('login', 'AdminAuth\LoginController@login')->name('admin_login_post');

        //Admin password reset routes
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


        //Event organizer password reset routes
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
    Route::get('user/home', 'HomeController@home_user');
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

    Route::group(['prefix' => 'admins'], function () {
        Route::get('/', 'AdminPages\AdminsController@index')->name('admins');
        Route::get('show/{id}', 'AdminPages\AdminsController@show')->name('single_admin');   
        Route::post('delete', 'AdminPages\AdminsController@destroy')->name('delete_admin');
    });

    Route::group(['prefix'=>'countries'], function () {
        Route::get('/', 'AdminPages\CountriesController@index')->name('countries');
        Route::get('add', 'AdminPages\CountriesController@showAddForm')->name('add_country');
        Route::post('add', 'AdminPages\CountriesController@store')->name('add_country_post');
        Route::get('edit/{name}', 'AdminPages\CountriesController@showEditForm')->name('edit_country');
        Route::post('edit', 'AdminPages\CountriesController@update')->name('edit_country_post');
        Route::post('delete', 'AdminPages\CountriesController@destroy')->name('delete_country');
        Route::get('most_users_chart', 'AdminPages\HomeController@country_most_users_chart')->name('country_most_users_chart');
    });

    Route::group(['prefix'=>'towns'], function () {
        Route::get('/', 'AdminPages\TownsController@index')->name('towns');
        Route::get('show/{id}', 'AdminPages\TownsController@show')->name('single_town');
        Route::get('add', 'AdminPages\TownsController@showAddForm')->name('add_town');
        Route::post('add', 'AdminPages\TownsController@store')->name('add_town_post');
        Route::get('edit/{coutry}/{town}', 'AdminPages\TownsController@showEditForm')->name('edit_town');
        Route::post('edit', 'AdminPages\TownsController@update')->name('edit_town_post');
        Route::post('delete', 'AdminPages\TownsController@destroy')->name('delete_town');       
        Route::get('most_users_chart', 'AdminPages\HomeController@town_most_users_chart')->name('town_most_users_chart');  
    }); 
    
    Route::group(['prefix'=>'adverts'], function () {
        Route::get('/', 'AdminPages\AdvertsController@index')->name('adverts');
        Route::get('add', 'AdminPages\AdvertsController@showAddForm')->name('add_advert');
        Route::post('add', 'AdminPages\AdvertsController@store')->name('add_advert_post'); 
        Route::get('edit/{slug}', 'AdminPages\AdvertsController@showEditForm')->name('edit_advert');
        Route::post('edit', 'AdminPages\AdvertsController@update')->name('edit_advert_post');
        Route::post('delete', 'AdminPages\AdvertsController@destroy')->name('delete_advert');  
    });

    Route::group(['prefix'=>'venues'], function () {
        Route::get('/', 'AdminPages\VenuesController@index')->name('venues');
        Route::get('show/{id}', 'AdminPages\VenuesController@show')->name('single_venue');
        Route::get('add', 'AdminPages\VenuesController@showAddForm')->name('add_venue');
        Route::post('add', 'AdminPages\VenuesController@store')->name('add_venue_post');
        Route::get('edit/{slug}', 'AdminPages\VenuesController@showEditForm')->name('edit_venue');
        Route::post('edit', 'AdminPages\VenuesController@update')->name('edit_venue_post');
        Route::post('delete', 'AdminPages\VenuesController@destroy')->name('delete_venue');
        Route::get('active_venues_chart', 'AdminPages\HomeController@active_venues_chart')->name('active_venues_chart');
        Route::get('feature/{slug}', 'AdminPages\VenuesController@feature')->name('feature_venue');
    }); 

    Route::group(['prefix'=>'users'], function () {
        Route::get('/', 'AdminPages\UsersController@index')->name('users');
        Route::get('show/{id}', 'AdminPages\UsersController@show')->name('single_user');
        Route::get('new_users_chart', 'AdminPages\HomeController@new_users_chart')->name('new_users_chart');
        Route::get('active_users_chart', 'AdminPages\HomeController@active_users_chart')->name('active_users_chart');

    });

    Route::group(['prefix'=>'posts'], function () {
        Route::get('/', 'AdminPages\PostsController@index')->name('posts');
        Route::post('block', 'AdminPages\PostsController@block')->name('block_post');
        Route::get('abuses/{id}', 'AdminPages\AbusesController@index')->name('abuses');
    });

    Route::group(['prefix'=>'event_organizers'], function () {
        Route::get('show/{id}', 'AdminPages\EventOrganizersController@show')->name('single_event_organizer');
        Route::get('unverified', 'AdminPages\EventOrganizersController@Unverifiedindex')->name('unverified_event_organizers');
        Route::get('verified', 'AdminPages\EventOrganizersController@Verifiedindex')->name('verified_event_organizers');
        Route::post('deactivate', 'AdminPages\EventOrganizersController@deactivate')->name('deactivate_event_organizer_post');
        Route::post('verify', 'AdminPages\EventOrganizersController@verify')->name('verify_event_organizer_post');
        Route::post('activate', 'AdminPages\EventOrganizersController@activate')->name('activate_event_organizer_post');
    });

    Route::group(['prefix'=>'events'], function () {
        Route::get('unverified', 'CommonPages\EventsController@Unverifiedindex')->name('admin_unverified_events');
        Route::get('unverified/paid', 'CommonPages\EventsController@UnverifiedPaidindex')->name('admin_unverified_paid_events');
        Route::get('unverified/free', 'CommonPages\EventsController@UnverifiedFreeindex')->name('admin_unverified_free_events');
        Route::get('verified/paid', 'CommonPages\EventsController@VerifiedPaidindex')->name('admin_verified_paid_events');
        Route::get('verified/free', 'CommonPages\EventsController@VerifiedFreeindex')->name('admin_verified_free_events');
        Route::post('deactivate', 'CommonPages\EventsController@deactivate')->name('admin_deactivate_event_post');
        Route::post('verify', 'CommonPages\EventsController@verify')->name('admin_verify_event_post');
        Route::post('activate', 'CommonPages\EventsController@activate')->name('admin_activate_event_post');
    });
    
});

//Only logged in event organizer can access or send requests to these pages
Route::group(['middleware' => 'event_organizer_auth','prefix'=>'event_organizer'], function () {
    
    Route::get('home', 'EventOrganizerPages\HomeController@index')->name('event_organizer_home');

    Route::post('logout', 'EventOrganizerAuth\LoginController@logout')->name('event_organizer_logout');

    Route::group(['prefix'=>'events'], function () {
        Route::get('unverified', 'CommonPages\EventsController@Unverifiedindex')->name('event_organizer_unverified_events');
        Route::get('unverified/paid', 'CommonPages\EventsController@UnverifiedPaidindex')->name('event_organizer_unverified_paid_events');
        Route::get('unverified/free', 'CommonPages\EventsController@UnverifiedFreeindex')->name('event_organizer_unverified_free_events');
        Route::get('verified/paid', 'CommonPages\EventsController@VerifiedPaidindex')->name('event_organizer_verified_paid_events');
        Route::get('verified/free', 'CommonPages\EventsController@VerifiedFreeindex')->name('event_organizer_verified_free_events');
        Route::post('deactivate', 'CommonPages\EventsController@deactivate')->name('event_organizer_deactivate_event_post');
        Route::post('verify', 'CommonPages\EventsController@verify')->name('event_organizer_verify_event_post');
        Route::post('activate', 'CommonPages\EventsController@activate')->name('event_organizer_activate_event_post');    
        Route::get('add', 'CommonPages\EventsController@showAddForm')->name('add_event');   
        Route::post('add', 'CommonPages\EventsController@store')->name('add_event_post');    
        Route::get('edit/{slug}', 'CommonPages\EventsController@showEditForm')->name('edit_event');   
        Route::post('edit', 'CommonPages\EventsController@update')->name('edit_event_post');   
        Route::post('delete', 'CommonPages\EventsController@destroy')->name('delete_event');

        Route::group(['prefix'=>'scanners'], function () {
            Route::post('/', 'EventOrganizerPages\ScannersController@index')->name('scanners');
            Route::post('add', 'EventOrganizerPages\ScannersController@showAddForm')->name('add_scanner');   
            Route::post('add/scanner', 'EventOrganizerPages\ScannersController@store')->name('add_scanner_post');    
            Route::post('edit', 'EventOrganizerPages\ScannersController@showEditForm')->name('edit_scanner');   
            Route::post('edit/update', 'EventOrganizerPages\ScannersController@update')->name('edit_scanner_post');   
            Route::post('delete', 'EventOrganizerPages\ScannersController@destroy')->name('delete_scanner');

        });


    });
});

Route::group(['prefix' => 'tickets'], function () {
    Route::get('/', 'TicketsController@index')->name('tickets_home');
    Route::get('{slug}', 'TicketsController@show')->name('ticket_details');
    
});

Route::get('display-tickets', 'TicketsController@displayTickets')->name('display-tickets');

//Terms and conditions pages
Route::get('terms-and-conditions', 'HomeController@showTermsAndConditions')->name('terms-and-conditions');
Route::get('privacy-policy', 'HomeController@showPrivacyPolicy')->name('privacy-policy');