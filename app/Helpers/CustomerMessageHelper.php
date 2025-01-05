<?php

namespace App\Helpers;

Class CustomerMessageHelper {
    /*
      |--------------------------------------------------------------------------
      | FreelancerMessageHelper that contains all the Freelancer message methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use Freelancer processes
      |
     */

    public static function getMessageData($type = '') {
        if ($type == 'error') {
            return self::returnEnglishErrorMessage();
        } elseif ($type == 'success') {
            return self::returnEnglishSuccessMessage();
        }
    }

    public static function returnEnglishSuccessMessage() {
        return [
            'successful_request' => 'Request successful!',
            'create_success' => 'Customer successfuly created!',
            'update_success' => 'Profile updated successfully!',
            'change_password_success' => 'Password successfully updated'
        ];
    }

    public static function returnEnglishErrorMessage() {
        return [
            'general_error' => 'Sorry, something went wrong. We are working on getting this fixed as soon as we can',
            'appointment found' => 'Profile could not be updated',
            'update_error' => 'Profile could not be updated',
            'create_error' => 'Customer creation failed.!',
            'chang_password_error' => 'Change password failed',
            'invalid_data_error' => 'Invalid data provided',
            'save_schedule_error' => 'Schedule could not be saved',
        ];
    }
}

?>
