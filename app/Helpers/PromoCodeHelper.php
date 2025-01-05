<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use App\PromoCode;
use App\Helpers\ApiService;
use App\Helpers\PromoCodeDataHelper;

class PromoCodeHelper
{
    public static function createNewPromoCode($inputs = []) {
        $validation = Validator::make($inputs, PromoCodeValidationHelper::addPromoCodesRules()['rules'], PromoCodeValidationHelper::addPromoCodesRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        $service = new ApiService();

        $res = $service->guzzleRequest('POST','addPromoCodes', $inputs);
        if($res['success']=="true") {
            return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['promo_code_success']);
        }
        else {
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
        }
    }

    public static function getActivePromoCodes() {
        return PromoCode::getActivePromoCodelist();
    }

    public static function getExpiredPromoCodes() {
        return PromoCode::getExpiredPromoCodelist();
    }

    public static function sendPromoCodes($inputs = []) {
        $validation = Validator::make($inputs, PromoCodeValidationHelper::sendPromoCodesRules()['rules'], PromoCodeValidationHelper::sendPromoCodesRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        $inputs['customer_uuid'] = explode(",",$inputs['customer_uuid']);
        $service = new ApiService();
        $res = $service->guzzleRequest('POST','sendPromoCodes', $inputs);
        if($res['success']=="true") {
            return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['send_code_success']);
        }
        else {
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
        }
    }

    public static function getSearchedPromoCode($search)
    {
        $promo_code = PromoCode::where('coupon_code', 'like', '%' .$search . '%')->limit(10)->get();
        return $promo_code;
    }

    public static function archivePromoCodedataById($inputs = [])
    {
        PromoCode::updatePromoCodedata(array('is_archive' => 1), $inputs['code_uuid']);
        return response()->json(['response' => 'success']);
    }

    public static function updatePromoCodedataById($inputs = [])
    {
        $promocode_data = PromoCodeDataHelper::makeUpdatePromoCodeArray($inputs);
        $res = PromoCode::updatePromoCodedata($promocode_data, $inputs['code_uuid']);
        return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['update_code_success']);
    }

}
