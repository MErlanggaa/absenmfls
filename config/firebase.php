<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    | Your Firebase project ID. Found in Firebase Console > Project Settings.
    | Example: my-app-12345
    */
    'project_id' => env('FIREBASE_PROJECT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Firebase Web App Config (for frontend JS SDK)
    |--------------------------------------------------------------------------
    | Found in Firebase Console > Project Settings > Your apps > Web app
    */
    'api_key'            => env('FIREBASE_API_KEY', ''),
    'auth_domain'        => env('FIREBASE_AUTH_DOMAIN', ''),
    'storage_bucket'     => env('FIREBASE_STORAGE_BUCKET', ''),
    'messaging_sender_id'=> env('FIREBASE_MESSAGING_SENDER_ID', ''),
    'app_id'             => env('FIREBASE_APP_ID', ''),
    'vapid_key'          => env('FIREBASE_VAPID_KEY', ''),
];
