<?php

namespace App\Helpers;

use App\PhoneNumberVerification;
use DB;
use App\Appointment;
use Illuminate\Support\Facades\Validator;

class MainHelper
{
    public static function getAppEarning()
    {
        $data = [];
        $total_amount =  Appointment::getAppEarning();
        $data['total_amount'] = $total_amount;
        $data['percentage'] = 2;
        $data['app_profit'] = self::get_percentage($total_amount,2);
        return $data;
    }

    public static function get_percentage($total, $percentage)
    {
      if ( $total > 0 ) {
       return number_format(($percentage/100)*$total);
      } else {
        return 0;
      }
    }

    public static function getAllMessageCodes(){
        $codes = PhoneNumberVerification::orderBy('updated_at', 'DESC')->get();
        return ($codes) ? $codes->toArray() : [];
    }

}
