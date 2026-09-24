<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shared secret between shop, future cloud broker, and POS
    |--------------------------------------------------------------------------
    */
    'token' => env('INTEGRATION_TOKEN', ''),

    /*
    | Broker URL that the shop posts outbox events to (HTTPS, internet).
    | POS later pulls the same queue outbound from the LAN.
    */
    'broker_url' => env('INTEGRATION_BROKER_URL', 'https://broker.example.invalid/events'),

    'city' => env('SHOP_SERVICE_CITY', ''),
];
