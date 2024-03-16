<?php

// config for joymendonca/ShopifyOauthLaravel
return [
    /*
    |--------------------------------------------------------------------------
    | Shopify APP client id
    |--------------------------------------------------------------------------
    |
    | Shopify App client id is an id you will get after creating your app
    |
     */
    'client_id' => env('SHOPIFY_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Shopify APP client secret
    |--------------------------------------------------------------------------
    |
    | Shopify App client id is an id you will get after creating your app
    |
     */
    'client_secret' => env('SHOPIFY_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | This redirect path used to redirect after installation of app
    |
     */
    'redirect_url' => env('SHOPIFY_REDIRECT_URL'),
    
    /*
    |--------------------------------------------------------------------------
    | App Home URL
    |--------------------------------------------------------------------------
    |
    | This URL that will be loaded when users opens the app from admin panel.
    |
     */
    'app_home_url' => env('SHOPIFY_APP_HOME_URL', '/'),

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | This redirect path used to redirect after installation of app
    |
     */
    'scopes' => env('SHOPIFY_SCOPES'),

    /*
    |--------------------------------------------------------------------------
    | Session key
    |--------------------------------------------------------------------------
    |
    | The session key used to save session data after app load.
    |
     */
    'session_key' => 'shopify_auth',

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
     */
    'models' => [

        /*
        |--------------------------------------------------------------------------
        | Shopify Store Model
        |--------------------------------------------------------------------------
        |
         */
        'store_model' => \joymendonca\ShopifyOauthLaravel\Models\Store::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Database Tables
    |--------------------------------------------------------------------------
     */
    'tables' => [

        /*
        |--------------------------------------------------------------------------
        | Stores table
        |--------------------------------------------------------------------------
        |
         */
        'stores' => 'stores',

        /*
        |--------------------------------------------------------------------------
        | Stores has User table
        |--------------------------------------------------------------------------
        |
         */
        'store_has_users' => 'store_has_users',

    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Controllers
    |--------------------------------------------------------------------------
     */
    'controllers' => [

        /*
        |--------------------------------------------------------------------------
        | Shopify Load API call controller
        |--------------------------------------------------------------------------
        |
         */
        'load' => \joymendonca\ShopifyOauthLaravel\Http\Controllers\ShopifyLoadController::class,

        /*
        |--------------------------------------------------------------------------
        | Shopify Install API call controller
        |--------------------------------------------------------------------------
        |
         */
        'install' => \joymendonca\ShopifyOauthLaravel\Http\Controllers\ShopifyInstallController::class,

    ],
];
