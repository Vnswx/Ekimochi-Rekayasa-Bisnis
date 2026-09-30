<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Midtrans Payment Gateway
    | Dapatkan credentials di: https://dashboard.midtrans.com/
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    
    // Set true untuk production, false untuk sandbox/testing
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    // Set true untuk sanitasi otomatis
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    
    // Set true untuk 3DS (3D Secure)
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
