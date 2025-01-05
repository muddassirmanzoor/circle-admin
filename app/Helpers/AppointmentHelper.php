<?php

namespace App\Helpers;

use App\Appointment;
use App\AppointmentService;
use App\FreelancerTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class AppointmentHelper {

    public static $changeStatusRules = [
        'status' => 'required',
        'appointment_uuid' => 'required',
    ];

    public static function getAllAppointments($status) {
        $data = array();
        $appointments = Appointment::getAllAppointments('status', $status);
        foreach ($appointments as $key => $row) {
            $appointment_freelancer = $row['appointment_freelancer'];

            if($appointment_freelancer == null)
            {
                $appointment_freelancer = [];
                $appointment_freelancer['first_name'] = "";
                $appointment_freelancer['last_name'] = "";
            }

            $appointment_customer = $row['appointment_customer'];
            $from_time = AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'], $row['local_timezone']);
            $to_time = AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'], $row['local_timezone']);
            $customer_first_name = !empty($appointment_customer['first_name']) ? $appointment_customer['first_name'] : "Anonymous";
            $customer_last_name = !empty($appointment_customer['last_name']) ? $appointment_customer['last_name'] : "Anonymous";
            $data[] = array(
                'id' => $key + 1,
                'appointment_uuid' => $row['appointment_uuid'],
                'appointment_title' => ucfirst($row['title']),
                'appointment_freelancer' => $appointment_freelancer['first_name'] . ' ' . $appointment_freelancer['last_name'],
                'appointment_customer' => $customer_first_name . ' ' . $customer_last_name,
                'service_arr' => self::getServiceArr($row['appointment_services']),
                'appointment_date' => Carbon::parse($row['created_at'])->format('d-m-Y H:i:'),
                'appointment_price' => $row['price'],
                'appointment_start_time' => date('d-m-Y H:i',$row['appointment_start_date_time']),
                'appointment_end_time' => date('d-m-Y H:i',$row['appointment_end_date_time']),
                'appointment_status' => ucfirst($row['status'])
            );
        }
        return $data;
    }

    public static function getServiceArr($arr) {
        $data = array();
        foreach ($arr as $row) {
            $data[] = $row['name'];
        }

        return $data;
    }

    public static function getFreelancerAppointments($freelancerId) {
        $data = array();
        $freelancerAppointments = Appointment::getFreelancerAppointments($freelancerId);
        if (!empty($freelancerAppointments)) {
            foreach ($freelancerAppointments as $key => $row) {
                $appointment_customer = $row['appointment_customer'];
                $data[] = array(
                    'id' => $key + 1,
                    'appointment_uuid' => $row['appointment_uuid'],
                    'freelancer_id' => $row['freelancer_id'],
                    'appointment_title' => ucfirst($row['title']),
                    'appointment_customer' => $appointment_customer['first_name'],
                    'service_arr' => self::getServiceArr($row['appointment_services']),
                    'appointment_date' => CommonHelper::setUserDateFormat($row['appointment_date']),
                    'appointment_price' => $row['price'],
                    'appointment_start_time' => $row['appointment_date'] . 'T' . $row['from_time'],
                    'appointment_end_time' => $row['appointment_date'] . 'T' . $row['to_time'],
                    'appointment_status' => $row['status']
                );
            }
        }
        return $data;
    }

    public static function getFreelancerEarnings($freelancerId) {
        $earnings = FreelancerTransaction::calculateEarnings('freelancer_id', $freelancerId);
        $result = $earnings - ($earnings * (CommonHelper::$circle_commission['commision_rate_percentage'] / 100));
        return $result;
        // return $freelancerAppointments = Appointment::getFreelancerEarnings($freelancerId);
    }

    public static function getFreelancerMonthlyEarnings($freelancerId) {
        return $freelancerAppointments = Appointment::getFreelancerMonthlyEarnings($freelancerId);
    }

    public static function getFreelancerYearlyEarnings($freelancerId) {
        return $freelancerAppointments = Appointment::getFreelancerYearlyEarnings($freelancerId);
    }

    public static function updateAppointByAdmin($inputs = []) {
        $validation = Validator::make($inputs, CommonValidationHelper::appointmentUpdateRules()['rules'], CommonValidationHelper::appointmentUpdateRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $appointment_data = CommonDataHelper::makeAppointmentUpdateArray($inputs);
        $requestResponse = ApiService::guzzleRequest('post', 'freelancerRescheduleAppointment', $appointment_data);
        if (!$requestResponse['success']) {
            return redirect()->back()->with('error_message', $requestResponse['message']);
        }
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['appointment_update_success']);
        // $update = Appointment::updateAppointment(array('appointment_uuid' => $appointment_uuid), $data_arr);
        // if (!$update) {
        //     DB::rollBack();
        //     return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['appointment_update_error']);
        // }
        // DB::commit();
        // return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['appointment_update_success']);
    }

    public static function getCustomerAppointments($customer_uuid) {
        $data = array();
        $customerAppointments = Appointment::getCustomerAppointments('customer_uuid', $customer_uuid);
        foreach ($customerAppointments as $key => $row) {
            $appointment_customer = $row['appointment_customer'];
            $data[] = array(
                'id' => $key + 1,
                'appointment_uuid' => $row['appointment_uuid'],
                'customer_uuid' => $row['customer_uuid'],
                'appointment_title' => ucfirst($row['title']),
                'appointment_customer' => $appointment_customer['first_name'],
                'service_arr' => self::getServiceArr($row['appointment_services']),
                'appointment_date' => CommonHelper::setUserDateFormat($row['appointment_date']),
                'appointment_price' => $row['price'],
                'appointment_start_time' => $row['appointment_date'] . 'T' . $row['from_time'],
                'appointment_end_time' => $row['appointment_date'] . 'T' . $row['to_time'],
                'appointment_status' => ucfirst($row['status'])
            );
        }
        return $data;
    }

    public static function createAppointment($inputs = []) {
        $validation = Validator::make($inputs, AppointmentValidationHelper::createAppointmentRules()['rules'], AppointmentValidationHelper::createAppointmentRules()['message_en']);
        if ($validation->fails()) {
            DB::rollBack();
            return CommonHelper::errorMessage($validation->errors()->first());
        }
        $inputs['logged_in_uuid'] = Auth::user()->user_uuid;
        $inputs['login_user_type'] = 'freelancer';
        $inputs['start_time'] = AdminCommonHelper::convertTimeToTimezone($inputs['start_time'], $inputs['local_timezone'], 'UTC');
        $inputs['end_time'] = AdminCommonHelper::convertTimeToTimezone($inputs['end_time'], $inputs['local_timezone'], 'UTC');
        $requestResponse = ApiService::guzzleRequest('post', 'freelancerAddAppointment', $inputs);
        if (!$requestResponse['success']) {
            return redirect()->back()->with('error_message', $requestResponse['message']);
        }
        return \Illuminate\Support\Facades\Redirect::route('getPendingAppointments')->with('success_message', $requestResponse['message']);
    }

    public static function makeAppointmentDataArr($inputs = []) {
        $response = [
            'freelancer_id' => $inputs['freelancer_id'],
            'customer_uuid' => $inputs['customer_uuid'],
            'title' => 'New Appointment',
            'appointment_date' => CommonHelper::setDbDateFormat($inputs['date']),
            'from_time' => $inputs['start_time'],
            'to_time' => $inputs['end_time'],
            'type' => 'appointment',
            'status' => 'pending',
            'notes' => $inputs['notes'],
        ];
        return $response;
    }

    public static function makeAppointmentServiceDataArr($inputs = [], $appointment_uuid = '') {
        $response = [
            'appointment_uuid' => $appointment_uuid,
            'service_uuid' => $inputs['sub_category_uuid']
        ];
        return $response;
    }

    public static function getFreelancerStats($freelancer_id) {
        $data = [];
        $appointment_where = array(
            'freelancer_id' => $freelancer_id,
            'type' => 'appointment'
        );
        $data['appointments'] = Appointment::getAppointmentsCount($appointment_where);

        $class_where = array(
            'freelancer_id' => $freelancer_id,
            'type' => 'class'
        );
        $data['classess'] = Appointment::getAppointmentsCount($class_where);

        $session_where = array(
            'freelancer_id' => $freelancer_id,
            'type' => 'session'
        );
        $data['sessions'] = Appointment::getAppointmentsCount($session_where);

        $customer_where = array(
            'freelancer_id' => $freelancer_id
        );
        $data['customers'] = Appointment::getFreelancerCustomers($customer_where);
        return $data;
    }

    public static function editAppointmentFrom($appointment_uuid) {
        $response = [
            'appointment_uuid' => $appointment_uuid,
            'service_uuid' => $inputs['sub_category_uuid']
        ];
        return $response;
    }

    public static function getAppointmentDetail($inputs = []) {
        $validation = Validator::make($inputs, AppointmentValidationHelper::getAppointmentDetailRules()['rules'], AppointmentValidationHelper::getAppointmentDetailRules()['message_en']);
        if ($validation->fails()) {
            DB::rollBack();
            return CommonHelper::errorMessage($validation->errors()->first());
        }
        $where = ['appointment_uuid' => $inputs['appointment_uuid']];
        $appointment_detail = Appointment::getAppointmentDetail($where);
        if (!$appointment_detail) {
            DB::rollBack();
            return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('error')['empty_error']);
        }
        $response = AppointmentHelper::makeAppointmentDetailArr($appointment_detail[0]);
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request'], $response);
    }

    public static function getAppointment($appointment_uuid)
    {
        $service = new ApiService();
        $url = 'freelancerGetAppointmentDetail?appointment_uuid='.$appointment_uuid."&logged_in_uuid=".Auth::guard('customer')->user()->customer_uuid."&local_timezone=Asia/Karachi&currency=Pound&lang=EN";
        $res = $service->guzzleRequest('GET',$url, []);
        return $res["success"] ? $res["data"]:false;
    }

    public static function makeAppointmentDetailArr($data = []) {
        $appointment_services = $data['appointment_services'][0];
        $response = [
            'customer_uuid' => $data['customer_uuid'],
            'service_uuid' => $appointment_services['service']['sub_category_uuid'],
            'appointment_date' => CommonHelper::setUserDateFormat($data['appointment_date']),
            'start_time' => $data['from_time'],
            'end_time' => $data['to_time'],
            'status' => $data['status']
        ];
        return $response;
    }

    public static function updateAppointmentDetail($inputs = []) {
        $validation = Validator::make($inputs, AppointmentValidationHelper::updateAppointmentDetailRules()['rules'], AppointmentValidationHelper::updateAppointmentDetailRules()['message_en']);
        if ($validation->fails()) {
            DB::rollBack();
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        $inputs['from_time'] = AdminCommonHelper::convertTimeToTimezone($inputs['from_time'], $inputs['local_timezone'], 'UTC');
        $inputs['to_time'] = AdminCommonHelper::convertTimeToTimezone($inputs['to_time'], $inputs['local_timezone'], 'UTC');
        $data = CommonDataHelper::makeAppointmentUpdateArray($inputs);
        $requestResponse = ApiService::guzzleRequest('post', 'freelancerRescheduleAppointment', $data);
        if (!$requestResponse['success']) {
            return redirect()->back()->with('error_message', $requestResponse['message']);
        }
        return redirect()->back()->with('success_message', AppointmentMessageHelper::getMessageData('success')['appointment_update_success']);
    }

    public static function getCustomerAppointmentsData($customer_id) {
        $data = array();
        $customerAppointments = Appointment::getCustomerAppointmentsData('customer_id', $customer_id);
        foreach ($customerAppointments as $key => $row) {
            $appointment_customer = $row['appointment_customer'];
            $from_time = AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'], $row['local_timezone']);
            $to_time = AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'], $row['local_timezone']);

            $data[] = array(
                'id' => $key + 1,
                'appointment_uuid' => $row['appointment_uuid'],
                'freelancer_uuid' => $row['appointment_freelancer']['freelancer_uuid'],
                'customer_uuid' => $row['appointment_customer']['customer_uuid'],
                'freelancer_id' => $row['freelancer_id'],
                'customer_id' => $row['customer_id'],
                'appointment_title' => ucfirst($row['title']),
                'appointment_customer' => $appointment_customer['first_name'],
                'appointment_date' => CommonHelper::setUserDateFormat($row['appointment_date']),
                'appointment_price' => $row['price'],
                'appointment_start_time' => $row['appointment_date'] . 'T' . $from_time,
                'appointment_end_time' => $row['appointment_date'] . 'T' . $to_time,
                'appointment_status' => ucfirst($row['status'])
            );
        }
        return $data;
    }

    public static function changeAppointmentStatus($inputs = []) {
        $validation = Validator::make($inputs, AppointmentValidationHelper::changeAppointmentStatusRules()['rules'], AppointmentValidationHelper::changeAppointmentStatusRules()['message_en']);
        if ($validation->fails()) {
            DB::rollBack();
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        $data = [
            'appointment_uuid' => $inputs['appointment_uuid'],
            'logged_in_uuid' => $inputs['logged_in_uuid'] ?? '',
            'login_user_type' => $inputs['login_user_type'] ?? 'admin',
            'status' => $inputs['status'],
            'lang' => 'EN',
            'local_timezone' => 'UTC',
            'currency' => 'POUND',
        ];
        $requestResponse = ApiService::guzzleRequest('post', 'changeAppointmentStatus', $data);
        if (!$requestResponse['success']) {
            return redirect()->back()->with('error_message', $requestResponse['message']);
        }
        return response()->json(['response' => 'success']);
    }

    public static function updateAppointmentStatus($appointment_uuid = null, $status = null) {
        $inputs['appointment_uuid'] = !empty($appointment_uuid) ? $appointment_uuid : null;
        $inputs['status'] = !empty($status) ? $status : null;
        $validator = Validator::make($inputs, self::$changeStatusRules);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->with('error_message', $validator->errors()->first());
        }

//        $data = [
//            'appointment_uuid' => $inputs['appointment_uuid'],
//            'status' => $inputs['status'],
//            'lang' => 'EN'
//
//        ];
//        $requestResponse = ApiService::guzzleRequest('post', 'changeAppointmentStatus', $data);
//        if (!$requestResponse['success']) {
//            DB::rollback();
//            return redirect()->back()->with('error_message', $requestResponse['message']);
//        }
//        DB::commit();
//        return redirect()->back()->with('success_message', 'Status changed successfully');
        $data = [
            'status' => $inputs['status'],
        ];
        $update = Appointment::updateAppointmentStatus('appointment_uuid', $inputs['appointment_uuid'], $data);
        if (!$update) {
            DB::rollback();
            return redirect()->back()->with('error_message', 'Status can not be changed');
        }
        DB::commit();
        return redirect()->back()->with('success_message', 'Status changed successfully');
    }

}
