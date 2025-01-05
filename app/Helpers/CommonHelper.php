<?php

namespace App\Helpers;

use App\CodeException;
use App\Exceptions;
use App\SESBounce;
use App\SESComplaint;
use Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Session;
use App\Mail\SendEmailsToUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;

class CommonHelper {
    /*
      |--------------------------------------------------------------------------
      | CommonHelper that contains all the common methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use common processes
      |
     */

    public static $s3_image_paths = [
        'directooo_general' => 'mobileUploads/',
        'mobile_uploads' => 'mobileUploads/',
        'general' => 'uploads/general/',
        'category_image' => 'uploads/category_images/',
        'freelancer_profile_image' => 'uploads/profile_images/freelancers/',
        'freelancer_profile_thumb_1122' => 'uploads/profile_images/freelancers/1122/',
        'freelancer_profile_thumb_420' => 'uploads/profile_images/freelancers/420/',
        'freelancer_profile_thumb_336' => 'uploads/profile_images/freelancers/336/',
        'freelancer_profile_thumb_240' => 'uploads/profile_images/freelancers/240/',
        'freelancer_profile_thumb_96' => 'uploads/profile_images/freelancers/96/',
        'freelancer_cover_image' => 'uploads/cover_images/freelancers/',
        'company_logo' => 'uploads/company_logos/company_logo/',
        'customer_profile_image' => 'uploads/profile_images/customers/',
        'customer_profile_thumb_1122' => 'uploads/profile_images/customers/1122/',
        'customer_profile_thumb_420' => 'uploads/profile_images/customers/420/',
        'customer_profile_thumb_336' => 'uploads/profile_images/customers/336/',
        'customer_profile_thumb_240' => 'uploads/profile_images/customers/240/',
        'customer_profile_thumb_96' => 'uploads/profile_images/customers/96/',
        'customer_cover_image' => 'uploads/cover_images/customers/',
        'cover_video' => 'uploads/videos/cover_videos/',
        'post_image' => 'uploads/posts/post_images/',
        'post_video' => 'uploads/posts/post_videos/',
//        'post_video_thumb' => 'uploads/posts/post_videos_thumb/',
        'post_video_thumb' => 'uploads/posts/post_videos/1212/',
        'package_image' => 'uploads/packages/package_image/',
        'folder_images' => 'uploads/folders/folder_image/',
        'image_stories' => 'uploads/stories/image_stories/',
        'video_stories' => 'uploads/stories/video_stories/',
        'video_story_thumb' => 'uploads/stories/video_story_thumb/',
        'gym_logo' => 'uploads/logos/gym_logo/',
        'class_images' => 'uploads/classes/',
        'freelancer_category_image' => 'uploads/freelancer_category_images/',
        'freelancer_category_video' => 'uploads/freelancer_category_videos/',
        'package_description_video' => 'uploads/packages/package_description_video/',
        'class_description_video' => 'uploads/classes/class_description_video',
        'message_attachments' => 'uploads/message_attachments/',
        'video_thumbnail' => 'uploads/video_thumbnail/',
        'receipt_file' => 'uploads/receipt/',
    ];
    public static $app_email_info = [
        'support_email' => 'adeel.ahmed@ilsainteractive.com',
        'contact_email' => 'adeel.ahmed@ilsainteractive.com',
        'site_title' => 'Circl App',
    ];
    public static $circle_commission = [
        'commision_rate_percentage' => 5,
        'fixed_commision_rate' => 5,
        'hyperpay_fee' => 2.5,
        'withdraw_sar_fee' => 15,
        'withdraw_pound_fee' => 3
    ];

    public static function createDir($category_folder) {
        if (!file_exists($category_folder)) {
            mkdir($category_folder, 0777, true);
        }
    }

    public static function getImageUrl($image, $from) {
        if (!empty($image)) {
            switch ($from) {
                case 'category';
                    return url('public/uploads/categories/' . $image);
                    break;

                case 'sub_category';
                    return url('public/uploads/sub_categories/' . $image);
                    break;

                case 'freelancer';
                    return url('public/uploads/freelancers/' . $image);
                    break;
            }
        }
        return url('public/uploads/default.jpg');
    }

    public static function convertToHash($string = '') {
        return Hash::make($string);
    }


    // return Database date format
    public static function setDbDateFormat($date) {
        return date('Y-m-d', strtotime($date));
    }

    // return User Selected date format
    public static function setUserDateFormat($date) {
        return date('d-m-Y', strtotime($date));
    }

    // return Database time format
    public static function setDbTimeFormat($time) {
        return date('h:i:s', strtotime($time));
    }

    // return User Selected time format
    public static function setUserTimeFormat($time) {
        return date('h:i:s', strtotime($time));
    }

    /**
     * Convert time to another time zone
     * @param type $time
     * @param type $from_timezone
     * @param type $to_timezone
     * @return type
     */
    public static function convertTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('H:i:s'); // 23:30:00
    }

    public static function convertDateTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('Y-m-d H:i:s'); // 2020-8-13 23:30:00
    }

    public static function convertTimeToTimezoneDay($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('D'); // Mon,Tue,Wed...
    }

    public static function convertTimeToTimezoneDate($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('d'); // 1,2,3...
    }

    public static function convertTimeToTimezoneMonth($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('M'); // Jan,Feb,March...
    }

    public static function convertTimeToTimezoneTime($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('h:i A'); // time in 12 hour format with AM or PM
    }

    public static function getTimeDifferenceInMinutes($from_time, $to_time) {
        $start_time = strtotime($from_time);
        $end_time = strtotime($to_time);
        $mintues = round(abs($end_time - $start_time) / 60, 2);
        return $mintues;
    }

    public static function getTimeDifferenceInHours($from_time, $to_time) {
        $t1 = Carbon::parse($from_time);
        $t2 = Carbon::parse($to_time);
        $diff = $t1->diff($t2);
        return $diff;
    }

    public static function getModelInstance($table='App\Customer', $col='customer_uuid', $val='') {
        return $table::where($col, $val)->first();
    }

    public static function CommonExceptions($ex) {
        Log::error('Exception error', [
            'exception' => $ex
        ]);
        $exception = $ex->getMessage();
        return View::make("errors.exception", compact('exception'));
    }

    //return json error response
    public static function errorMessage($error = "Error while request execution") {
        return redirect()->back()->with('message', $error);
    }

    // return success response with data
    public static function successMessage($msg = "Request Successful", $data = []) {
        return redirect()->back()->with('message', $msg);
    }

    //return json error response
    public static function jsonErrorResponse($error = "Error while request execution") {
        $response = [];
        $response['success'] = false;
        $response['message'] = $error;
        return response()->json($response);
    }

    // return success response with data
    public static function jsonSuccessResponse($msg = "Request Successful", $data = []) {
        $response = [];
        $response['success'] = true;
        $response['message'] = $msg;
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }

    public static function successResponse($message = null) {
        $response = [];
        $response['success'] = true;
        $response['message'] = $message;
        return $response;
    }

    public static function successResponseWithData($message = null, $data = null) {
        $response = [];
        $response['success'] = true;
        $response['message'] = $message;
        $response['data'] = $data;
        return $response;
    }

    public static function errorResponse($message = null) {
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        return $response;
    }

    public static function errorResponseWithData($message = null, $data = null) {
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = $data;
        return $response;
    }

    /**
     * storeException method
     * @param type $ex
     */
    public static function storeException($ex) {
        $exception['user_id'] = (Session::has('current_user')) ? Session::get('current_user')->id : 0;
        $exception['exception_file'] = $ex->getFile();
        $exception['exception_line'] = $ex->getLine();
        $exception['exception_message'] = $ex->getMessage();
        $exception['exception_url'] = Request::url();
        $exception['exception_code'] = $ex->getCode();
        CodeException::saveException($exception);
    }

    public static function jsonErrorResponseWithData($message = null, $data = null) {
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = $data;
        return response()->json($response);
    }

    public static function uploadSingleImage($file, $s3_destination, $pre_fix = '', $server = 's3') {
        $full_name = $pre_fix . uniqid() . time() . '.' . $file->getClientOriginalExtension();
        $upload = $file->storeAs($s3_destination, $full_name, $server);
        if ($upload) {
            return ['success' => true, 'file_name' => $full_name];
        }
        return ['success' => false, 'file_name' => ''];
    }

    public static function send_email($template, $data, $attachment = null) {
        $support_email = self::$app_email_info['support_email'];
        $site_title = self::$app_email_info['site_title'];
        $permanentBounceExists = SESBounce::checkIfHardBounce($data['email']);
        $complaintExists = SESComplaint::checkIfNotSpam($data['email']);
        if ($permanentBounceExists || $complaintExists):
            return;
        endif;
//        Mail::send('emails.' . $template, $data, function ($message) use ($support_email, $site_title, $data, $attachment) {
//            $message->from($support_email, $site_title);
//            $message->subject($data['subject']);
//            $message->to($data['email']);
//            if (!empty($attachment)) {
//                $message->attach($attachment, ['as' => 'label.pdf', 'mime' => 'application/pdf']);
//            }
//        });
    }


    // return User Selected date format
    public static function setDateFormat($date, $format = "Y-m-d") {
        return date($format, strtotime($date));
    }

    public static function checkKeyExist($data) {
        $array = [];
        if (!array_key_exists(0, $data)) {
            $array[0] = $data;
        } else {
            $array = $data;
        }
        return $array;
    }

    public static function datePartial($date) {
        $response = [];
        if (!empty($date)) {
            $response['date'] = date("Y-m-d", strtotime($date));
            $response['year'] = date("Y", strtotime($date));
            $response['month'] = date("m", strtotime($date));
            $response['month_name'] = date("M", strtotime($date));
            $response['day'] = date("d", strtotime($date));
            $response['day_name'] = date("D", strtotime($date));
        }
        return $response;
    }

    public static function getEnglishInteger($integer) {
        $alphabat = '';
        if ($integer == 1) {
            $alphabat = '1st';
        }
        if ($integer == 2) {
            $alphabat = '2nd';
        }
        if ($integer == 3) {
            $alphabat = '3rd';
        }
        if ($integer > 3) {
            $alphabat = $integer . 'th';
        }
        return $alphabat;
    }

    public static function resizeImage($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

//    public static function convertDateTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
//        $date = new DateTime($time, new DateTimeZone($from_timezone));
//        $date->setTimezone(new DateTimeZone($to_timezone));
//        return $date->format('d M Y h:i:A');
//    }
//
//    public static function convertTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
//        $date = new DateTime($time, new DateTimeZone($from_timezone));
//        $date->setTimezone(new DateTimeZone($to_timezone));
//        return $date->format('H:i:s'); // 23:30:00
//    }

    public static function getConvertedCurrency($amount, $from_currency, $to_currency) {
        if ($from_currency !== $to_currency) {
            $exchange_rate = self::getExchangeRate($from_currency, $to_currency);
            $result = $amount * $exchange_rate;
//            $result = $amount * config('general.globals.' . $to_currency);
            return round($result, 2);
        } else {
            return round($amount, 2);
        }
    }

    public static function getExchangeRate($from_currency, $to_currency) {
        if (($from_currency == "SAR" && $to_currency == "SAR") || ($from_currency == "SAR" && $to_currency == "Pound")) {
            $rate_obj = CommonHelper::currencyConversionRequest("SAR", "GBP");
            $result = isset($rate_obj->rate) ? $rate_obj->rate : config('general.globals.' . "Pound");
        }

        if (($from_currency == "Pound" && $to_currency == "Pound") || ($from_currency == "Pound" && $to_currency == "SAR")) {
            $rate_obj = CommonHelper::currencyConversionRequest("GBP", "SAR");
            $result = isset($rate_obj->rate) ? $rate_obj->rate : config('general.globals.' . "SAR");
        }
        return ($result) ? $result : null;
    }

    public static function currencyConversionRequest($from_Currency, $to_Currency) {
        $found = true;
        $get = '';
        $json = '';

        try {
            $get = file_get_contents('http://data.fer.io/api/latest?access_key=' . config('general.fixer.fixer_key') . '&base=' . $from_Currency . '&symbols=' . $to_Currency);
            $json = json_decode($get, true);
        } catch (\Throwable $th) {
            $found = false;
        }

        $exchange_rate = new \stdClass();
        if (isset($json['success']) && $json['success'] && $found && isset($json['rates'][$to_Currency])) {

            $exchange_rate->rate = $json['rates'][$to_Currency];
            $exchange_rate->from = $from_Currency;
            $exchange_rate->to = $to_Currency;
        } else {

            $exchange_rate->rate = (strtolower($from_Currency) == "sar" && strtolower($to_Currency) == "gbp") ? config('general.globals.' . "Pound") : config('general.globals.' . "SAR");
            $exchange_rate->to = $to_Currency;
            $exchange_rate->from = $from_Currency;
            /* try {
              $get = \file_get_contents("http://rate-exchange-1.appspot.com/currency?from=" . $from_Currency . "&to=" . $to_Currency);
              $json = json_decode($get, true);

              $exchange_rate->rate = $json['rate'] ;
              $exchange_rate->to = $json['to'];
              $exchange_rate->from = $json['from'];

              } catch (\Throwable $th) {

              } */
        }

        return $exchange_rate;
    }

    public function checkDateHours($date, $hour){
        $new_date = date("Y-m-d H:i:s", strtotime('+'.$hour. ' hour',strtotime($date)));
        $current_date = date("Y-m-d H:i:s");
        if (strtotime($new_date) > strtotime($current_date)){
            return false;
        }
        return true;
    }
}
