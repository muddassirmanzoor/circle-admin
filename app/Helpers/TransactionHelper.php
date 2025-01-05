<?php

namespace App\Helpers;


use App\BankDetail;
use App\FreelancerLocation;
use App\Appointment;
use App\BlockedTime;
use App\Classes;
use App\Freelancer;
use App\FreelancerCategory;
use App\FreelancerTransaction;
use App\FSubscriptionSetting;
use App\PaymentRequest;
use App\ProfileLog;
use App\Schedule;
use App\Session;
use App\SubscriptionSetting;
use App\Client;
use App\Customer;
use App\WalkinCustomer;
use DB;
use Illuminate\Support\Facades\Validator;
use App\PromoCode;

class TransactionHelper
{
    public static function setTransactionResposne($dataArray = [], $timezone = 'UTC', $login_user_type ='') {
        $response = [];
        if (!empty($dataArray)) {
            $total_count = 0;
            $index = 0;
            foreach ($dataArray as $key => $value) {
                if ($login_user_type == "freelancer" && $value['transaction_type'] == 'subscription' && isset($value['payment_due']) && count($value['payment_due']) > 0) {
                    foreach ($value['payment_due'] as $due_payment){
                        $response[] = self::setTransactionListCommonResponse($value, $timezone, $login_user_type, $due_payment);
                    }
                } else{
                    $response[] = self::setTransactionListCommonResponse($value, $timezone, $login_user_type);
                }
            }
        }
        return $response;
    }

    public static function setTransactionListCommonResponse($value, $timezone, $login_user_type, $due_payment = []){

        $response['name'] = self::setTransactionTitle($value);
        $response['uuid'] = $value['freelancer_transaction_uuid'];
        $response['freelancer_uuid'] = $value['freelancer_id'];
        $response['payment_due_uuid'] = isset($due_payment['payment_due_uuid']) ? $due_payment['payment_due_uuid'] : null;
        $response['freelancer_name'] = !empty($value['freelancer']) ? $value['freelancer']['first_name'] . ' ' . $value['freelancer']['last_name'] : null;
        $response['freelancer_profile_image'] = !empty($value['freelancer']['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['freelancer_profile_image'] . $value['freelancer']['profile_image'] : null;
        $response['type'] = self::setTransactionType($value);

        $response['amount'] = self::getTransactionAndPaymentDueAmountForList($value, $login_user_type, $due_payment);

        $response['transaction_id'] = !empty($value['transaction_id']) ? $value['transaction_id'] : null;
        $response['travel_fee'] = self::calculateTravelFee($value);
        $response['date'] = date('d M Y', strtotime($value['transaction_date']));
        $time = date('H:i:s', strtotime($value['transaction_date']));
        $response['time'] = CommonHelper::convertTimeToTimezone($time, 'UTC', $timezone);
        $response['customer_name'] = !empty($value['customer']) ? $value['customer']['first_name'] . ' ' . $value['customer']['last_name'] : null;
        $response['customer_uuid'] = !empty($value['customer']) ? $value['customer']['customer_uuid'] : null;
        $response['profile_image'] = !empty($value['customer']['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $value['customer']['profile_image'] : null;
        $response['payment_by'] = 'Visa';
        $response['transaction_status'] = self::prepareTransactionStatus($value);
        $response['currency'] = null;
        if ($login_user_type == "freelancer") {
            $response['currency'] = $value['freelancer']['default_currency'] ?? '';
        } elseif ($login_user_type == "customer") {
            $response['currency'] = $value['to_currency'];
        }
        $response['location'] = self::setTransactionLocation($value);
        $response['session_number'] = isset($due_payment['due_no']) ? $due_payment['due_no'] : 1;
        $response['total_session'] = isset($due_payment['total_dues']) ?  $due_payment['total_dues'] : 1;
        $response['is_package'] = false;
        $response['session_status'] = null;

        if (!empty($value['appointment'])) {
            $response['session_status'] = $value['appointment']['status'];
        } elseif (!empty($value['class_book'])) {
            $response['session_status'] = $value['class_book']['status'];
        }
        if (!empty($value['appointment']['package_uuid'])) {
            $response['session_number'] = $value['appointment']['session_number'];
            $response['total_session'] = $value['appointment']['total_session'];
            $response['is_package'] = true;
        } elseif (!empty($value['class_book']['package_uuid'])) {
            $response['session_number'] = $value['class_book']['session_number'];
            $response['total_session'] = $value['class_book']['total_session'];
            $response['is_package'] = true;
        }

        $response['detail_route'] = self::getDetailPageRoute($value, $due_payment);

        return $response;
    }

    public static function getDetailPageRoute($value, $due_payment){
        if ($value['transaction_type'] == 'subscription' && isset($due_payment['payment_due_uuid'])){
            return route('getTransactionDetail', [$value['freelancer_transaction_uuid'], $due_payment['payment_due_uuid']]);
        } else{
            return route('getTransactionDetail', $value['freelancer_transaction_uuid']);
        }
    }

    public static function setTransactionTitle($data = []) {
        $title = null;
        if (!empty($data['appointment'])) {
            $title = $data['appointment']['title'];
        } elseif (!empty($data['class_book']['class_object'])) {
            $title = $data['class_book']['class_object']['name'];
        } elseif (!empty($data['subscription'])) {
            $title = ucfirst($data['subscription']['subscription_setting']['type']) . ' subscription';
        }
        return $title;
    }

    public static function setTransactionType($data = []) {
        if ($data['transaction_type'] == 'appointment_bookoing') {
            if (!empty($data['appointment']['package_uuid'])) {
                return 'Package';
            } else {
                return 'Session';
            }
        } elseif ($data['transaction_type'] == 'class_booking') {
            if (!empty($data['class_book']['package_uuid'])) {
                return 'Package';
            } else {
                return 'Class';
            }
        } elseif ($data['transaction_type'] == 'subscription') {
            return 'Subscription';
        } elseif ($data['transaction_type'] == 'refund') {
            return 'Refund';
        }
        return $data['transaction_type'];
    }

    public static function getTransactionAndPaymentDueAmountForList($value, $login_user_type, $payment_due=[]){
        $amount = 0;
        if (isset($payment_due['payment_due_uuid'])){
            if (isset($value['freelancer']['default_currency']) && strtolower($value['freelancer']['default_currency']) == 'pound'){
                $amount = $payment_due['pound_amount'];
            }
            if (isset($value['freelancer']['default_currency']) && strtolower($value['freelancer']['default_currency']) == 'sar'){
                $amount = $payment_due['sar_amount'];
            }
        } else{
            if (!empty($login_user_type) && $login_user_type == 'customer'){
                $amount = $value['total_amount'];
            } else{
                if (isset($value['freelancer']['default_currency']) && strtolower($value['freelancer']['default_currency']) == strtolower($value['to_currency'])){
                    $amount = $value['total_amount'];
                } else{
                    if (!empty($value['exchange_rate']) && $value['exchange_rate'] > 0):
                        $amount = $value['total_amount'] / $value['exchange_rate'];
                        $amount = round($amount, 2);
                    endif;
                }
            }
        }
        return $amount;
    }

    public static function calculateTravelFee($data = []) {
        $travel_cost = null;
        if (!empty($data['appointment']) && $data['appointment']['location_type'] != 'freelancer' && $data['freelancer']['can_travel'] == 1) {
            if (!empty($data['appointment']['travelling_distance'])) {
                $travel_cost = self::distanceResponse($data['freelancer'], $data['appointment']['travelling_distance']);
            }
        }
        return $travel_cost;
    }

    public static function distanceResponse($data, $total_distance = 0) {
        $distance['distance'] = $total_distance;
        $distance['distance_cost'] = $data['travelling_cost_per_km'];
        $distance['total_distance_cost'] = $total_distance * $data['travelling_cost_per_km'];
        return $distance;
    }

    public static function prepareTransactionStatus($data) {
        $status = null;
        if (!empty($data['appointment'])) {
            if ($data['appointment']['status'] == "pending" || $data['appointment']['status'] == "confirmed") {
                $status = "pending";
            } elseif ($data['appointment']['status'] == "completed") {
                $status = "completed";
            } elseif ($data['appointment']['status'] == "cancelled") {
                $status = "cancelled";
            } elseif ($data['appointment']['status'] == "rejected") {
                $status = "rejected";
            }
        } elseif (!empty($data['class_book'])) {
            if ($data['class_book']['status'] == "pending" || $data['class_book']['status'] == "confirmed") {
                $status = "pending";
            } elseif ($data['class_book']['status'] == "completed") {
                $status = "completed";
            } elseif ($data['class_book']['status'] == "cancelled") {
                $status = "cancelled";
            } elseif ($data['class_book']['status'] == "rejected") {
                $status = "rejected";
            }
        }
        return $status;
    }

    public static function setTransactionLocation($data = []) {
        $location = null;
        if (!empty($data['appointment']) && !empty($data['appointment']['address'])) {
            $location = $data['appointment']['address'];
        } elseif (!empty($data['class_book']) && !empty($data['class_book']['class_object']['address'])) {
            $location = $data['class_book']['class_object']['address'];
        }
        return $location;
    }

    public static function setTransactionDetailResponse($data, $inputs) {



        $timezone = $inputs['local_timezone'];
        $response = [];
        if (!empty($data)) {
            if(isset($inputs['payment_due_uuid']) && !empty($inputs['payment_due_uuid']) && isset($data['payment_due']) && count($data['payment_due']) > 0){
                $due_key = array_search($inputs['payment_due_uuid'], array_column($data['payment_due'], 'payment_due_uuid'));
                $data['single_pay_due'] = $data['payment_due'][$due_key];
            }
            $response['uuid'] = $data['freelancer_transaction_uuid'];
            $response['freelancer_id'] = $data['freelancer_id'];
            $response['freelancer_name'] = !empty($data['freelancer']) ? $data['freelancer']['first_name'] . ' ' . $data['freelancer']['last_name'] : null;
            $response['freelancer_profile_image'] = !empty($data['freelancer']['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['freelancer_profile_image'] . $data['freelancer']['profile_image'] : null;
            $response['purchase_type'] = self::setTransactionType($data);
            $response['purchase_detail'] = self::getPurchaseDetails($data);
            $response['booking_name'] = self::setTransactionTitle($data);
            $response['transaction_id'] = !empty($data['transaction_id']) ? $data['transaction_id'] : null;
            $response['travel_fee'] = null;
            $response['travel_fee'] = self::calculateTravelFee($data);
            $response['purchase_date'] = date('d M Y', strtotime($data['transaction_date']));
            $time = date('H:i:s', strtotime($data['transaction_date']));
            $response['time'] = CommonHelper::convertTimeToTimezone($time, 'UTC', $timezone);
            $response['customer'] = !empty($data['customer']) ? $data['customer']['first_name'] . ' ' . $data['customer']['last_name'] : null;
            $response['customer_uuid'] = !empty($data['customer']) ? $data['customer']['customer_uuid'] : null;
            $response['profile_image'] = !empty($data['customer']['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['customer_profile_image'] . $data['customer']['profile_image'] : null;
//            $response['payment_by'] = $data['transaction_user'];
            $response['payment_by'] = !empty($data['payment_brand']) ? $data['payment_brand'] : 'VISA';
//            $response['status'] = $data['status'];
            $response['transaction_status'] = self::prepareTransactionStatus($data);
            $response['circl_charges'] = !empty($data['circl_charges']) ? $data['circl_charges'] : null;
            $response['hyperpay_fee'] = !empty($data['hyperpay_fee']) ? $data['hyperpay_fee'] : null;
            $response['location'] = self::setTransactionLocation($data);
            $response['online_link'] = self::setTransactionSessionLink($data);
            $response['type'] = self::checkBookingIsOnline($data) != null ? 'online' : 'face to face';
            $response['booking_date'] = self::getTransactionBookingTime($data);

            $exchange_rate = 1;
            $exchange_type = '';
            if ($inputs['login_user_type'] == "freelancer") {
                $response['currency'] = $data['freelancer']['default_currency'] ?? '';
                $response['service_fee'] = !empty($data['total_amount']) ? $data['total_amount'] : 0;
                $response['amount'] = $response['service_fee'] - ($response['circl_charges'] + $response['hyperpay_fee']);
                $response['amount'] = round($response['amount'], 2);
                $exchange_rate = $data['exchange_rate'];

                if (isset($data['freelancer']['default_currency']) && strtolower($data['freelancer']['default_currency']) != strtolower($data['to_currency'])){
                    $exchange_type = 'd';
                }
            }

            if ($inputs['login_user_type'] == "customer") {
                $response['currency'] = !empty($data['to_currency']) ? $data['to_currency'] : null;
                $response['service_fee'] = !empty($data['actual_amount']) ? $data['actual_amount'] : 0;
                $response['amount'] = $data['total_amount'] ?? 0;
            }
            $response['discount'] = self::getDiscountPrice($data, $response);
            $response['promo_code'] = self::getPromoCode($data);

            if (!empty($exchange_type)) {
                if ($exchange_type == 'd') {
                    $response['service_fee'] = round($response['service_fee'] / $exchange_rate, 2);
                    $response['circl_charges'] = round($response['circl_charges'] / $exchange_rate, 2);
                    $response['hyperpay_fee'] = round($response['hyperpay_fee'] / $exchange_rate, 2);
                    $response['amount'] = round($response['amount'] / $exchange_rate, 2);
                    $response['discount'] = round($response['discount'] / $exchange_rate, 2);
                }
                if ($exchange_type == 'm') {
                    $response['service_fee'] = round($response['service_fee'] * $exchange_rate, 2);
                    $response['circl_charges'] = round($response['circl_charges'] * $exchange_rate, 2);
                    $response['hyperpay_fee'] = round($response['hyperpay_fee'] * $exchange_rate, 2);
                    $response['amount'] = round($response['amount'] * $exchange_rate, 2);
                    $response['discount'] = round($response['discount'] * $exchange_rate, 2);
                }
            }

            if ($data['transaction_type'] == 'subscription'){
                $response['subscription_type'] = $data['subscription']['subscription_setting']['type'];
            }
            // get amount from payment_due table if transaction type is subscription
            if ($data['transaction_type'] == 'subscription' && isset($inputs['payment_due_uuid']) && !empty($inputs['payment_due_uuid']) && isset($data['payment_due']) && count($data['payment_due']) > 0){
                $response['due_amount'] = self::getPaymentDueAmountForSingleTransaction($data, $inputs);
                $response['due_date'] = isset($data['single_pay_due']['due_date']) ? date('d M Y', strtotime($data['single_pay_due']['due_date'])) : null;
            }

            $session_number_info = self::getSessionNumberDetails($data);
            $response['total_session'] = $session_number_info['total_session'];
            $response['session_number'] = $session_number_info['session_number'];



            if (!empty($data['appointment'])) {
                $response['session_status'] = $data['appointment']['status'];
            } elseif (!empty($data['class_book'])) {
                $response['session_status'] = $data['class_book']['status'];
            }
        }
        return $response;
    }

    public static function getSessionNumberDetails($data) {

        $resp = [
            'session_number' => 0,
            'total_session' => 0
        ];

        if ($data['transaction_type'] == 'appointment_bookoing') {
            if (!empty($data['appointment']['session_number']) && !empty($data['appointment']['total_session'])) {
                $resp['session_number'] = $data['appointment']['session_number'];
                $resp['total_session'] = $data['appointment']['total_session'];
            }
        }

        if ($data['transaction_type'] == 'class_booking') {
            if (!empty($data['class_book']['session_number']) && !empty($data['class_book']['total_session'])) {
                $resp['session_number'] = $data['class_book']['session_number'];
                $resp['total_session'] = $data['class_book']['total_session'];
            }
        }

        if ($data['transaction_type'] == 'subscription') {
            if (isset($data['single_pay_due'])) {
                $resp['session_number'] = $data['single_pay_due']['due_no'];
                $resp['total_session'] = $data['single_pay_due']['total_dues'];
            }
        }

        return $resp;
    }

    public static function getPaymentDueAmountForSingleTransaction($data, $inputs){
        $amount = 0;
        $freelancer_currency = isset($data['freelancer']['default_currency']) ? $data['freelancer']['default_currency'] : '';
        if (isset($data['single_pay_due'])){
            if (strtolower($freelancer_currency) == 'pound'){
                $amount = $data['single_pay_due']['pound_amount'];
            } else{
                $amount = $data['single_pay_due']['sar_amount'];
            }
        }

        return round($amount, 2);
    }

    public static function getPromoCode($data) {

        $code = '';
        if ($data['transaction_type'] == 'appointment_bookoing') {
            $code = $data['appointment']['promo_code']['coupon_code'] ?? '';
        }

        if ($data['transaction_type'] == 'class_booking') {
            $code = $data['class_book']['promo_code']['coupon_code'] ?? '';
        }

        return $code;
    }

    public static function getDiscountPrice($data, $response) {

        $discounted_price = 0;
        if ($data['transaction_type'] == 'appointment_bookoing') {
            $discounted_price = $data['appointment']['discounted_price'] ?? 0;
        }

        if ($data['transaction_type'] == 'class_booking') {
            $discounted_price = $data['class_book']['discounted_price'] ?? 0;
        }

        $discount = ($discounted_price > 0) ? $response['service_fee'] - $discounted_price : 0;
        $discount = round(abs($discount), 2);

        return $discount;
    }

    public static function getTransactionBookingTime($data = []) {
        if ($data['transaction_type'] == 'appointment_bookoing') {
            return date('d M Y', strtotime($data['appointment']['appointment_date']));
        } elseif ($data['transaction_type'] == 'class_booking') {
            return date('d M Y', strtotime($data['class_book']['schedule']['class_date']));
        } elseif ($data['transaction_type'] == 'subscription') {
            return date('d M Y', strtotime($data['subscription']['subscription_date']));
        }
    }

    public static function checkBookingIsOnline($data = []) {
        $link = null;
        if (isset($data['appointment']['is_online']) && !empty($data['appointment']['is_online'])) {
            $link = $data['appointment']['is_online'];
        } elseif (!empty($data['class_book']) && !empty($data['class_book']['class_object']['online_link'])) {
            $link = $data['class_book']['class_object']['online_link'];
        }
        return $link;
    }

    public static function setTransactionSessionLink($data = []) {
        $link = null;
        if (!empty($data['appointment']) && !empty($data['appointment']['online_link'])) {
            $link = $data['appointment']['online_link'];
        } elseif (!empty($data['class_book']) && !empty($data['class_book']['class_object']['online_link'])) {
            $link = $data['class_book']['class_object']['online_link'];
        }
        return $link;
    }

    public static function getPurchaseDetails($data) {
        $name = '';
        if ($data['transaction_type'] == 'appointment_bookoing') {
            if (isset($data['appointment']['package']['package_name'])) {
                $name = $data['appointment']['package']['package_name'];
            } else {
                $name = "Single Appointment";
            }
        }

        if ($data['transaction_type'] == 'class_booking') {
            if (isset($data['class_book']['package']['package_name'])) {
                $name = $data['class_book']['package']['package_name'];
            } else {
                $name = "Single Class";
            }
        }

        if ($data['transaction_type'] == 'subscription') {
            $name = 'Subscription';
        }

        return $name;
    }
}
