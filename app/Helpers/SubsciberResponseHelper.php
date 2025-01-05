<?php

namespace App\Helpers;

Class SubscriberResponseHelper {
    /*
      |--------------------------------------------------------------------------
      | SubscriberResponseHelper that contains all the subscriber methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use subscriber processes
      |
     */

    public static function makeSubscriberResponse($data = []) {
        $response = [];
        if (!empty($data)) {
            foreach ($data as $key => $row) {
                $response[$key]['subscription_uuid'] = $row['subscription_uuid'];
                $response[$key]['subscriber_uuid'] = $row['customer']['customer_uuid'];
                $response[$key]['subscriber_id'] = $row['subscriber_id'];
                $response[$key]['subscribed_id'] = $row['subscribed_id'];
                $response[$key]['first_name'] = !empty($row['customer']['first_name']) ? $row['customer']['first_name'] : null;
                $response[$key]['last_name'] = !empty($row['customer']['last_name']) ? $row['customer']['last_name'] : null;
                $response[$key]['profile_image'] = !empty($row['customer']['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $row['customer']['profile_image'] : null;
                // $response[$key]['profile_images'] = CustomerResponseHelper::customerProfileImagesResponse($row['customer']['profile_image']);
                $response[$key]['date'] = date('Y-m-d', strtotime($row['subscription_date']));
                $response[$key]['subscription_type'] = !empty($row['subscription_setting']['type']) ? $row['subscription_setting']['type'] : null;
                $response[$key]['subscription_start'] = !empty($row['subscription_date']) ? date('Y-m-d H:i a', strtotime($row['subscription_date'])) : null;
                $response[$key]['subscription_end'] = !empty($row['subscription_end_date']) ? date('Y-m-d H:i a', strtotime($row['subscription_end_date'])) : null;
                $response[$key]['total_amount'] = !empty($row['purchase_subscription']['total_amount']) ? $row['purchase_subscription']['total_amount'] : 0;
                $response[$key]['subscription_type'] = !empty($row['subscription_setting']['type']) ? $row['subscription_setting']['type'] : null;
                $response[$key]['subscription_type'] = !empty($row['subscription_setting']['type']) ? $row['subscription_setting']['type'] : null;
                $response[$key]['subscription_type'] = !empty($row['subscription_setting']['type']) ? $row['subscription_setting']['type'] : null;
                $response[$key]['currency'] = !empty($row['customer']['default_currency']) ? $row['customer']['default_currency'] : null;
            }
        }
        return $response;
    }

}

?>
