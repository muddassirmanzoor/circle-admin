<?php

namespace App\Helpers;

use DB;
use App\Profession;
use Illuminate\Support\Facades\Validator;

class ProfessionHelper
{
    public static function getAllProfessions() {
        return Profession::getAllProfessions();
    }

    public static function saveProfession($inputs = []) {
        $validation = Validator::make($inputs, ProfessionValidationHelper::saveProfessionRules()['rules'], ProfessionValidationHelper::saveProfessionRules()['message_en']);
        if ($validation->fails()) {
            return CommonHelper::jsonErrorResponse($validation->errors()->first());
        }
        $save_profession = Profession::saveProfession($inputs);
        if (!$save_profession) {
            DB::rollBack();
            return redirect()->back()->with('error_message', FreelancerMessageHelper::getMessageData('error')['create_error']);
        }
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
        // echo "<pre>";
        // print_r($inputs);exit;
    }

    public static function deleteProfession($inputs = []) {
        $delete_profession = Profession::updateProfession('profession_uuid',$inputs['profession_uuid'],array('is_archive' => 1));
        DB::commit();
        return response()->json(['response' => 'success']);
       
        
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
    }

    public static function updateProfession($inputs = [])
    {
        $validation = Validator::make($inputs, ProfessionValidationHelper::updateProfessionRules()['rules'], ProfessionValidationHelper::updateProfessionRules()['message_en']);
        if ($validation->fails()) {
            return redirect()->back()->with('error_message', $validation->errors()->first());
        }
        $update = ['name' => $inputs['name']];
        // echo '<pre>';
        // print_r($inputs);exit;
        if(!Profession::updateProfession('profession_uuid', $inputs['profession_uuid'], $update)) {
            DB::rollBack();
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
        }
        DB::commit();
        return redirect()->route('getAllProfessions')->with('success_message', CommonMessageHelper::getMessageData('success')['update_success']);
    }
}
