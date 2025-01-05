<?php

namespace App\Http\Controllers;

use App\Exports\FundsTransferCSV;
use App\Helpers\AdminCommonHelper;
use App\Helpers\CommonHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\PayoutHelper;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class PayoutController extends Controller
{
    //
    public function getAllFreelancerAvailableEarnings(Request $request)
    {
        try {
            $type = $request->type;
            $freelancers = PayoutHelper::getAllFreelancerAvailableEarnings($type);
            return view('payouts.available-payouts', compact('freelancers','type'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
    public function getAllFreelancersPayouts(Request $request)
    {
        try {
            $type = $request->type;
            $payouts = PayoutHelper::getAllFreelancersPayouts($type);
            return view('payouts.payouts', compact('payouts','type'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function fundsTransferCSV(Request $request)
    {
        # code...
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $freelancers = PayoutHelper::fundsTransferCSV($inputs);
            DB::commit();
            return Excel::download(new FundsTransferCSV($freelancers), $freelancers['funds_transfer_reference_no'].' '.date('Y-m-d h:i:s').'.csv');
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }
    public function payoutDetails(Request $request)
    {
        try {
            $inputs = $request->all();
            $uuid = $inputs['uuid'];
            $freelancers = PayoutHelper::payoutDetails($inputs);
            return view('payouts.detail', compact('freelancers','uuid'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
    public function sendPaymentStatusNotification(Request $request)
    {
        try {
            $inputs = $request->all();
            $freelancer = PayoutHelper::sendNotification($inputs);
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
    public function updatePayoutStatusWithFile(Request $request)
    {
        try {
            $inputs = $request->all();
            DB::beginTransaction();
            PayoutHelper::updatePayoutStatusWithFile($inputs);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $ex) {
            DB::rollBack();
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
    public function freelancerCSV(Request $request)
    {
        try {
            $inputs = $request->all();
            $freelancers['withdraws'] = PayoutHelper::freelancerCSV($inputs);
            return Excel::download(new FundsTransferCSV($freelancers), 'Freelancer_Payouts.csv');

        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
    
}
