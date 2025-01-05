<?php

namespace App\Helpers;

use App\CodeException;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\View;
use App\Exceptions;
use Request;
use Session;

Class AdminCommonHelper {
    /*
      |--------------------------------------------------------------------------
      | AdminCommonHelper that contains all the common methods for admin
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use common processes
      |
     */

    public static $images_CDN = '';
    public static $s3_images_slug = '';

    public static function AdminExceptions($ex) {
        $exception = $ex->getMessage();
        return View::make("errors.exception", compact('exception'));
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

    public static function jsonSuccessResponse($message = null) {
        $response = [];
        $response['success'] = true;
        $response['message'] = $message;
        return response()->json($response);
    }

    public static function jsonSuccessResponseWithData($message = null, $data = null) {
        $response = [];
        $response['success'] = true;
        $response['message'] = $message;
        $response['data'] = $data;
        return response()->json($response);
    }

    public static function jsonErrorResponse($message = null) {
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        return response()->json($response);
    }

    public static function jsonErrorResponseWithData($message = null, $data = null) {
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = $data;
        return response()->json($response);
    }

    /**
     * Convert time to another timezone
     * @param type $time
     * @param type $from_timezone
     * @param type $to_timezone
     * @return type
     */
    public static function convertTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('H:i:s'); // 10:30:00
    }

}

?>