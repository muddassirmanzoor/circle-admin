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
use App\Package;
use App\PaymentRequest;
use App\ProfileLog;
use App\Schedule;
use App\Session;
use App\SubscriptionSetting;
use App\Client;
use App\Customer;
use App\Exports\FundsTransferCSV;
use App\FreelancerEarning;
use App\FreelancerWithdrawal;
use App\FundsTransfer;
use App\WalkinCustomer;
use Illuminate\Support\Facades\Validator;
use App\PromoCode;
use App\Purchases;
use App\Profession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FreelancerHelper {

    public static function getFreelancerDetail($freelancerId) {
        $detail = Freelancer::getFreelancerDetail($freelancerId);
        return self::getFreelancerDetailArr($detail);
    }

    public static function getFreelancerDetailArr($detail) {
        $resp = array();
        $resp['freelancer_uuid'] = $detail['freelancer_uuid'];
        $resp['first_name'] = $detail['first_name'];
        $resp['last_name'] = $detail['last_name'];
        $resp['email'] = $detail['email'];
        $resp['phone'] = $detail['phone_number'];
        $resp['gender'] = $detail['gender'];
        $resp['dob'] = CommonHelper::setUserDateFormat($detail['dob']);
        $resp['profession'] = $detail['profession'];
        $resp['profession_id'] = $detail['profession_id'];
        $resp['company'] = $detail['company'];
        $resp['currency'] = $detail['default_currency'];
        $resp['default_currency'] = $detail['default_currency'];
        $resp['profile_image'] = !empty($detail['profile_image']) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths['freelancer_profile_thumb_240'] . $detail['profile_image'] : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image';
        $resp['country_name'] = $detail['country_name'];
        $resp['country_code'] = $detail['country_code'];
        $resp['bank_detail'] = isset($detail->bank_detail) ? $detail->bank_detail->toArray() : [];

        return $resp;
    }

    public static function getFreelancerCategories($freelancerId) {
        return FreelancerCategory::getFreelancerCategory($freelancerId);
    }

    public static function getFreelancerSubcategories($freelancerId) {
        $data = array();
        $selectedSubCat = FreelancerCategory::getFreelancerServices($freelancerId);
        // echo '<pre>';
        // print_r($selectedSubCat);exit;
        foreach ($selectedSubCat as $key => $row) {
            $category = $row['category'];
            $sub_category = $row['sub_category'];
            $data[] = array(
                'id' => $key + 1,
                'sub_category_id' => $row['sub_category_id'],
                'category_name' => $category['name'],
                'sub_category_name' => $row['name'],
                'price' => $row['price']
            );
        }
        // echo '<pre>';
        //  print_r($data);exit;
        return $data;
    }

    public static function getFreelancerClassess($freelancerId) {
        $data = array();
        $freelancerClassess = Classes::getFreelancerClassSchedule('freelancer_id',$freelancerId);
        foreach ($freelancerClassess as $key => $row) {
            $start_date_time = $row['start_date'].' '.$row['schedule'][0]['from_time'];
            $end_date_time = $row['end_date'].' '.$row['schedule'][0]['to_time'];
            $data[] = array(
                'id' => $key + 1,
                'class_name' => !empty($row['name']) ? $row['name'] : '',
                'no_of_students' => $row['no_of_students'],
                'class_price' => $row['price'],
                'class_notes' => $row['notes'] ?? '',
                'class_status' => $row['status'] ?? '',
                'class_address' => $row['address'] ?? '',
                'class_start_time' => CommonHelper::convertDateTimeToTimezone($start_date_time,$row['schedule'][0]['saved_timezone'], $row['schedule'][0]['local_timezone']),
                'class_end_time' => CommonHelper::convertDateTimeToTimezone($end_date_time,$row['schedule'][0]['saved_timezone'], $row['schedule'][0]['local_timezone']),
                'class_uuid' => $row['class_uuid']
            );
        }
        return $data;
    }

    public static function getFreelancerPackages($freelancerId)
    {
        return Package::getAllPackages('freelancer_id', $freelancerId);
    }

    public static function getFreelancerClass($class_id) {

        $service = new ApiService();

        $url = 'getClassDetails?class_uuid=' . $class_id . "&local_timezone=UTC";

        $res = $service->guzzleRequest('GET', $url, []);
        return $res['success'] ? $res["data"] : false;
    }

    public static function getFreelancerPackage($package_uuid) {

        $service = new ApiService();
        //requires user login type but admin does not exists in api code. Returns invalid data because of package_uuid not found.

        //        $url = 'getAllPackages?currency=SAR&login_user_type=freelancer&freelancer_uuid='.'f3ff91ac-fadd-4598-8422-315d166d7c50'."&local_timezone=UTC";
        $url = 'getPackageDetails?currency=SAR&customer_uuid=5d0644c8-432f-418d-a2e1-f3dc316c5988&login_user_type=freelancer&package_uuid=' . 'a899285b-f29a-4e15-a476-955cd70dd13a' . "&logged_in_uuid=" . Auth::user()->user_uuid . "&local_timezone=UTC";
        //        $url = 'getPackageBasicDetails?package_uuid='.$package_uuid."&currency=SAR&login_user_type=freelancer&logged_in_uuid=".Auth::user()->user_uuid."&local_timezone=UTC";

        $res = $service->guzzleRequest('GET', $url, []);
        return $res['success'] ? $res["data"] : false;
    }

    public static function getFreelancerSessions($freelancerId) {
        $data = array();
        $freelancerSessions = Session::getFreelancerSessions($freelancerId);
        foreach ($freelancerSessions as $key => $row) {
            $data[] = array(
                'id' => $key + 1,
                'service_arr' => self::getServiceArr($row['session_services']),
                'session_date' => CommonHelper::setUserDateFormat($row['session_date']),
                'location' => (!empty($row['location'])) ? $row['location'] : '',
                'session_price' => (!empty($row['price'])) ? $row['price'] : '',
                'session_notes' => $row['notes'],
                'session_start_time' => CommonHelper::setUserTimeFormat($row['from_time']),
                'session_end_time' => CommonHelper::setUserTimeFormat($row['to_time']),
            );
        }
        return $data;
    }

    public static function getServiceArr($arr) {
        $data = array();
        foreach ($arr as $row) {
            $data[] = $row['service']['name'];
        }

        return $data;
    }

    public static function getFreelancerTimings($freelancerId) {
        $data = array();
        $freelancerBlockedTimings = BlockedTime::getFreelancerTimings($freelancerId);
        foreach ($freelancerBlockedTimings as $key => $row) {
            $data[] = array(
                'id' => $key + 1,
                'freelancer_uuid' => $row['freelancer_uuid'],
                'blocked_start_date' => CommonHelper::setUserDateFormat($row['start_date']),
                'blocked_end_date' => CommonHelper::setUserDateFormat($row['end_date']),
                'blocked_start_time' => CommonHelper::setUserTimeFormat($row['from_time']),
                'blocked_end_time' => CommonHelper::setUserTimeFormat($row['to_time']),
                'blocked_notes' => $row['notes'],
            );
        }
        return $data;
    }

    public static function getFreelancerlocations($freelancerId) {
        $data = array();
        $freelancerLocation = FreelancerLocation::getFreelancerLocations('freelancer_id', $freelancerId);
        foreach ($freelancerLocation as $key => $row) {
            $address_locations = $row['location'];
            $data[] = array(
                'id' => $key + 1,
                'type' => $row['type'],
                'freelancer_location_uuid' => $row['freelancer_location_uuid'],
                'freelancer_id' => $freelancerId,
                'location_id' => $address_locations['location_id'],
                'address' => ucfirst($address_locations['address']),
                'route' => ucfirst($address_locations['route']),
                'street_number' => "Street No " . $address_locations['route'],
                'city' => ucfirst($address_locations['city']),
                'state' => ucfirst($address_locations['state']),
                'country' => ucfirst($address_locations['country']),
                'lat' => $address_locations['lat'],
                'lng' => $address_locations['lng'],
                'can_travel' => $row['freelancer']['can_travel']
            );
        }
        return $data;
    }

    public static function getAllFreelancers() {
        return Freelancer::getAllFreelancers();
    }

    public static function getAllFreelancersWithIbanInfo()
    {
        $data['with_iban'] = Freelancer::getFreelancerHavingIban();
        $data['no_iban'] = Freelancer::getFreelancerDontHaveIban();

        return $data;
    }

    public static function getActiveFreelancers() {
        $where = array(
            'is_active' => 1,
            // 'is_verified' => 1,
            'is_archive' => 0
        );
        return Freelancer::getAllFreelancers($where);
    }

    public static function getNotActiveFreelancers() {
        $where = array(
            'is_active' => 0,
        );
        return Freelancer::getAllFreelancers($where, 0);
    }

    public static function getNotVerfiedFreelancers() {
        $where = array(
            'is_verified' => 0,
            'is_archive' => 0
        );
        return Freelancer::getAllFreelancers($where);
    }

    public static function getBlockedFreelancers() {
        $where = array(
            'is_active' => 0,
            'is_verified' => 1,
            'is_archive' => 0
        );
        return Freelancer::getAllFreelancers($where);
    }

    public static function getDeletedFreelancers() {
        $where = array(
            'is_active' => 0,
            'is_archive' => 1
        );
        $freelancers = Freelancer::getAllFreelancers($where, 1);
        return $freelancers;
    }

    public static function updateFreelancerProfile($inputs, $type = 'freelancer', $update_type = 'freelancer') {
        if($update_type == 'freelancer'){
            $validation = Validator::make($inputs, CommonValidationHelper::updateProfileRules()['rules'], CommonValidationHelper::updateProfileRules()['message_en']);
            if ($validation->fails()) {
                return redirect()->back()->with('error_message', $validation->errors()->first());
            }
        }
        $profession_array = Profession::getProfessionDataById('id', $inputs['profession_id']);
        $inputs['profession_uuid'] = $profession_array['profession_uuid'];
        $service = new ApiService();
        $res = $service->guzzleRequest('POST', 'updateFreelancer', $inputs);
        if ($res['success'] == "true") {
            return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['update_' . $type . '_success']);
        } else {
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['update_error']);
        }
        // $validation = Validator::make($inputs, CommonValidationHelper::updateProfileRules()['rules'], CommonValidationHelper::updateProfileRules()['message_en']);
        // if ($validation->fails()) {
        //     return redirect()->back()->with('error_message', $validation->errors()->first());
        // }
        // $where['freelancer_uuid'] = $inputs['freelancer_uuid'];
        // unset($inputs['freelancer_uuid']);
        // $save_freelancer = Freelancer::updateFreelancerProfile($where, $inputs);
        // if (!$save_freelancer) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['update_error']);
        // }
        // DB::commit();
        // return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['update_success']);
    }

    public static function updateFreelancerBankDetails($inputs)
    {
        $validation = Validator::make($inputs, CommonValidationHelper::freelancerBankRules()['rules'], CommonValidationHelper::freelancerBankRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }

        $resp = BankDetail::where('freelancer_uuid', $inputs['freelancer_uuid'])->update(['iban_account_number' => $inputs['iban_account_number']]);
        if ($resp) {
            return redirect()->back()->with('success_message', "Bank details updated successfully");
        } else {
            return redirect()->back()->with('error_message', "Bank detail could not be updated");
        }
    }

    public static function updateFreelancerStatus($inputs)
    {
        if ($inputs['is_active'] == 1) {
            $active = 1;
            $archive = 0;
        } else {
            $active = 0;
            $archive = 0;
        }
        $update = array(
            'is_active' => $active,
            'is_archive' => $archive,
            'is_verified' => 1
        );
        if (!Freelancer::updateFreelancerdataById($update, $inputs['freelancer_uuid'])) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['update_error']);
        }
        // $data['freelancer_uuid'] = $inputs['freelancer_uuid'];
        // $data['reason'] = $inputs['reason'];
        // ProfileLog::createReaosn($data);
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
    }

    public static function deleteFreelancer($inputs)
    {
        if (!Freelancer::updateFreelancerdataById(['is_active' => $inputs['is_active'], 'is_archive' => $inputs['is_archive']], $inputs['freelancer_uuid'])) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['update_error']);
        }
        // $data['freelancer_uuid'] = $inputs['freelancer_uuid'];
        // $data['reason'] = $inputs['delete_notes'];
        // ProfileLog::createReaosn($data);
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
    }

    public static function getAppointmentDetail($inputs = []) {
        $validation = Validator::make($inputs, FreelancerValidationHelper::freelancerAppointmentRules()['rules'], FreelancerValidationHelper::freelancerAppointmentRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::errorMessage($validation->errors()->first());
        }
        $appointment_data = Appointment::getAppointmentDetail(array('appointment_uuid' => $inputs['appointment_uuid']));
        if (empty($appointment_data)) {
            return CommonHelper::errorMessage(MessageHelper::returnEnglishErrorMessage()['empty_error']);
        }
        $appointment = FreelancerDataHelper::makeAppointmentDetailArray($appointment_data);
        return view('appointment/appointment_edit', compact('appointment'));
    }

    //    public static function freelancerAppointmentUpdate($inputs = [])
    //    {
    ////        $validation = Validator::make($inputs, FreelancerValidationHelper::freelancerAppointmentUpdateRules()['rules'], FreelancerValidationHelper::freelancerAppointmentUpdateRules()['message_en']);
    ////        if ($validation->fails()) {
    ////            return CommonHelper::errorMessage($validation->errors()->first());
    ////        }
    ////        $where = array(
    ////            'date' =>  CommonHelper::setDbDateFormat($inputs['date']),
    ////            'from_time' =>  CommonHelper::setDbTimeFormat($inputs['from_time']),
    ////            'to_time' =>  CommonHelper::setDbTimeFormat($inputs['to_time']),
    ////            'freelancer_uuid' =>  $inputs['freelancer_uuid'],
    ////        );
    ////        if(count(Appointment::getAppointmentDetail($where)) > 0){
    ////            return CommonHelper::errorMessage($validation->errors()->first());
    ////        }
    ////        echo '<pre>';
    ////        print_r($appointment_data);
    ////        die;
    ////        if(empty($appointment_data)){
    ////            return CommonHelper::errorMessage(MessageHelper::returnEnglishErrorMessage()['empty_error']);
    ////        }
    ////        $appointment = FreelancerDataHelper::makeAppointmentDetailArray($appointment_data);
    ////        return view('appointment/appointment_edit', compact('appointment'));
    //    }

    public static function createFreelancerByAdmin($inputs) {
        $validation = Validator::make($inputs, FreelancerValidationHelper::createFreelancerRules()['rules'], FreelancerValidationHelper::createFreelancerRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }

        $inputs['password'] = CommonHelper::convertToHash($inputs['password']);
        $inputs['user_id'] = 1;
        $inputs['age'] = 0;
        $create_freelancer = Freelancer::createFreelancer($inputs);
        if (!$create_freelancer) {
            DB::rollBack();
            return redirect()->back()->with('error_message', FreelancerMessageHelper::getMessageData('error')['create_error']);
        }
        DB::commit();
        return redirect()->back()->with('success_message', FreelancerMessageHelper::getMessageData('success')['create_success']);
    }

    public static function getFreelancerCalendarData($freelancer_id) {
        $freelancer_appointments = Appointment::getFreelancerAppointments($freelancer_id);
        $appointments = FreelancerDataHelper::makeAppointmentsCalendarArray($freelancer_appointments);
        $freelancer_blockedtimings = BlockedTime::getFreelancerTimings($freelancer_id);
        $blocktimes = FreelancerDataHelper::makeBlocktimeCalendarArray($freelancer_blockedtimings);
        $freelancer_classes = Classes::getFreelancerClassSchedule('freelancer_uuid', $freelancer_id);
        $classes = FreelancerDataHelper::makeClassesCalendarArray($freelancer_classes);
        return array_merge($appointments, $blocktimes, $classes);
    }

    public static function updateFreelancerPicture($freelancer_id, $file, $inputs) {
        if (!empty($file)) {
            $file_size = $file->getSize();
            if ($file_size > 1000000) {
                return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['max_size_error']);
            }
            $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['mobile_uploads']);
            if (!$upload_image['success']) {
                return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['image_not_uploaded']);
            }
            $inputs['profile_image'] = $upload_image['file_name'];
            $inputs['freelancer_id'] = $freelancer_id;
            return self::updateFreelancerProfile($inputs, 'image', 'profile_image');
        }
        return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['file_not_found']);
    }

    public static function saveFreelancerSubscription($inputs) {
        $validation = Validator::make($inputs, FreelancerValidationHelper::saveFreelancerSubsRules()['rules'], FreelancerValidationHelper::saveFreelancerSubsRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $data = FreelancerDataHelper::makeSubsArray($inputs);
        if (!empty($inputs['subscription_uuid'])) {
            $create_subs = SubscriptionSetting::updateSubscription(['subscription_settings_uuid' => $inputs['subscription_uuid']], $data);
        } else {
            $create_subs = SubscriptionSetting::createSubscription($data);
        }
        if (!$create_subs) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['save_error']);
        }
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request'], $data);
    }

    public static function getFreelancerSubscriptions($freelancer_id) {
        $data = array();
        $freelancer_subs = SubscriptionSetting::getFreelancerSubs(['freelancer_id' => $freelancer_id]);
        foreach ($freelancer_subs as $key => $row) {
            $data[] = array(
                'id' => $key + 1,
                'subscription_setting_uuid' => $row['subscription_settings_uuid'],
                'freelancer_id' => $row['freelancer_id'],
                'type' => $row['type'],
                'price' => $row['price']
            );
        }
        return $data;
    }

    public static function getSubscriptionDetail($inputs) {
        $validation = Validator::make($inputs, FreelancerValidationHelper::getSubscriptionDetailRules()['rules'], FreelancerValidationHelper::getSubscriptionDetailRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $subscriptions = SubscriptionSetting::getSubscriptionDetail($inputs['subscription_uuid']);
        if (!$subscriptions) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['empty_error']);
        }
        $data = array(
            'subscription_uuid' => $subscriptions['subscription_settings_uuid'],
            'freelancer_uuid' => $subscriptions['freelancer_uuid'],
            'type' => $subscriptions['type'],
            'price' => $subscriptions['price'],
            'currency' => $subscriptions['currency']
        );
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request'], $data);
    }

    public static function getSearchedFreelancers($search)
    {
        $freelancers = Freelancer::where('first_name', 'like', '%' . $search . '%')->limit(5)->get();
        return $freelancers;
    }

    public static function getFreelancerPromoCodes($freelancerId) {
        $data = array();
        $freelancerPromoCodes = PromoCode::getFreelancerPromoCodes('freelancer_id', $freelancerId);
        if (!empty($freelancerPromoCodes)) {
            foreach ($freelancerPromoCodes as $key => $row) {
                $data[] = array(
                    'id' => $key + 1,
                    'code_uuid' => $row['code_uuid'],
                    'coupon_code' => !empty($row['coupon_code']) ? $row['coupon_code'] : '',
                    'valid_from' => $row['valid_from'],
                    'valid_to' => $row['valid_to'],
                    'discount_type' => $row['discount_type'] ?? '',
                    'coupon_amount' => $row['coupon_amount'] ?? '',
                );
            }
        }
        return $data;
    }

    public static function updateLocationByAdmin($inputs) {
        $data_arr = CommonDataHelper::makeLocationUpdateArray($inputs);
        $service = new ApiService();
        $res = $service->guzzleRequest('POST', 'updateFreelancerLocations', $data_arr);
        if ($res['success'] == "true") {
            return redirect()->back()->with('success_message', MessageHelper::getMessageData('success')['location_update_success']);
        } else {
            return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['invalid_data_error']);
        }
    }

    public static function getFreelancerClients($freelancer_id)
    {
        $clients_id_array = Client::getClientsColumn($freelancer_id, 'customer_id');
        $clients = Client::getClients('freelancer_id', $freelancer_id);
        $customers = Customer::searchClientCustomers($clients_id_array);
        $customer_response = CustomerResponseHelper::customerListResponse($customers);
        // $walkin_customers = WalkinCustomer::searchClientWalkinCustomers($freelancer_id);
        // $walkin_customers_response = WalkinCustomerResponseHelper::searchWalkinCustomersResponse($walkin_customers);
        $walkin_customers_response = [];
        $merge_customers = array_merge($customer_response, $walkin_customers_response);
        $response = ClientResponseHelper::prepareMixedClientsResponse($merge_customers, $clients);
        return $response;
    }

    public static function getFreelancerPaymentRequests($freelancer_id)
    {
        $reqs = PaymentRequest::where('user_id', $freelancer_id)->with('freelancer.bank_detail')->orderBy('id', 'DESC')->take(20)->get();
        $reqs = $reqs->toArray();
        $data = [];
        foreach ($reqs as $ind => $req) {
            $data[$ind]['no'] =  $ind + 1;
            $data[$ind]['freelancer'] =  $req['freelancer'];
            $data[$ind]['requested_amount'] =  round($req['requested_amount'], 2);
            $data[$ind]['deductions'] =  round($req['deductions'], 2);
            $data[$ind]['final_amount'] =  round($req['final_amount'], 2);
            $data[$ind]['currency'] =  $req['currency'];
            $data[$ind]['date'] = CommonHelper::convertDateTimeToTimezone($req['datetime'], 'UTC', 'Europe/Paris');
            $data[$ind]['status'] =  $req['is_processed'];
            $data[$ind]['action_detail'] =  route('getPaymentRequestDetail', $req['payment_request_uuid']);
        }

        return $data;
    }

    public static function getAllTransactions($id)
    {
        $data = FreelancerTransaction::getAllTransactions('freelancer_id', $id, true);
        $resp = TransactionHelper::setTransactionResposne($data, 'UTC', 'freelancer');

        return $resp;
    }

    // public static function getSpecificTransactions($id, $type)
    // {
    //     $data = FreelancerTransaction::getParticularTransactions('freelancer_id', $id, ['type' => $type, 'login_user_type' => 'freelancer']);
    //     $resp = TransactionHelper::setTransactionResposne($data, 'UTC', 'freelancer');

    //     return $resp;
    // }

    public static function getTransactionDetail($tr_id, $due_id)
    {
        $transaction_data = FreelancerTransaction::getTransactionDetail('freelancer_transaction_uuid', $tr_id);
        $inputs = ['local_timezone' => 'UTC', 'payment_due_uuid' => $due_id, 'login_user_type' => 'freelancer'];
        $response = TransactionHelper::setTransactionDetailResponse($transaction_data, $inputs);

        return $response;
    }

    public static function getAllTransactionsAPI($api_params)
    {
        $response = ApiService::guzzleRequest('get', 'getAllTransactions', $api_params);

        if (!$response['success']) {
            return $response['data'] = [];
        }

        return $response['data'];
    }

    public static function getTransactionByType($api_params, $type)
    {
        $api_params['type'] = $type;
        $response = ApiService::guzzleRequest('get', 'getTransactionByType', $api_params);

        if (!$response['success']) {
            return $response['data'] = [];
        }

        return $response['data'];
    }

    public static function getAvailableTransactions($freelancer_id)
    {
        $result = FreelancerEarning::with('freelancer', 'purchases.appointment', 'purchases.AppointmentPackage.appointments', 'purchases.classbooking.schedule', 'purchases.classbooking.classObject', 'purchases.wallet', 'purchases.purchasesTransition', 'purchases.AppointmentPackage.classBooking.classObject')->where(['freelancer_withdrawal_id' => null, 'freelancer_id' => $freelancer_id])->orderByDesc('id')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public static function getFreelancerWithdrawHiostory($freelancer_id)
    {
        $withdrawal = FreelancerWithdrawal::with('withdrawalEarnings', 'withdrawalFreelancer')->where('freelancer_id', $freelancer_id)->get();
        return !empty($withdrawal) ? $withdrawal->toArray() : [];
    }

    public static function getFreelancerBalances($freelancer, $type) {
        $available_balance = 0;
        $pending_balance = 0;
        $transfer_balance = 0;
        $progress_amount = 0;

        if ($type == 'available') {
            $availableFreelancerEarning = FreelancerEarning::getEarningWRTTime('freelancer_id', $freelancer->id, 'available');
            $available_balance = self::convertFreelancerEarningAccordingToCurrency($freelancer, $availableFreelancerEarning);
            return $available_balance;
        }

        if ($type == 'pending') {
            $pendingFreelancerEarning = FreelancerEarning::getEarningWRTTime('freelancer_id', $freelancer->id, 'pending');
            $pending_balance = self::convertFreelancerEarningAccordingToCurrency($freelancer, $pendingFreelancerEarning);
            return $pending_balance;
        }

        if ($type == 'transfer') {
            $transfer_balance = FreelancerWithdrawal::where(['freelancer_id' => $freelancer->id, 'schedule_status' => 'complete'])->sum('amount');
            return $transfer_balance;
        }

        if ($type == 'progress') {
            $progress_amount = FreelancerWithdrawal::where(['freelancer_id' => $freelancer->id, 'schedule_status' => 'in_progress'])->sum('amount');
            return $progress_amount;
        }
    }

    public static function prepareConvertedBalances($freelancer, $purchases) {
        $convertedAmountCount = 0;
        $count = 0;
        $convertedAmount = [];
        $amount = [];
        $balance['converted_amount'] = 0;
        $balance['amount'] = 0;
        foreach ($purchases as $key => $purchase) {

            if ($purchase['purchased_in_currency'] != $freelancer['default_currency']) {

                $convertedAmount[$convertedAmountCount] = CommonHelper::getConvertedCurrency($purchase['total_amount'], $purchase['purchased_in_currency'], $freelancer['default_currency']);
                $convertedAmountCount++;
            } else {
                $amount[$count] = $purchase['total_amount'];
                $count++;
            }
        }

        $sumOfConvertedAmount = array_sum($convertedAmount);
        $sumOfAmount = array_sum($amount);
        $balance['converted_amount'] = $sumOfConvertedAmount;
        $balance['amount'] = $sumOfAmount;
        return $balance;
    }

    public static function convertFreelancerEarningAccordingToCurrency($freelancer, $earnings) {
        $convertedAmountCount = 0;
        $count = 0;
        $convertedAmount = [];
        $amount = [];
        $balance['converted_amount'] = 0;
        $balance['amount'] = 0;
        foreach ($earnings as $key => $earning) {

            if ($earning['currency'] != $freelancer->default_currency) {

                $convertedAmount[$convertedAmountCount] = CommonHelper::getConvertedCurrency($earning['earned_amount'], $earning['currency'], $freelancer->default_currency);
                $convertedAmountCount++;
            } else {
                $amount[$count] = $earning['earned_amount'];
                $count++;
            }
        }

        $sumOfConvertedAmount = array_sum($convertedAmount);
        $sumOfAmount = array_sum($amount);
        $balance = $sumOfConvertedAmount + $sumOfAmount;
        return ($balance != 0) ? round($balance, 2) : 0;
    }




}
