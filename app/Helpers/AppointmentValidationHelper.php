<?php

namespace App\Helpers;

Class AppointmentValidationHelper
{
    public static function createAppointmentRules() {
        $validate['rules'] = [
            'freelancer_uuid' => 'required',
            'customer_uuid' => 'required',
            'service_uuid' => 'required',
            'title' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'local_timezone' => 'required',
            //'notes' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function updateAppointmentDetailRules() {
        $validate['rules'] = [
            'appointment_uuid' => 'required',
            //'sub_category_uuid' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function changeAppointmentStatusRules() {
        $validate['rules'] = [
            //'freelancer_uuid' => 'required',
            'appointment_uuid' => 'required',
            'status' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function getAppointmentDetailRules() {
        $validate['rules'] = [
            'appointment_uuid' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function englishMessages() {
        return [
            'appointment_uuid.required' => 'Appointment Id is missing',
            'freelancer_uuid.required' => 'Freelancer Id is missing',
            'sub_category_uuid.required' => 'Service is missing',
            'start.required' => 'Start date is missing',
            'end.required' => 'End date is missing',
            'date.required' => 'Appointment date Id is missing',
            'start_time.required' => 'Appointment From time Id is missing',
            'end_time.required' => 'Appointment To time Id is missing',
            'status.required' => 'status is missing'
        ];
    }

}

?>
