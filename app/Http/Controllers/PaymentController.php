<?php

namespace App\Http\Controllers;

use App\Helpers\AdminCommonHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MessageHelper;
use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{

    public function getAllPaymentRequests(Request $request)
    {
        try {
            $freelancers = PaymentHelper::getAllFreelancers();
            return view('paymentRequest.list_payment_requests', compact('freelancers'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }


    public function getPaymentRequestDetail($uuid)
    {
        try {
            $data = PaymentHelper::getRequestDetail($uuid);
            if (empty($data)) {
                return redirect()->route('getAllPaymentRequests');
            }
            return view('paymentRequest.payment_request_detail', compact('data'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function rejectPaymentRequest(Request $request)
    {
        try {
            $resp = PaymentHelper::rejectPaymentRequest($request);
            return $resp;
        } catch (\Exception $ex) {
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['general_error']);
        }
    }

    public function approvePaymentRequest(Request $request)
    {
        try {
            $resp = PaymentHelper::approvePaymentRequest($request);
            return $resp;
        } catch (\Exception $ex) {
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['general_error']);
        }
    }

    public function splitOrderNotificationHook(Request $request)
    {

        Log::info($request->all());

        $key_from_configuration = "99B100800B8F598682855275AF0758470BACCB3FCE68A12E5DB9726D04C4BEAB";
        $iv_from_http_header = "000000000000000000000000";
        $auth_tag_from_http_header = "CE573FB7A41AB78E743180DC83FF09BD";
        $http_body = "0A3471C72D9BE49A8520F79C66BBD9A12FF9";

        $key = hex2bin($key_from_configuration);
        $iv = hex2bin($iv_from_http_header);
        $auth_tag = hex2bin($auth_tag_from_http_header);
        $cipher_text = hex2bin($http_body);

        $result = openssl_decrypt($cipher_text, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $auth_tag);

        Log::info($result);
    }

    public function paymentFreelancerListing(Request $request)
    {
        try {
            $freelancers = PaymentHelper::getAllFreelancers();
            return view('paymentTransfer.freelancer-payment-tarnsfer', compact('freelancers'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function paymentTransferList($uuid)
    {
        try {
            $freelancer = PaymentHelper::freelancerInfo($uuid);
            $payments = PaymentHelper::freelanceTransferPayments($uuid);
            return view('paymentTransfer.payment-tarnsfer-list', compact('payments', 'freelancer'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function processPaymentTransfer(Request $request)
    {
        try {
            $inputs = $request->except('_token');
            return (new PaymentHelper)->processPaymentTransfer($inputs);
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function freelancerPaymentRequestDetail($uuid)
    {
        try {
            $withdrawal_detail = (new PaymentHelper)->freelancerPaymentRequestDetail($uuid);
            return view('paymentTransfer.payment-tarnsfer-detail', compact('withdrawal_detail'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function updateFreelancerPaymentTransfer(Request $request)
    {
        try {
            $inputs = $request->except('_token');
            return (new PaymentHelper)->updateFreelancerPaymentTransfer($inputs);
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function transferPaymentPDFDownload($uuid)
    {
        try {
            return (new PaymentHelper)->transferPaymentPDFDownload($uuid);
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function freelancerPaymentRequestList($uuid)
    {
        try {
            $freelancer_withdraws = (new PaymentHelper)->freelancerPaymentRequestList($uuid);
            // dd($freelancer_withdraws);
            return view('paymentTransfer.payment-tarnsfer-detail-list', compact('freelancer_withdraws'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }
}
