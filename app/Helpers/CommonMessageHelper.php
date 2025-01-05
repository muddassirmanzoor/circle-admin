<?php

namespace App\Helpers;

Class CommonMessageHelper {
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
            'update_success' => 'Successfully updated!',
            'change_password_success' => 'Password successfully updated',
            'promo_code_success' => 'Promo Code has been added successfully',
            'send_code_success' => 'Promo Code has been sent to clients successfully',
            'update_code_success' => 'Promo Code has been updated successfully',
            'update_post_success' => 'Post has been updated successfully',
            'update_customer_success' => 'Customer has been updated successfully',
            'update_image_success' => 'Profile image updated successfully',
            'update_freelancer_success' => 'Freelancer has been updated successfully',
        ];
    }

    public static function returnEnglishErrorMessage() {
        return [
            'general_error' => 'Sorry, something went wrong. We are working on getting this fixed as soon as we can',
            'signup_error' => "Sorry, we could't register your data",
            'update_error' => 'Profile could not be updated',
            'change_password_error' => 'Change password failed',
            'save_category_error' => 'Freelancer category could not be saved',
            'invalid_data_error' => 'Invalid data provided',
            'save_schedule_error' => 'Schedule could not be saved',
        ];
    }

}

?>
