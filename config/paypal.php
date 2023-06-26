<?php

return [
    'mode'    => env('PAYPAL_MODE', $_SERVER['PAYPAL_MODE'] ?? 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', $_SERVER['PAYPAL_SANDBOX_CLIENT_ID'] ?? ''),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', $_SERVER['PAYPAL_SANDBOX_CLIENT_SECRET'] ?? ''),
        'app_id'            => 'APP-80W284485P519543T',
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', $_SERVER['PAYPAL_LIVE_CLIENT_ID'] ?? ''),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', $_SERVER['PAYPAL_LIVE_CLIENT_SECRET'] ?? ''),
        'app_id'            => env('PAYPAL_LIVE_APP_ID', $_SERVER['PAYPAL_LIVE_APP_ID'] ?? ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Order'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'EUR'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', $_SERVER['PAYPAL_NOTIFY_URL'] ?? ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
    
    
];
