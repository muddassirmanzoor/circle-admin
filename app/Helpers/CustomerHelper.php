<?php

namespace App\Helpers;

use App\Address;
use App\Appointment;
use App\ClassBooking;
use App\User;
use App\BlockedTime;
use App\Classes;
use App\Customer;
use App\FreelancerCategory;
use App\FreelancerCreatedSubCategory;
use App\Helpers\CommonHelper;
use App\Schedule;
use App\Session;
use App\Subscription;
use App\SubscriptionSetting;
use App\Interest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerHelper {

    public static function getCustomerDetail($customer_id) {
        $detail = Customer::getCustomerDetail($customer_id);
        return self::getCustomerDetailArr($detail);
    }

    public static function getCustomerDetailArr($detail) {
        $resp = array();
        $resp['customer_uuid'] = $detail['customer_uuid'];
        $resp['first_name'] = $detail['first_name'];
        $resp['last_name'] = $detail['last_name'];
        $resp['email'] = $detail['email'];
        $resp['phone'] = $detail['phone_number'];
        $resp['gender'] = $detail['gender'];
        $resp['dob'] = CommonHelper::setUserDateFormat($detail['dob']);
        $resp['profession'] = $detail['profession'];
        $resp['company'] = $detail['company'];
        $resp['profile_image'] = !empty($detail['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_thumb_240'] . $detail['profile_image'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image';
        $resp['country_name'] = $detail['country_name'];
        $resp['country_code'] = $detail['country_code'];
        return $resp;
    }

    public static function getCustomerAppointments($customerId) {
        return self::getAppointmentDetail($customerId, 'normal');
    }

//    public static function getcustomerClasses($customerId) {
//        return self::getAppointmentDetail($customerId, 'class');
//    }

    public static function getcustomerClasses($customerId) {
        $data = array();
        $customerClasses = ClassBooking::getCustomerClasses('customer_id',$customerId);
        foreach ($customerClasses as $key => $row) {
            $start_date_time = $row['class_related']['start_date'].' '.$row['schedule']['from_time'];
            $end_date_time = $row['class_related']['end_date'].' '.$row['schedule']['to_time'];
            $data[] = array(
                'id' => $key + 1,
                'appointment_title' => !empty($row['class_related']['name']) ? ucfirst($row['class_related']['name']) : '',
                'no_of_students' => $row['class_related']['no_of_students'],
                'appointment_price' => $row['class_related']['price'],
                'class_notes' => $row['class_related']['notes'] ?? '',
                'class_status' => $row['class_related']['status'] ?? '',
                'class_address' => $row['class_related']['address'] ?? '',
                'appointment_start_time' => CommonHelper::convertDateTimeToTimezone($start_date_time,$row['schedule']['saved_timezone'], $row['schedule']['local_timezone']),
                'appointment_end_time' => CommonHelper::convertDateTimeToTimezone($end_date_time,$row['schedule']['saved_timezone'], $row['schedule']['local_timezone']),
                'appointment_status' => ucfirst($row['class_related']['status']),
                'customer_id' => $row['customer_id'],
                'appointment_freelancer' => $row['class_related']['freelancer']['first_name'],
            );
        }
        return $data;
    }

    public static function getCustomerSessions($customerId) {
        return self::getAppointmentDetail($customerId, 'session');
    }

    public static function getServiceArr($arr) {
        $data = array();
        foreach ($arr as $row) {
            $data[] = $row['service']['name'];
        }

        return $data;
    }

    public static function getAppointmentDetail($customerId = '', $type = '') {
        $data = array();
        $customerAppointments = Appointment::getCustomerAppointments($customerId, $type);
        foreach ($customerAppointments as $key => $row) {
            $appointment_freelancer = $row['appointment_freelancer'];
            $data[] = array(
                'id' => $key + 1,
                'appointment_title' => ucfirst($row['title']),
                'customer_id' => $row['customer_id'],
                'appointment_freelancer' => $appointment_freelancer['first_name'],
                'service_arr' => self::getServiceArr($row['appointment_services']),
                'type' => ucfirst($row['price']),
                'appointment_date' => CommonHelper::setUserDateFormat($row['appointment_date']),
                'appointment_price' => $row['price'],
                'appointment_start_time' => CommonHelper::setUserTimeFormat($row['from_time']),
                'appointment_end_time' => CommonHelper::setUserTimeFormat($row['to_time']),
                'appointment_status' => ucfirst($row['status'])
            );
        }
        return $data;
    }

    public static function getAllCustomers() {
        $where = ['is_archive' => 0];
        return Customer::getAllCustomers($where);
    }

    public static function getAllVerifiedCustomers() {
        $where = ['is_active' => 1, 'is_archive' => 0];
        $result = Customer::getAllCustomers($where);
        return !empty($result) ? $result->toArray() : [];
    }

    public static function getActiveCustomers() {
        $where = array(
            'is_active' => 1,
            'is_archive' => 0,
            //'is_verified' => 1
        );
        return Customer::getAllCustomers($where);
    }

    public static function getPendingCustomers() {
        $title = "Pending";
        $where = array(
            'is_verified' => 0,
            'is_archive' => 0
        );
        return Customer::getAllCustomers($where);
    }

    public static function getBlockedCustomers() {
        $where = array(
            'is_active' => 0,
            'is_verified' => 1,
            'is_archive' => 0
        );
        return Customer::getAllCustomers($where);
    }

    public static function getDeletedCustomers() {
        $title = "Deleted";
        $customers = array();
        $where = array(
            'is_active' => 0,
            'is_archive' => 1
        );
        $customers_a = Customer::getAllCustomers($where);
        if (!empty($customers_a) && count($customers_a) > 0) {
            foreach ($customers_a as $key => $x) {
                $customers[$key] = $x;
            }
        }

        $where = array(
            'is_active' => 1,
            'is_archive' => 1
        );
        $customers_b = Customer::getAllCustomers($where);
        if (!empty($customers_b) && count($customers_b) > 0) {
            foreach ($customers_b as $key => $y) {
                $customers[$key] = $y;
            }
        }

        return $customers;
    }

    public static function updateCustomerProfile($inputs, $type = 'customer') {
        $validation = Validator::make($inputs, CommonValidationHelper::createCustomerRules()['rules'], CommonValidationHelper::createCustomerRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }

        $service = new ApiService();
        $res = $service->guzzleRequest('POST','updateCustomer', $inputs);
        if($res['success']=="true") {
            return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['update_'.$type.'_success']);
        }
        else {
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
        }
    }

    public static function getCustomerSubscriptions($customerId) {
        $data = array();
        $subscriptions = Subscription::getCustomerSubscriptions($customerId);
        foreach ($subscriptions as $key => $row) {
            $subscription_setting = $row['subscription_setting'];
            $subscribed_detail = $row['subscribed_detail'];
            $data[] = array(
                'id' => $key + 1,
                'subscription_uuid' => $row['subscription_uuid'],
                'freelancer_name' => ucfirst($subscribed_detail['first_name']) . ' ' . ucfirst($subscribed_detail['last_name']),
                'type' => ucfirst($subscription_setting['type']),
                'price' => $subscription_setting['price'],
                'date' => CommonHelper::setUserDateFormat($row['subscription_date'])
            );
        }
        return $data;
    }

    public static function createCustomerByAdmin($inputs) {
        $validation = Validator::make($inputs, CommonValidationHelper::createCustomerRules()['rules'], CommonValidationHelper::createCustomerRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        if ($inputs['dob'] != 1970 - 01 - 01) {
            $inputs['dob'] = CommonHelper::setDbDateFormat($inputs['dob']);
        } else {
            unset($inputs['dob']);
        }
        $inputs['password'] = CommonHelper::convertToHash($inputs['password']);
        $inputs['is_verified'] = 1;
        $inputs['is_active'] = 1;
        $save_user = User::saveUser();
        $inputs['user_id'] = $save_user['id'];
        $create_customer = Customer::createCustomer($inputs);
        if (!$create_customer) {
            DB::rollBack();
            return redirect()->back()->with('error_message', CustomerMessageHelper::getMessageData('error')['create_error']);
        }
        DB::commit();
        return redirect()->back()->with('success_message', CustomerMessageHelper::getMessageData('success')['create_success']);
    }

    public static function updateCustomerPicture($customer_id, $file) {
        $inputs = [];
        if (!empty($file)) {
            $file_size = $file->getSize();
            if($file_size > 1000000) {
               return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['max_size_error']);
            }
            $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads']);
            if (!$upload_image['success']) {
                return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['image_not_uploaded']);
            }
            $inputs['profile_image'] = $upload_image['file_name'];
            //return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['file_not_found']);
        }
        $inputs['customer_uuid'] = $customer_id;
        return self::updateCustomerProfile($inputs, 'image');
    }

    public static function saveCustomerSubscription($inputs) {
        $validation = Validator::make($inputs, CustomerValidationHelper::saveCustomerSubsRules()['rules'], FreelancerValidationHelper::saveFreelancerSubsRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        if (!empty($inputs['subscription_uuid'])) {
            $create_subs = Subscription::updateSubscription(['subscription_uuid' => $inputs['subscription_uuid']], $inputs);
        } else {
            $inputs['subscription_date'] = date('Y-m-d');
            $create_subs = Subscription::createSubscription($inputs);
        }
        if (!$create_subs) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['save_error']);
        }
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
    }

    public static function getSubscriptionDetail($inputs) {
        $validation = Validator::make($inputs, CustomerValidationHelper::getSubscriptionDetailRules()['rules'], CustomerValidationHelper::getSubscriptionDetailRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $subscriptions = Subscription::getSubscriptionDetail($inputs['subscription_uuid']);
        if (!$subscriptions) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['empty_error']);
        }
        $freelancer_subsciptions = FreelancerHelper::getFreelancerSubscriptions($subscriptions['subscribed_uuid']);
        $subscriptions['freelancer_subsciptions'] = $freelancer_subsciptions;
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request'], $subscriptions);
    }

    public static function getSearchedCustomer($search)
    {
        $customers = Customer::where('first_name', 'like', '%' .$search . '%')->limit(10)->get();
        return $customers;
    }

    public static function getCustomerIntrusts($customer_id){
        return Interest::getCustomerIntrusts('customer_id', $customer_id);
    }
}
