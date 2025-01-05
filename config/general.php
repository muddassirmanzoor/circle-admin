    <?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
        'emails' => [
        'MAIL_FROM_NAME'=>'Circl',
        'MAIL_USERNAME'=>'admin@circl.com',
        'MAIL_FROM_ADDRESS'=>'admin@circl.com',
        'ADMIN_EMAIL'=>'admin@circl.com',
        'SUPPORT_EMAIL'=>'admin@circl.com',
        ],
    "globals" => [
        "hyperpay_access_token" => 'OGFjN2E0Yzc3MmE4Zjg3YzAxNzJiMWUyNDhlYzE5YzN8WFFDNTVoTnJzeg==', // test credentials
//        "hyperpay_access_token" => 'OGFjZGE0Y2Q3NTQwYWI4ZDAxNzU0YjAzODJlMDcyYTN8d3B5QVdyc3g4eA==',  // live credentials
        "hyperpay_entity_id" => '8ac7a4c8730e778101730ea96d810154', // test credentials
//        "hyperpay_entity_id" => '8acda4cd7540ab8d01754b03ecbf72aa',  // live credentials
        "hyperpay_base_address" => 'https://test.oppwa.com', // test link
//        "hyperpay_base_address" => 'https://oppwa.com/', // live link
        "hyperpay_full_address" => 'https://test.oppwa.com/v1/checkouts', // test credentials
//        "hyperpay_full_address" => 'https://oppwa.com/v1/checkouts',      // live credetials
        "hyperpay_chargeback_address" => 'https://test.oppwa.com/v1/payments', // test credentials
//        "hyperpay_chargeback_address" => 'https://oppwa.com/v1/payments',      // live credetials
        "SAR" => 4.72,
        "Pound" => 0.21,
    ],
    'fixer' => [
        'fixer_key' => '74347d47de7750e9bb484c4065a5483b',
    ],
    "notifications" => [
        "notification_gateway" => env('NOTIFICATION_GATEWAY', 'ssl://gateway.push.apple.com:2195'),
        "bundle_identifier" => "com.circlonline.app",
        "key_id" => "W2JN797RY6",
        "team_id" => "662LW2NFS8",
        "p8_key" => "-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQggmEkzxbEJTBWPADM
PBpi0zkthQ2I26Ud1n6pWNwIPCegCgYIKoZIzj0DAQehRANCAARSSz5IxRL/L18x
TL+vzxn1K6JH8LSlvOQVAlfQa+2NDWBYCp2SuU8ZYijOucVL36yNG4wfDnmZvnGN
x41ap9Xb
-----END PRIVATE KEY-----",
    ],
];
