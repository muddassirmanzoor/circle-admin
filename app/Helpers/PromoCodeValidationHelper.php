<?php

namespace App\Helpers;

Class PromoCodeValidationHelper {
    /*
      |--------------------------------------------------------------------------
      | PromoCodeValidationHelper that contains all the Freelancer Validation methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use Freelancer processes
      |
     */

    public static function englishMessages() {
        return [
            'freelancer_uuid.required' => 'Freelancer uuid is required',
            'coupon_code.required' => 'Coupon Code is missing',
        ];
    }

    public static function arabicMessages() {
        return [
            'freelancer_uuid.required' => 'uuid لحسابهم الخاص هو المطلوب',
            'coupon_code.required' => 'Coupon Code is missing',
        ];
    }
    
    public static function addPromoCodesRules() {
        $validate['rules'] = [
            //'logged_in_uuid' => 'required',
            'freelancer_uuid' => 'required',
            'coupon_code' => 'required',
            
        ];
        $validate['message_en'] = self::englishMessages();
        $validate['message_ar'] = self::arabicMessages();
        return $validate;
    }
    
    public static function getPromoCodesRules() {
        $validate['rules'] = [
            //'logged_in_uuid' => 'required',
            'freelancer_uuid' => 'required',
            
        ];
        $validate['message_en'] = self::englishMessages();
        $validate['message_ar'] = self::arabicMessages();
        return $validate;
    }
    
    public static function sendPromoCodesRules() {
        $validate['rules'] = [
            //'logged_in_uuid' => 'required',
            'freelancer_uuid' => 'required',
            
        ];
        $validate['message_en'] = self::englishMessages();
        $validate['message_ar'] = self::arabicMessages();
        return $validate;
}

}

?>
