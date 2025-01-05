<?php

namespace App\Helpers;


use App\BlockedTime;
use App\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ScheduleHelper
{
    // public static function getFreelancerSchedules($freelancerId)
    // {
    //     $data = array();
    //     $freelancerSchedule = Schedule::getFreelancerWeeklyTimings($freelancerId);
    //     if(!empty($freelancerSchedule))
    //     {
    //         foreach ($freelancerSchedule as $key => $row)
    //         {
    //             switch ($row['day'])
    //             {
    //                 case 'Monday':
    //                     $dow = [1];
    //                     break;

    //                 case 'Tuesday':
    //                     $dow = [2];
    //                     break;

    //                 case 'Wednesday':
    //                     $dow = [3];
    //                     break;

    //                 case 'Thursday':
    //                     $dow = [4];
    //                     break;

    //                 case 'Friday':
    //                     $dow = [5];
    //                     break;

    //                 case 'Saturday':
    //                     $dow = [6];
    //                     break;

    //                 case 'Sunday':
    //                     $dow = [7];
    //                     break;

    //             }
    //             $data[] = array(
    //                 'id' => $row['freelancer_uuid'],
    //                 'title' => 'Schedule',
    //                 'schedule_uuid' => $row['schedule_uuid'],
    //                 'schedule_start_time' => AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'],$row['local_timezone']),
    //                 'schedule_end_time' => AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'],$row['local_timezone']),
    //                 'freelancer_uuid' => $row['freelancer_uuid'],
    //                 'local_timezone' => $row['local_timezone'],
    //                 'dow' => $dow
    //             );
    //         }
    //     }
    //     return $data;
    // }

    public static function updateScheduleByAdmin($inputs = [])
    {
        $validation = Validator::make($inputs, CommonValidationHelper::scheduleUpdateRules()['rules'], CommonValidationHelper::scheduleUpdateRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $data_arr = CommonDataHelper::makeScheduleUpdateArray($inputs);

        $service = new ApiService();
        $res = $service->guzzleRequest('POST','updateFreelancerWeeklySchedule', $data_arr);
        if($res['success']=="true") {
            return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['schedule_update_success']);
        }
        else {
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['invalid_data_error']);
        }
    }

    public static function getFreelancerSchedules($freelancerId)
    {
        $data = array();
        $freelancerSchedule = Schedule::getFreelancerWeeklyTimings($freelancerId);
        if(!empty($freelancerSchedule))
        {
            foreach ($freelancerSchedule as $key => $row)
            {
                $data[] = array(
                    'id' => $row['id'],
                    'day'  => $row['day'],
                    'schedule_uuid' => $row['schedule_uuid'],
                    'schedule_start_time' => AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'],$row['local_timezone']),
                    'schedule_end_time' => AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'],$row['local_timezone']),
                    'freelancer_id' => $row['freelancer_id'],
                    'local_timezone' => $row['local_timezone']
                );
            }
        }
        return $data;
    }
}
