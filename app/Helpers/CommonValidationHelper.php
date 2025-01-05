<?php

namespace App\Helpers;

Class CommonValidationHelper {
    /*
      |--------------------------------------------------------------------------
      | FreelancerValidationHelper that contains all the Freelancer Validation methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use Freelancer processes
      |
     */

    public static function updateProfileRules() {
        $validate['rules'] = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'country_code' => 'required',
            'country_name' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function createFreelancerRules() {
        $validate['rules'] = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function createCustomerRules() {
        $validate['rules'] = [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:freelancers',
            //'dob' => 'required',
            'phone_number' => 'required|numeric',
            'country_code' => 'required',
            'country_name' => 'required|alpha',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function freelancerBankRules() {
        $validate['rules'] = [
            'freelancer_uuid' => 'required|max:250',
            /*'account_name' => 'required|max:250',
            'bank_name' => 'required|max:250',
            'account_number' => 'required|max:250',*/
            'iban_account_number' => 'required|max:250',
            /*'swift_code' => 'required|max:250'*/
        ];
        $validate['message_en'] = self::freelancerBankMessage();
        return $validate;
    }

    public static function freelancerBankMessage(){
        return [
            'freelancer_uuid.required' => 'Freelancer info missing',
            'account_name.required' => 'Account name is missing',
            'bank_name.required' => 'Bank name is missing',
            'account_number.required' => 'Account number is missing',
            'iban_account_number.required' => 'Account Iban is missing',
            'swift_code.required' => 'Swift code is missing',
        ];
    }

    public static function englishMessages() {
        return [
            'first_name.required' => 'First name is missing',
            'last_name.required' => 'Last name is missing',
            'profession.required' => 'Profession is missing',
            'company.required' => 'Company name is missing',
            'dob.required' => 'Invalid Date of Birth',
            'email.required' => 'Email is missing',
            'phone_number.required' => 'Phone number is missing',
            'password.required' => 'Password is missing',
            'freelancer_uuid.required' => 'Freelancer uuid is required',
            'blocked_time_uuid.required' => 'Blocktime uuid is required',
            'day.required' => 'Please specify the day',
            'from_time.required' => 'Please enter start time',
            'to_time.required' => 'Please enter ending time',
            'appointment_uuid.required' => 'Appointment Id is missing',
            'schedule_uuid.required' => 'Schedule Id is missing',
            'start.required' => 'Start date is missing',
            'start_date.required' => 'Start date is missing',
            'end_date.required' => 'End date is missing',
            'end.required' => 'End date is missing',
            'date.required' => 'Appointment date Id is missing',
            'status.required' => 'Appointment To time Id is missing',
            'password.required' => 'Password is missing'
        ];
    }

    public static function appointmentUpdateRules() {
        $validate['rules'] = [
            'appointment_uuid' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'local_timezone' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function blocktimeUpdateRules() {
        $validate['rules'] = [
            'blocked_time_uuid' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function scheduleUpdateRules() {
        $validate['rules'] = [
            'freelancer_uuid' => 'required',
            //'start_date' => 'required',
            //'end_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

}

?>
