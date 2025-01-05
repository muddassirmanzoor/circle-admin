<?php

/*
  |--------------------------------------------------------------------------
  | Paths Configurations
  |--------------------------------------------------------------------------
  |
  | This file will have all paths and URLs for the project.
  |
 */

return [
    // 'key' => 'value',
    'SERVICES_APP_KEY' => env('SERVICES_APP_KEY'),
    'SERVICES_APP_BASE_URL' => env('SERVICES_APP_BASE_URL'),
    'twillio_account_id' => env('TWILLIO_ACCOUNT_ID'),
    'twillio_auth_token' => env('TWILLIO_AUTH_TOKEN'),
    'twilio_number' => env('TWILLIO_NUMBER'),
    's3_cdn_base_url' => env('AWS_CDN_BASE_URL'),
    's3_access_key' => env('AWS_ACCESS_KEY_ID'),
    's3_secret_key' => env('AWS_SECRET_ACCESS_KEY'),
    's3_bucket' => env('AWS_BUCKET'),
    's3_bucket_region' => env('AWS_DEFAULT_REGION'),
    's3_image_path_slug' => 'uploads/items/',
    's3_images_link' => env('AWS_CDN_BASE_URL') . 'uploads/items/',
    's3_images_link' => env('AWS_CDN_BASE_URL') . 'uploads/items/',
];
