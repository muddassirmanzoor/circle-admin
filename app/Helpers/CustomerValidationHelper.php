<?php

namespace App\Helpers;

Class CustomerValidationHelper
{
    public static function saveCustomerSubsRules() {
        $validate['rules'] = [
            'subscriber_uuid' => 'required',
            'subscribed_uuid' => 'required',
            'subscription_settings_uuid' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function getSubscriptionDetailRules() {
        $validate['rules'] = [
            'subscription_uuid' => 'required'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function englishMessages() {
        return [
            'appointment_uuid.required' => 'Appointment Id is missing',
            'customer_uuid.required' => 'Customer Id is missing',
            'freelancer_uuid.required' => 'Freelancer Id is missing',
            'freelancers_subscriptions.required' => 'Subscription Setting Id is missing',
            'start.required' => 'Start date is missing',
            'end.required' => 'End date is missing',
            'date.required' => 'Appointment date Id is missing',
            'from_time.required' => 'Appointment From time Id is missing',
            'to_time.required' => 'Appointment To time Id is missing',
            'status.required' => 'Appointment To time Id is missing',
            'first_name.required' => 'First name is missing',
            'last_name.required' => 'Last name is missing',
            'profession.required' => 'Profession is missing',
            'company.required' => 'Company name is missing',
            'dob.required' => 'Invalid Date of Birth',
            'email.required' => 'Email is missing',
            'email.unique' => 'Freelancer with this email already exist',
            'phone_number.required' => 'Phone number is missing',
            'password.required' => 'Password is missing',
            'blocked_time_uuid.required' => 'Blocktime uuid is required',
            'day.required' => 'Please specify the day',
            'schedule_uuid.required' => 'Schedule Id is missing',
            'start_date.required' => 'Start date is missing',
            'end_date.required' => 'End date is missing',
        ];
    }

}

?>
