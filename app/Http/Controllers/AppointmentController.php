<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Helpers\AppointmentHelper;
use App\Helpers\CommonHelper;
use App\Helpers\CustomerHelper;
use App\Helpers\FreelancerHelper;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\AdminCommonHelper;

class AppointmentController extends Controller {

    public function getPendingAppointments(Request $request) {
        $status_url = explode('/', $request->url());
        $status = explode('-', $status_url[count($status_url) - 1]);
        $status = $status[0];
        $appointments = AppointmentHelper::getAllAppointments($status);
        return view('appointment.appointments_listing', compact('appointments', 'status'));
    }

    public function getFreelancerAppointments(Request $request) {
        try {
            $freelancer_uuid = $request->input('freelancer_uuid');
            $freelancer_appointments = AppointmentHelper::getFreelancerAppointments($freelancer_uuid);
            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $freelancer_appointments);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateAppointByAdmin(Request $request) {
        try {
            $inputs = $request->input();
            return AppointmentHelper::updateAppointByAdmin($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getCustomerAppointments(Request $request) {
        try {
            $customer_uuid = $request->input('customer_uuid');
            $customer_appointments = AppointmentHelper::getCustomerAppointmentsData($customer_uuid);
            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $customer_appointments);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function addNewappointmentForm($id) {
        try {
            $data['all_customers'] = CustomerHelper::getAllVerifiedCustomers();
            $data['freelancer_subcategories'] = FreelancerHelper::getFreelancerSubcategories($id);
            return view('appointment/add-new-appointment', $data);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function newAppointment($id = null) {
        try {
            $data = [];
            $data['freelancer_uuid'] = $id;
            $data['freelancer_detail'] = FreelancerHelper::getFreelancerDetail($id);
            $data['all_customers'] = CustomerHelper::getAllVerifiedCustomers();
            $data['freelancer_subcategories'] = FreelancerHelper::getFreelancerSubcategories($id);
            return view('appointment.newAppointment', $data);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function createNewAppointment(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return AppointmentHelper::createAppointment($inputs);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function editAppointmentFrom($uuid) {
        $appointment_detail = Appointment::getAppointmentDetail(['appointment_uuid' => $uuid]);
        $freelancer = CommonHelper::getModelInstance('App\Freelancer', 'id', $appointment_detail['freelancer_id']);
        $data['all_customers'] = CustomerHelper::getAllVerifiedCustomers();
        $data['freelancer_subcategories'] = FreelancerHelper::getFreelancerSubcategories($appointment_detail['freelancer_id']);
        $data['appointment_date'] = $appointment_detail['appointment_date'];
        $from_time = AdminCommonHelper::convertTimeToTimezone($appointment_detail['from_time'], $appointment_detail['saved_timezone'], $appointment_detail['local_timezone']);
        $to_time = AdminCommonHelper::convertTimeToTimezone($appointment_detail['to_time'], $appointment_detail['saved_timezone'], $appointment_detail['local_timezone']);
        $data['from_time'] = $from_time;
        $data['to_time'] = $to_time;
        $data['local_timezone'] = $appointment_detail['local_timezone'];
        $data['freelancer_uuid'] = $freelancer->freelancer_uuid;
        $data['currency'] = $freelancer->default_currency;
        $data['customer_id'] = $appointment_detail['customer_id'];
        $data['service_id'] = $appointment_detail['service_id'];

        return view('appointment.appointment_edit', $data);
    }

    public function getAppointment($uuid)
    {
        try{
            $data = AppointmentHelper::getAppointment($uuid);
            return view('appointment.show', compact('data'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getAppointmentDetail(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->input();
            return AppointmentHelper::getAppointmentDetail($inputs);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            echo '<pre>';
            print_r($ex->getMessage());
            print_r($ex->getFile());
            print_r($ex->getLine());
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateAppointmentDetail(Request $request) {
        try {
            //DB::beginTransaction();
            $inputs = $request->except('_token');
            return AppointmentHelper::updateAppointmentDetail($inputs);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            echo '<pre>';
            print_r($ex->getMessage());
            print_r($ex->getFile());
            print_r($ex->getLine());
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function changeAppointmentStatus($id, $status, Request $request) {
        try {
            $inputs = $request->input();
            $inputs = array_merge($inputs, [
                'logged_in_uuid' => $request->user()->user_uuid,
                'appointment_uuid' => $id,
                'status' => $status,
            ]);
            return AppointmentHelper::changeAppointmentStatus($inputs);
        } catch (\Exception $ex) {
            dd($ex);
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateAppointmentStatus($id, $status, Request $request) {
        try {
            $inputs = [
                'logged_in_uuid' => Auth()->guard('customer')->user()->customer_uuid,
                'appointment_uuid' => $id,
                'status' => $status,
            ];
            $response =  AppointmentHelper::changeAppointmentStatus($inputs);
            if ($response instanceof RedirectResponse ):
                return $response;
            elseif($response instanceof JsonResponse && ($response = json_decode($response->getContent(), true))
                && ($response['response'] ?? '') == 'success'):
                return redirect()->back()->with('success_message', 'Status changed successfully');
            endif;
            return redirect()->back()->with('error_message', 'Status can not be changed');
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
//        try {
//            DB::beginTransaction();
//            return AppointmentHelper::updateAppointmentStatus($id, $status);
//        } catch (\Exception $ex) {
//            DB::rollback();
//            CommonHelper::storeException($ex);
//            return CommonHelper::CommonExceptions($ex);
//        }
    }

}
