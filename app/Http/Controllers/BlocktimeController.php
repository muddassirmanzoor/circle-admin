<?php

namespace App\Http\Controllers;

use App\Helpers\AppointmentHelper;
use App\Helpers\BlocktimeHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MessageHelper;
use Illuminate\Http\Request;

class BlocktimeController extends Controller
{
    public function getFreelancerBlocktimes(Request $request)
    {
        try{
            $freelancer_uuid = $request->input('freelancer_uuid');
            $freelancer_blocktimes = BlocktimeHelper::getFreelancerBlocktimings($freelancer_uuid);
            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $freelancer_blocktimes);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateBlocktimeByAdmin(Request $request)
    {
        try{
            $inputs = $request->input();
            return BlocktimeHelper::updateBlocktimeByAdmin($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }
}
