<?php

return [
    'username' => env('CAMPAY_USERNAME'),
    'password' => env('CAMPAY_PASSWORD'),
    'token_url' => env('CAMPAY_TOKEN_URL', 'https://demo.campay.net/api/token/'),
    'collect_url' => env('CAMPAY_COLLECT_URL', 'https://demo.campay.net/api/collect/'),
    'status_url' => env('CAMPAY_STATUS_URL', 'https://demo.campay.net/api/transaction/'),
    'webhook_url' => env('CAMPAY_WEBHOOK_URL'),
];
