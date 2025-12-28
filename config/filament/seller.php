<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Panel Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration options for the seller panel.
    |
    */

    'name' => 'Seller Panel',

    'route_prefix' => 'seller',

    'middleware' => [
        'web',
    ],

    'panels' => [
        'seller' => [
            'id' => 'seller',
            'path' => 'seller',
            'provider' => \App\Providers\Filament\SellerPanelProvider::class,
            'middleware' => ['web', 'auth'],
            'guard' => 'web',  // or your guard
        ],
    ],

    // You can add more options later like:
    // - feature toggles
    // - permissions
    // - branding options
];
