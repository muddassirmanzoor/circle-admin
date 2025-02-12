<?php

namespace App\Helpers;

/*
  All methods related to user notifications will be here
 */

use App\UserDevice;
use Illuminate\Support\Facades\Log;

class PushNotificationHelper {

    public static function testNotifications() {
        $notification_data = [];
        $device_token = '8a9a8fb68d38dabe0124dd1dbb329fbd8adc1be16a7ab5180d47829f7000a2bf';
        $notification_data['aps']['alert'] = 'Yasir has started following you';
        $notification_data['aps']['sound'] = 'default';
        $notification_data['aps']['badge'] = 0;

        $notification_data['type'] = 'new_follower';
        $notification_data['data']['sender']['customer_uuid'] = '123456t6';
        $notification_data['data']['sender']['first_name'] = 'Yasir';
        $notification_data['data']['sender']['last_name'] = 'Ali';
        $notification_data['data']['sender']['profile_image'] = 'http://d2bp2kgc0vgu09.cloudfront.net/uploads/profile_images/customers/4F1E8B68-9947-424C-9D49-7CD7C614F27B-1.jpeg';

        $notification_data['data']['receiver']['customer_uuid'] = '123456t6';
        $notification_data['data']['receiver']['first_name'] = 'Yasir';
        $notification_data['data']['receiver']['last_name'] = 'Ali';
        $notification_data['data']['receiver']['profile_image'] = 'http://d2bp2kgc0vgu09.cloudfront.net/uploads/profile_images/customers/4F1E8B68-9947-424C-9D49-7CD7C614F27B-1.jpeg';

        //        Production pem file
        $pemFile = 'apns-prod.pem';
        //        $pemFile = 'development_push.p12';
        //        Development pem file
        //        $pemFile = 'apns-dev.pem';

        PushNotificationHelper::sendPushNotificationToIOS($device_token, $notification_data, $pemFile);
        return true;
    }

   
    public static function send_notification_to_user_devices($user_uuid, $data = null, $is_chat = null) {

//        $totalBadgeCount = 0;
        // TODO: need to fix this after demo
        $totalBadgeCount = self::getBadgeCount($data, $is_chat);
        if (!empty($data['notification_send_type']) && isset($data['notification_send_type']) && $data['notification_send_type'] == 'mutable') {
            $data['type'] = (!empty($data['type'])) ? $data['type'] : $data['notification_type'];
            $iosNotificationDetail = PushNotificationHelper::setIosChatNotificationDataParameters($data, $totalBadgeCount);
            \Log::info("---Notification Detail---");
            \Log::info($iosNotificationDetail);
        } else {
            $iosNotificationDetail = PushNotificationHelper::setIosNotificationDataParameters($data, $totalBadgeCount);
            \Log::info("---flow is here ---");
            \Log::info($iosNotificationDetail);
        }
        Log::info('Sending Push Notification ', [
            'user_id' => $user_uuid,
            'data' => $data,
        ]);
        $pemFile = 'apns-prod.pem';

        $devices = UserDevice::getUserAllDevices('user_id', $user_uuid);

        //        $devices = UserDevice::getUserAllDevices('profile_uuid', 'a3d183bf-8463-4889-b8e6-38ff53ca50eb');
        
        foreach ($devices as $device) :

            //            PusherHelper::sendAndroidNotification($device['profile_uuid'], $data);
            if ($device['device_type'] == 'android' && !empty($device['user'])) :
                PusherHelper::sendAndroidNotification($device['user_id'], $data);
            elseif (!empty($device['device_token'])) :
                PushNotificationHelper::sendPushNotificationToIOS($device['device_token'], $iosNotificationDetail, $pemFile);
            endif;
        endforeach;

        return true;
    }

    public static function getBadgeCount($data, $type = null) {
        $badge_count = 0;
        if (isset($data['type']) && !empty($data['type']) && $data['type'] != 'chat') {
            if (isset($data['data']['receiver']['customer_uuid']) || isset($data['data']['receiver']['freelancer_uuid'])) {
                if (!empty($data['data']['receiver']['customer_uuid']) || !empty($data['data']['receiver']['freelancer_uuid'])) {

                    $receiver_id = $data['data']['receiver']['user_id'];
                    $receiver_uuid = !empty($data['data']['receiver']['customer_uuid']) ? $data['data']['receiver']['customer_uuid'] : $data['data']['receiver']['freelancer_uuid'];

                    $get_notification_count = \App\Notification::getNotificationCount('receiver_id', $receiver_id);
                    $get_message_unread_count = \App\Helpers\InboxHelper::getUnreadChatCount($receiver_uuid);
                    $badge_count = $get_notification_count + $get_message_unread_count;
                }
            }
        }
        if ((isset($type)) && (!empty($type)) && ($type == 'chat')) {
            if ($data['receiver_type'] == 'freelancer') {
                $receiver_id = \App\Freelancer::where('freelancer_uuid', $data['receiver_id'])->first()->user_id;
            } elseif ($data['receiver_type'] == 'customer') {
                $receiver_id = \App\Customer::where('customer_uuid', $data['receiver_id'])->first()->user_id;
            }
            $get_notification_count = \App\Notification::getNotificationCount('receiver_id', $receiver_id);
            $get_message_unread_count = \App\Helpers\InboxHelper::getUnreadChatCount($data['receiver_id']);
            $badge_count = $get_notification_count + $get_message_unread_count;
        }
        return $badge_count;
    }

    public static function setIosNotificationDataParameters($data, $badgeCount = 0) {
        return [
            'aps' => [
                'content-available' => 1,
                'alert' => $data['message'],
                'sound' => 'default',
                'badge' => $badgeCount,
            ],
            'type' => $data['type'],
            'data' => $data['data']
        ];
    }

    public static function setIosChatNotificationDataParameters($data, $badgeCount = 0) {
        return [
            'aps' => [
                'content-available' => 1,
                'mutable-content' => 1,
                'alert' => $data['alert-message'],
                'sound' => 'default',
                'badge' => $badgeCount,
            ],
            'type' => $data['type'],
            'data' => $data
        ];
    }



    public static function sendPushNotificationToIOS($token, $message) {
        $p_key = config("general.notifications.p8_key");
        $bundle_id = config("general.notifications.bundle_identifier");
        $key_id = config("general.notifications.key_id");
        $team_id = config("general.notifications.team_id");
        // development
//        $url = "https://api.sandbox.push.apple.com";
        // for production
        if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
        {
            $url = "https://api.push.apple.com";
        }
        else
        {
            $url = "https://api.sandbox.push.apple.com";
        }
        $p8_key = openssl_get_privatekey(($p_key));

        $payload = json_encode($message);
        $header = ['alg' => 'ES256', 'kid' => $key_id];
        $claims = ['iss' => $team_id, 'iat' => time()];
        $header_encoded = self::base64($header);
        $claims_encoded = self::base64($claims);
        $signature = '';
        openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $p8_key, 'sha256');
        $jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);
        // only needed for PHP prior to 5.5.24
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }
        $http2ch = curl_init();
        curl_setopt_array($http2ch, array(
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_URL => "$url/3/device/$token",
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => array(
                "apns-topic: {$bundle_id}",
                "authorization: bearer $jwt"
            ),
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => 1
        ));
        $result = curl_exec($http2ch);
        // dd($result,$http2ch);
        if ($result === FALSE) {
            throw new \Exception("Curl failed: " . curl_error($http2ch));
        }
        return curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
    }

    public static function base64($data) {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }

    public static function refineDeviceTokens($deviceTokens) {
        $refinedTokens = array();
        $index = 0;
        foreach ($deviceTokens as $key => $value) {
            if (!empty($value)) {
                $refinedTokens[$index] = str_replace(array(' ', '<', '>'), '', $value);
                $index++;
            }
        }
        return $refinedTokens;
    }

}

// end of helper class
