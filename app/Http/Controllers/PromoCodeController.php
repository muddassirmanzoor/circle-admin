<?php

namespace App\Http\Controllers;

use App\Helpers\PromoCodeHelper;
use App\Helpers\CommonMessageHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PromoCode;
use App\Freelancer;


class PromoCodeController extends Controller
{
    public function addPromoCode($id =null)
    {
        try {
            $data = [];
            $data['freelancer_uuid'] = $id;
            return view('promoCode.add-new-promocode', $data);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function sendPromoCodeForm($id =null)
    {
        try {
            $data = [];
            $data['freelancer_uuid'] = $id;
            return view('promoCode/send-promocode', $data);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
        return view('promoCode/send-promocode');
    }

    public function createNewPromoCode(Request $request)
    {
    	try {
            $inputs = $request->input();
            return PromoCodeHelper::createNewPromoCode($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updatePromoCode(Request $request)
    {
    	try {
            $inputs = $request->input();
            return PromoCodeHelper::updatePromoCodedataById($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getActivePromoCodes(Request $request)
    {
    	try {
    		$search = $request->q;
    		if($search == ''){
    			$title = "Active";
        		$promoCodes = PromoCodeHelper::getActivePromoCodes();
        		return view('promoCode/promocode_listing', compact('promoCodes', 'title'));
        	}
        	else
            {
            	$promoCodes = PromoCodeHelper::getSearchedPromoCode($search);
                $response = array();
                foreach($promoCodes as $promoCode){
                    $response[] = array("id"=>$promoCode->code_uuid,"label"=>$promoCode->coupon_code);
                }
                echo json_encode($response);
                exit;
		    }
		} catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
	}

    public function getExpiredPromoCodes()
    {
    	try {
    		$title = "Expired";
        	$promoCodes = PromoCodeHelper::getExpiredPromoCodes();
        	return view('promoCode/promocode_listing', compact('promoCodes', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function sendPromoCodes(Request $request) {
        try {
            $inputs = $request->input();
            return PromoCodeHelper::sendPromoCodes($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function deletePromoCode(Request $request)
    {
    	try {
            $inputs = $request->all();
            return PromoCodeHelper::archivePromoCodedataById($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function editPromoCode($id)
    {
    	$result = PromoCode::where('code_uuid',$id)->first();
        $freelancer_detail = Freelancer::where('freelancer_uuid',$result['freelancer_uuid'])->first();
    	return view('promoCode/edit-promocode', compact('result','freelancer_detail'));
    }

}
