<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],

        //Our admin custom driver.
        'web_admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        //Our event_organizer custom driver.
        'web_event_organizer' => [
            'driver' => 'session',
            'provider' => 'event_organizers',
        ],

        //our scanners custom driver
        'scanner' =>[
            'driver' => 'session',
            'provider' => 'scanners',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
        //admin user provider
        'admins' => [
            'driver' => 'eloquent',  //We are using eloquent model
            'model' => App\Admin::class,
        ],
        //event_organizer user provider
        'event_organizers' => [
            'driver' => 'eloquent',  //We are using eloquent model
            'model' => App\EventOrganizer::class,
        ],

        //scanners provider
        'scanners' => [
            'driver' => 'eloquent',
            'model'     => App\Scanner::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        //admin password broker
       'admins' => [
            //user provider for admin
           'provider' => 'admins',
            //table to store password reset tokens for admin
           'table' => 'password_resets',
           //expire time for these tokens in minutes
           'expire' => 60,
       ],
       //event organizer password broker
       'event_organizers' => [
            //user provider for event organizer
           'provider' => 'event_organizers',
            //table to store password reset tokens for event organizer
           'table' => 'password_resets',
           //expire time for these tokens in minutes
           'expire' => 60,
       ],

        //scanners
        'scanners' => [
            'provider' => 'scanners',
            'table'     => 'scanners_password_resets',
            'expire'    => 60,
        ],
    ],

];
