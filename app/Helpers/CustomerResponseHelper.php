<?php

namespace App\Helpers;

Class CustomerResponseHelper {
    /*
      |--------------------------------------------------------------------------
      | CustomerResponseHelper that contains all the customer methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use customer processes
      |
     */

    public static function prepareSignupResponse($data = []) {
        $response = [];
        $response['customer_uuid'] = $data['customer_uuid'];
        $response['first_name'] = !empty($data['first_name']) ? $data['first_name'] : null;
        $response['last_name'] = !empty($data['last_name']) ? $data['last_name'] : null;
        $response['email'] = !empty($data['email']) ? $data['email'] : null;
        $response['phone_number'] = !empty($data['phone_number']) ? $data['phone_number'] : null;
        $response['country_code'] = !empty($data['country_code']) ? $data['country_code'] : null;
        $response['country_name'] = !empty($data['country_name']) ? $data['country_name'] : null;
        $response['profile_images'] = self::customerProfileImagesResponse($data['profile_image']);
        $response['profile_image'] = !empty($data['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $data['profile_image'] : null;
        $response['cover_image'] = !empty($data['cover_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_cover_image'] . $data['cover_image'] : null;
        $response['cover_images'] = self::customerCoverImagesResponse($data['cover_image']);
        $response['gender'] = !empty($data['gender']) ? $data['gender'] : null;
        $response['onboard_count'] = !empty($data['onboard_count']) ? $data['onboard_count'] : null;
        if (!empty($data['address'])) {
            $response['location']['address'] = !empty($data['address']) ? $data['address'] : null;
            $response['location']['lat'] = !empty($data['lat']) ? $data['lat'] : null;
            $response['location']['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        } else {
            $response['location'] = null;
        }
        return $response;
    }

    public static function customerProfileImagesResponse($image_key = null) {
        $response = null;
        $response['1122'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_1122'] . $image_key : null;
        $response['420'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_420'] . $image_key : null;
        $response['336'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_336'] . $image_key : null;
        $response['240'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_240'] . $image_key : null;
        $response['96'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_96'] . $image_key : null;
        $response['orignal'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $image_key : null;
        return $response;
    }

    public static function customerCoverImagesResponse($image_key = null) {
        $response = null;
        $response['1122'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_1122'] . $image_key : null;
        $response['420'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_420'] . $image_key : null;
        $response['336'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_336'] . $image_key : null;
        $response['240'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_240'] . $image_key : null;
        $response['96'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_96'] . $image_key : null;
        $response['orignal'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_cover_image'] . $image_key : null;
        return $response;
    }

    public static function prepareLoginResponse($data = []) {
        $response = null;
        $response['customer_uuid'] = $data['customer_uuid'];
        $response['first_name'] = !empty($data['first_name']) ? $data['first_name'] : null;
        $response['last_name'] = !empty($data['last_name']) ? $data['last_name'] : null;
        $response['email'] = !empty($data['email']) ? $data['email'] : null;
        $response['country_code'] = !empty($data['country_code']) ? $data['country_code'] : null;
        $response['country_name'] = !empty($data['country_name']) ? $data['country_name'] : null;
        $response['phone_number'] = !empty($data['phone_number']) ? $data['phone_number'] : null;
        $response['profile_images'] = self::customerProfileImagesResponse($data['profile_image']);
        $response['profile_image'] = !empty($data['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $data['profile_image'] : null;
        $response['cover_image'] = !empty($data['cover_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_cover_image'] . $data['cover_image'] : null;
        $response['cover_images'] = self::customerCoverImagesResponse($data['cover_image']);
        $response['gender'] = !empty($data['gender']) ? $data['gender'] : null;
        $response['onboard_count'] = !empty($data['onboard_count']) ? $data['onboard_count'] : null;
        if (!empty($data['address'])) {
            $response['location']['address'] = !empty($data['address']) ? $data['address'] : null;
            $response['location']['lat'] = !empty($data['lat']) ? $data['lat'] : null;
            $response['location']['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        } else {
            $response['location'] = null;
        }
        return $response;
    }

    public static function prepareGuestLoginResponse($data = []) {
        $response = null;
        $response['customer_uuid'] = $data['customer_uuid'];
        $response['first_name'] = !empty($data['first_name']) ? $data['first_name'] : null;
        $response['last_name'] = !empty($data['last_name']) ? $data['last_name'] : null;
        $response['email'] = !empty($data['email']) ? $data['email'] : null;
        $response['phone_number'] = !empty($data['phone_number']) ? $data['phone_number'] : null;
        $response['profile_images'] = self::customerProfileImagesResponse($data['profile_image']);
        $response['profile_image'] = !empty($data['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $data['profile_image'] : null;
        $response['cover_image'] = !empty($data['cover_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_cover_image'] . $data['cover_image'] : null;
        $response['cover_images'] = self::customerCoverImagesResponse($data['cover_image']);
        $response['gender'] = !empty($data['gender']) ? $data['gender'] : null;
        $response['location'] = null;
        return $response;
    }

    public static function customerListResponse($data = []) {
        $response = [];
        if (!empty($data)) {
            foreach ($data as $key => $customer) {
                $response[$key]['customer_id'] = $customer['id'];
                $response[$key]['customer_uuid'] = $customer['customer_uuid'];
                $response[$key]['first_name'] = $customer['first_name'];
                $response[$key]['last_name'] = $customer['last_name'];
                $response[$key]['email'] = $customer['email'];
                $response[$key]['phone_number'] = $customer['phone_number'];
                $response[$key]['gender'] = $customer['gender'];
            }
        }
        return $response;
    }

    public static function updateCustomerListResponse($data = []) {
        $response = [];
        if (!empty($data)) {
            foreach ($data as $key => $customer) {
                $response[$key]['customer_uuid'] = !empty($customer['customer_uuid']) ? $customer['customer_uuid'] : null;
                $response[$key]['first_name'] = !empty($customer['first_name']) ? $customer['first_name'] : null;
                $response[$key]['last_name'] = !empty($customer['last_name']) ? $customer['last_name'] : null;
                $response[$key]['email'] = !empty($customer['email']) ? $customer['email'] : null;
                $response[$key]['phone_number'] = !empty($customer['phone_number']) ? $customer['phone_number'] : null;
                $response[$key]['gender'] = !empty($customer['gender']) ? $customer['gender'] : null;
                $response[$key]['onboard_count'] = !empty($customer['onboard_count']) ? $customer['onboard_count'] : null;
                $response[$key]['country_code'] = !empty($customer['country_code']) ? $customer['country_code'] : null;
                $response[$key]['country_name'] = !empty($customer['country_name']) ? $customer['country_name'] : null;
                $response[$key]['profile_images'] = self::customerProfileImagesResponse($customer['profile_image']);
                $response[$key]['profile_image'] = !empty($customer['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $customer['profile_image'] : null;
                if (!empty($customer['address'])) {
                    $response[$key]['location']['address'] = !empty($customer['address']) ? $customer['address'] : null;
                    $response[$key]['location']['lat'] = !empty($customer['lat']) ? $customer['lat'] : null;
                    $response[$key]['location']['lng'] = !empty($customer['lng']) ? $customer['lng'] : null;
                } else {
                    $response[$key]['location'] = null;
                }
            }
        }
        return $response;
    }

}

?>