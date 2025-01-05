<?php

namespace App\Helpers;

Class ClientResponseHelper {
    /*
      |--------------------------------------------------------------------------
      | ClientResponseHelper that contains all the client methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use client processes
      |
     */

    public static function prepareClientResponse($data = []) {
        $response = [];
        $response['client_uuid'] = $data['client_uuid'];
        $response['customer_uuid'] = $data['customer_uuid'];
        $response['freelancer_uuid'] = $data['freelancer_uuid'];
        return $response;
    }

    public static function prepareClientDetailsResponse($data = []) {
        $response = [];
        if (!empty($data)) {
            $response['customer_uuid'] = !empty($data['customer_uuid']) ? $data['customer_uuid'] : $data['walkin_customer_uuid'];
            $response['first_name'] = $data['first_name'];
            $response['last_name'] = $data['last_name'];
            $response['email'] = !empty($data['email']) ? $data['email'] : null;
            $response['phone_number'] = !empty($data['email']) ? $data['email'] : null;
            $response['profile_image'] = !empty($data['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $data['profile_image'] : null;
            $response['profile_images'] = CustomerResponseHelper::customerProfileImagesResponse($data['profile_image']);
            $response['appointments_count'] = $data['appointments_count'];
            $response['appointments_revenue'] = $data['appointments_revenue'];
        }
        return $response;
    }

    public static function prepareMixedClientsResponse($customers = [], $clients = []) {
        if (!empty($customers) && !empty($clients)) {
            foreach ($customers as $key => $customer) {
                foreach ($clients as $client) {
                    if ($client['customer_id'] == $customer['customer_id']) {
                        $customers[$key]['client_id'] = $client['id'];
                        break;
                    }
                }
            }
        }
        return $customers;
    }

}

?>