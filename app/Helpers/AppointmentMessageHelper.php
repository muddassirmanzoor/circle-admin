<?php

namespace App\Helpers;

Class AppointmentMessageHelper {

    public static function getMessageData($type = '') {
        if($type == 'error') {
            return self::returnEnglishErrorMessage();
        } elseif ($type == 'success') {
            return self::returnEnglishSuccessMessage();
        }
    }

    public static function returnEnglishSuccessMessage() {
        return [
            'successful_request' => 'Request successful!',
            'update_success' => 'Profile updated successfully!',
            'appointment_create_success' => 'Appointment created successfully!',
            'appointment_update_success' => 'Appointment updated successfully!'
        ];
    }


    public static function returnEnglishErrorMessage() {
        return [
            'empty_error' => 'Sorry, No record found.',
            'general_error' => 'Sorry, something went wrong. We are working on getting this fixed as soon as we can',
            'appointment_found' => 'An Appointment already exist on this date & time range',
            'update_error' => 'Profile could not be updated',
            'appointment_create_error' => 'Appointment creation failed!',
            'appointment_update_error' => 'Appointment could not be updated',
            'invalid_data_error' => 'Invalid data provided',
            'save_appointment_error' => 'Appointment could not be saved'
        ];
    }

}

?>
