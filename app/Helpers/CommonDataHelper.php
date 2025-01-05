<?php

namespace App\Helpers;

Class CommonDataHelper {

    public static function makeAppointmentArray($data = []) {
        $response = [];
        if(!empty($data))
        {
            foreach ($data as $key => $row)
            {
                $response['freelancer_uuid'] = $row['freelancer_uuid'];
                $response['appointment_uuid'] = $row['appointment_uuid'];
                $response['title'] = $row['title'];
                $response['type'] = $row['type'];
                $response['status'] = $row['status'];
                $response['price'] = $row['price'];
                $response['appointment_date'] = CommonHelper::setUserDateFormat($row['appointment_date']);
                $response['from_time'] = CommonHelper::setUserTimeFormat($row['from_time']);
                $response['to_time'] = CommonHelper::setUserTimeFormat($row['to_time']);
            }
        }
        return $response;
    }

    public static function makeAppointmentUpdateArray($inputs = []) {
        $response = [];
        if(!empty($inputs))
        {
            $response['freelancer_uuid'] = $inputs['freelancer_uuid'];
            $response['appointment_uuid'] = $inputs['appointment_uuid'];
            $response['date'] = CommonHelper::setDbDateFormat($inputs['date']);
            $response['start_time'] = CommonHelper::setDbTimeFormat($inputs['from_time']);
            $response['end_time'] = CommonHelper::setDbTimeFormat($inputs['to_time']);
            $response['local_timezone'] = $inputs['local_timezone'];
            $response['currency'] = $inputs['currency'];
        }
        return $response;
    }

    public static function makeBlocktimeUpdateArray($inputs = []) {
        $response = [];
        if(!empty($inputs))
        {
            $response['start_date'] = CommonHelper::setDbDateFormat($inputs['start_date']);
            $response['end_date'] = CommonHelper::setDbDateFormat($inputs['end_date']);
            $response['from_time'] = CommonHelper::setDbTimeFormat($inputs['from_time']);
            $response['to_time'] = CommonHelper::setDbTimeFormat($inputs['to_time']);
        }
        return $response;
    }

    public static function makeScheduleUpdateArray($inputs = []) {
        $response = [];
        if(!empty($inputs))
        {
            $response['freelancer_uuid'] = $inputs['freelancer_uuid'];
            $response['local_timezone'] = 'Asia/Karachi';
            $response['days'][0]['day'] = \Carbon\Carbon::parse($inputs['start_date'])->format('l');
            $response['days'][0]['time_slots'][0]['from_time'] = CommonHelper::setDbTimeFormat($inputs['from_time']);
            $response['days'][0]['time_slots'][0]['to_time'] = CommonHelper::setDbTimeFormat($inputs['to_time']);
        }
        return $response;
    }

    public static function makeLocationUpdateArray($inputs = []) {
        $response = [];
        if(!empty($inputs))
        {
            $response['freelancer_id'] = $inputs['freelancer_id'];
            $response['can_travel'] = $inputs['can_travel'];
            $response['locations'][0]['location_id'] = $inputs['location_id'];
            $response['locations'][0]['freelancer_location_uuid'] = $inputs['freelancer_location_uuid'];
            $response['locations'][0]['lat'] = $inputs['lat'];
            $response['locations'][0]['lng'] = $inputs['lng'];
            $response['locations'][0]['address'] = $inputs['address'];
            $response['locations'][0]['city'] = $inputs['city'];
            $response['locations'][0]['state'] = $inputs['state'];
            $response['locations'][0]['country'] = $inputs['country'];
            $response['locations'][0]['location_type'] = $inputs['location_type'];
            $response['lang'] = 'EN';
        }
        return $response;
    }

}

?>
