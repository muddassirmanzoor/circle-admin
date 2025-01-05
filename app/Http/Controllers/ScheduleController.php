<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\MessageHelper;
use App\Helpers\ScheduleHelper;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getFreelancerSchedules(Request $request)
    {
        try{
            $freelancer_uuid = $request->input('freelancer_uuid');
            $freelancer_schedules = ScheduleHelper::getFreelancerSchedules($freelancer_uuid);
            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $freelancer_schedules);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateSchedulesByAdmin(Request $request)
    {
        try{
            $inputs = $request->input();
            return ScheduleHelper::updateScheduleByAdmin($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

//    public function getCustomerAppointments(Request $request)
//    {
//        try{
//            $customer_uuid = $request->input('customer_uuid');
//            $customer_appointments = AppointmentHelper::getCustomerAppointments($customer_uuid);
//            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $customer_appointments);
//        } catch (\Exception $ex) {
//            CommonHelper::storeException($ex);
//            return CommonHelper::CommonExceptions($ex);
//        }
//    }
}
