<?php

namespace App\Helpers;


use App\BlockedTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class BlocktimeHelper
{
    public static function getFreelancerBlocktimings($freelancerId)
    {
        $data = array();
        $freelancerBlockedTimings = BlockedTime::getFreelancerTimings($freelancerId);
        if(!empty($freelancerBlockedTimings)){
            foreach ($freelancerBlockedTimings as $key => $row)
            {
                $data[] = array(
                    'id' => $key + 1,
                    'blocked_time_uuid' => $row['blocked_time_uuid'],
                    'freelancer_uuid' => $row['freelancer_uuid'],
                    'blocked_start_date' => CommonHelper::setUserDateFormat($row['start_date']),
                    'blocked_end_date' => CommonHelper::setUserDateFormat($row['end_date']),
                    'blocked_start_time' => $row['start_date'].'T'.$row['from_time'],
                    'blocked_end_time' => $row['end_date'].'T'.$row['to_time'],
                    'blocked_notes' => $row['notes'],
                );
            }
        }
        return $data;
    }

    public static function updateBlocktimeByAdmin($inputs = [])
    {
        $validation = Validator::make($inputs, CommonValidationHelper::blocktimeUpdateRules()['rules'], CommonValidationHelper::blocktimeUpdateRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $blocked_time_uuid = $inputs['blocked_time_uuid'];
        unset($inputs['blocked_time_uuid']);
        $data_arr = CommonDataHelper::makeBlocktimeUpdateArray($inputs);
        $update = BlockedTime::updateAppointment(array('blocked_time_uuid' => $blocked_time_uuid), $data_arr);
        if(!$update) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['blocktime_update_error']);
        }
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['blocktime_update_success']);
    }
}
