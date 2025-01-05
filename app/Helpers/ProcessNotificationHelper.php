<?php

namespace App\Helpers;

use App\User;
use App\UserDevice;
use App\Freelancer;
use App\Customer;
use App\Notification;
use App\NotificationSetting;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;

/*
  All methods related to user notifications will be here
 */

class ProcessNotificationHelper {

    public static function sendPaymentStatusNotificationToFreelancer($data = [], $notificationType = 'payout_status')
    {
        $payout_uuid = $data['payout_uuid'];
        $receiver_data = self::processPaymentStatusReceiver($data);
        $inputs = $data;
        $data = [];
        $data['receiver'] = $receiver_data['receiver'];
        $data['payout_uuid'] = $payout_uuid;
        // $data['subscription']['subscription_uuid'] = $subscription['subscription_uuid'];
        $messageData = [
            'type' => $notificationType,
            'message' => 'Your payment status has been updated.',
            'save_message' => ' payment status has been updated',
            'data' => $data,
        ];
        $notification_inputs = self::preparePaymentStatusNotificationToFreelancerInputs($messageData);
        $save_notification = Notification::addNotification($notification_inputs);
        return PushNotificationHelper::send_notification_to_user_devices($receiver_data['receiver']['user_id'], $messageData);
    }
    public static function preparePaymentStatusNotificationToFreelancerInputs($messageData = []) {
        $notification_inputs = [];

        if (isset($messageData['data']['receiver']['freelancer_uuid'])) {
            $notification_inputs['receiver_id'] = $messageData['data']['receiver']['user_id'];
            $notification_inputs['sender_id'] = $messageData['data']['receiver']['user_id'];
        }
        $notification_inputs['notification_uuid'] = $messageData['data']['payout_uuid'];
        $notification_inputs['message'] = $messageData['save_message'];
        $notification_inputs['notification_type'] = $messageData['type'];
        $notification_inputs['is_read'] = 0;
        return $notification_inputs;
    }
    public static function processPaymentStatusReceiver($inputs = []) {
        $receiver = ['receiver' => [], 'device_token' => ''];
        $profile = Freelancer::checkFreelancer('id', $inputs['freelancer_id']);
        if (!empty($profile)) {
            $receiver['receiver'] = self::processFreelancer($profile);
            $device = UserDevice::getUserDevice('user_id', $profile['user_id']);
            $receiver['device_token'] = $device['device_token'];
        }
        return $receiver;
    }

    public static function processFreelancer($data = []) {
        $response = [];
        if (!empty($data)) {
            $response['freelancer_uuid'] = $data['freelancer_uuid'];
            $response['freelancer_id'] = $data['id'];
            $response['user_id'] = $data['user_id'];
            $response['first_name'] = (!empty($data['first_name'])) ? $data['first_name'] : "";
            $response['last_name'] = (!empty($data['last_name'])) ? $data['last_name'] : "";
            $response['profile_image'] = !empty($data['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['freelancer_profile_image'] . $data['profile_image'] : null;
            //            $response['profile_images'] = FreelancerResponseHelper::freelancerProfileImagesResponse($data['profile_image']);
        }
        return $response;
    }
}

// end of helper class
