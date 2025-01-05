<?php

namespace App\Helpers;

use App\Freelancer;
use App\FreelancerEarning;
use App\FreelancerWithdrawal;
use App\PaymentDue;
use App\PaymentLog;
use App\PaymentRequest;
use App\SystemSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentHelper
{
    public static function getAllRequests()
    {
        $data = [];
        $requests = PaymentRequest::getPaymentRequests("all");

        foreach ($requests as $ind => $req) {
            $data[$ind]['no'] =  $ind + 1;
            $data[$ind]['freelancer'] =  $req['freelancer'];
            $data[$ind]['requested_amount'] =  round($req['requested_amount'], 2);
            $data[$ind]['deductions'] =  round($req['deductions'], 2);
            $data[$ind]['final_amount'] =  round($req['final_amount'], 2);
            $data[$ind]['currency'] =  $req['currency'];
            $data[$ind]['date'] = CommonHelper::convertDateTimeToTimezone($req['datetime'], 'UTC', 'Europe/Paris');
            $data[$ind]['status'] =  $req['is_processed'];
            $data[$ind]['action_detail'] =  route('getPaymentRequestDetail', $req['payment_request_uuid']);
        }

        return $data;
    }

    public static function getRequestDetail($req_uuid)
    {
        $data = [];
        $req_detail = PaymentRequest::getPaymentRequestDetail($req_uuid);

        if (!empty($req_detail)) {
            $due_details = PaymentDue::getUserAllPaymentDues($req_detail['freelancer']['freelancer_uuid'] ?? '', $req_detail['datetime']);
            $data['pay_req'] = self::arrangeRequestData($req_detail);
            $data['pay_dues'] = self::arrangePaymentDuesData($due_details);
        }

        return $data;
    }

    public static function arrangeRequestData($req_data)
    {
        $data = [];
        if (!empty($req_data)) {
            $withdraw_info = self::getFreelancerWithdrawDetails($req_data);
            $data['req_uuid'] =  $req_data['payment_request_uuid'];
            $data['freelancer'] =  $req_data['freelancer'];
            $data['freelancer']['total_earnings'] =  round($withdraw_info['total_amount'], 2);
            $data['freelancer']['total_withdrwal'] = round($withdraw_info['completed_withdraw'], 2);
            $data['freelancer']['available_balance'] =  round($withdraw_info['available_withdraw'], 2);
            $data['freelancer']['pending_balance'] =  round($withdraw_info['pending_withdraw'], 2);
            $data['requested_amount'] =  round($req_data['requested_amount'], 2);
            $data['deductions'] =  round($req_data['deductions'], 2);
            $data['final_amount'] =  round($req_data['final_amount'], 2);
            $data['date'] =  CommonHelper::convertDateTimeToTimezone($req_data['datetime'], 'UTC', 'Europe/Paris');
            $data['status'] =  $req_data['is_processed'];
            $data['currency'] =  $req_data['currency'];
        }

        return $data;
    }

    public static function getFreelancerWithdrawDetails($req_data)
    {
        $response['total_amount'] = PaymentDue::getUserTotalEarnings($req_data['freelancer']['freelancer_uuid'], false, $req_data['freelancer']['default_currency']);
        $response['completed_withdraw'] = PaymentRequest::getPaymentRequestAmount(2, $req_data['freelancer']['freelancer_uuid']);
        $response['requested_withdraw'] = PaymentRequest::getPaymentRequestAmount(0, $req_data['freelancer']['freelancer_uuid']);
        $response['processed_withdraw'] = PaymentRequest::getPaymentRequestAmount(1, $req_data['freelancer']['freelancer_uuid']);
        $response['pending_withdraw'] = PaymentDue::getUserPendingBalance($req_data['freelancer']['freelancer_uuid'], $req_data['freelancer']['default_currency']);
        $response['pending_withdraw'] = round($response['pending_withdraw'], 2);
        $response['available_withdraw'] = self::calculateFreelancerAvailableWithdraw($req_data, $response);

        return $response;
    }

    public static function calculateFreelancerAvailableWithdraw($req_data, $response)
    {
        $total_payment_dues = PaymentDue::getUserTotalEarnings($req_data['freelancer']['freelancer_uuid'], true, $req_data['freelancer']['default_currency']);
        $total_payment_dues = $total_payment_dues - ($response['completed_withdraw'] + $response['requested_withdraw'] + $response['processed_withdraw']);
        $total_payment_dues = $total_payment_dues < 0 ? 0 : $total_payment_dues;
        return $total_payment_dues;
    }

    public static function arrangePaymentDuesData($due_data)
    {
        $data = [];
        if (!empty($due_data)) {
            foreach ($due_data as $ind => $pay_due) {
                $data[$ind]['no'] = $ind + 1;
                $data[$ind]['pay_due_uuid'] = $pay_due['payment_due_uuid'];
                $data[$ind]['freelancer_transaction'] = $pay_due['freelancer_transaction'];
                $data[$ind]['sar_amount'] = round($pay_due['sar_amount'], 2);
                $data[$ind]['pound_amount'] = round($pay_due['pound_amount'], 2);
                $data[$ind]['due_date'] = CommonHelper::convertDateTimeToTimezone($pay_due['due_date'], 'UTC', 'Europe/Paris');
                $data[$ind]['status'] = $pay_due['status'] == 1 ? "processed" : "pending";
            }
        }

        return $data;
    }

    public static function rejectPaymentRequest($request)
    {
        if ($request->has('req_id')) {
            $req_uuid = $request->req_id;
            $data = ['is_processed' => 3];
            $resp = PaymentRequest::updatePaymentRequest($req_uuid, $data);
            return CommonHelper::jsonSuccessResponse("Updated Successfully.");
        } else {
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['request_id_not_found']);
        }
    }

    public static function approvePaymentRequest($request)
    {
        if ($request->has('req_id')) {
            $req_detail = PaymentRequest::getPaymentRequestDetail($request->req_id);

            if (empty($req_detail['freelancer']['bank_detail']['iban_account_number'])) {
                return CommonHelper::jsonErrorResponse("freelancer's IBAN is missing");
            }

            $auth_resp = self::sendOrderSplitAuthRequest();
            if (!isset($auth_resp['data']['accessToken'])) {
                return CommonHelper::jsonErrorResponse("Order authentication failed.");
            }

            $transfer_resp = '';
            $transfer_resp = self::sendCreateOrderSplitRequest($req_detail, $auth_resp);

            if (!isset($transfer_resp['data']['uniqueId'])) {
                return CommonHelper::jsonErrorResponse("Transfer failed.");
            }

            $resp = self::saveOrderDetails($req_detail, $transfer_resp);
            return CommonHelper::jsonSuccessResponse("Transferred Successfully.");
        } else {
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['request_id_not_found']);
        }
    }

    public static function sendOrderSplitAuthRequest()
    {
        $client = new Client();
        $data = [
            'json' => [
                'email' => 'philip@al-anazi.com',
                'password' => '12fg345df'
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ];

        $res = $client->request('POST', 'https://splits.sandbox.hyperpay.com/api/v1/login', $data);
        $res = $res->getBody()->getContents();

        return json_decode($res, true);
    }

    public static function sendCreateOrderSplitRequest($req_detail, $auth_data)
    {
        if (isset($auth_data['data']['accessToken'])) {
            $reqBody = self::prepareOrderCreateRequestBody($req_detail, $auth_data);

            $client = new Client();
            $res = $client->request('POST', 'https://splits.sandbox.hyperpay.com/api/v1/orders', $reqBody);
            $res = $res->getBody()->getContents();

            return json_decode($res, true);
        }
    }

    public static function prepareOrderCreateRequestBody($req_detail, $auth_data)
    {
        $transfer_currency = strtolower($req_detail['currency'] == 'sar') ? 'SAR' : 'GBP';
        $auth_token = "Bearer " . $auth_data['data']['accessToken'];
        $bank_detail = $req_detail['freelancer']['bank_detail'];
        $data = [
            'json' => [
                'merchantTransactionId' => $req_detail['payment_request_uuid'],
                'transferOption' => 7,
                'period' => date('Y-m-d'),
                'batchDescription' => 'Transfer amount to ' . $req_detail['freelancer']['email'] ?? '',
                'configId' => '8af820eaa609ff03963cdd28c3cf15a1',
                'beneficiary' => [
                    [
                        'name' => $bank_detail['account_name'] ?? '',
                        'accountId' => $bank_detail['iban_account_number'],
                        'debitCurrency' => 'SAR',
                        'transferAmount' => $req_detail['final_amount'],
                        'transferCurrency' => $transfer_currency,
                        'bankIdBIC' => $req_detail['swift_code'] ?? '',
                        'payoutBeneficiaryAddress1' => $bank_detail['address'] ?? '',

                    ]
                ]
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $auth_token
            ]
        ];

        return $data;
    }

    public static function saveOrderDetails($req_detail, $transfer_resp)
    {
        $pay_log = [
            'payment_request_uuid' => $req_detail['payment_request_uuid'], 'processed_by' => 'admin', 'gateway_response' => json_encode($transfer_resp),
            'amount' => $req_detail['final_amount'], 'currency' => $req_detail['currency'], 'hyperpay_unique_id' => $transfer_resp['data']['uniqueId']
        ];
        PaymentLog::create($pay_log);

        $new_req_data = ['is_processed' => 1];
        PaymentRequest::updatePaymentRequest($req_detail['payment_request_uuid'], $new_req_data);
    }

    public static function sendInquiryOrderSplitRequest($req_uuid, $token)
    {
        $auth_token = "Bearer " . $token;
        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $auth_token
            ]
        ];

        $client = new Client();
        $res = $client->request('GET', 'https://splits.sandbox.hyperpay.com/api/v1/orders/' . $req_uuid, $headers);
        $res = $res->getBody()->getContents();

        return json_decode($res, true);
    }

    public static function refundTransactionApi($payment_id, $amount, $currency)
    {
        $entityId = '8ac7a4c8730e778101730ea96d810154';
        $access_token = 'Bearer OGFjN2E0Yzc3MmE4Zjg3YzAxNzJiMWUyNDhlYzE5YzN8WFFDNTVoTnJzeg==';
        $body = [
            'headers' => [
                'Authorization' => $access_token
            ],
            'body' => [
                'entityId' => $entityId,
                'paymentType' => 'RF',
                'amount' => $amount,
                'currency' => $currency
            ]
        ];

        $client = new Client();
        $res = $client->request('POST', 'https://test.oppwa.com/v1/payments/' . $payment_id, $body);
        $res = $res->getBody()->getContents();

        return json_decode($res, true);
    }

    public static function getAllFreelancers()
    {
        $data = [];
        $freelancers = Freelancer::with(['freelancer_earnings' => function ($sql) {
            $sql->where('freelancer_withdrawal_id', null);
        }])->where(['is_verified' => 0, 'is_active' => 1, 'is_archive' => 0])->orderByDesc('id')->get();

        foreach ($freelancers as $ind => $freelancer) {
            $data[$ind]['freelancer_uuid'] =  $freelancer['freelancer_uuid'];
            $data[$ind]['freelancer'] =  $freelancer['first_name'] . ' ' . $freelancer['last_name'];
            $data[$ind]['email'] =  $freelancer['email'];
            $data[$ind]['default_currency'] =  $freelancer['default_currency'];
            $data[$ind]['profession'] =  $freelancer['profession'];
            $data[$ind]['total_earning'] =  $freelancer['freelancer_earnings']->sum('earned_amount');
        }

        return $data;
    }
    public static function getAllFreelancerAvailablePayouts()
    {
        $data = [];
        $freelancers = Freelancer::with(['freelancer_earnings' => function ($sql) {
            $sql->whereNull('freelancer_withdrawal_id')->whereNull('funds_transfers_id')->where('is_archive',0);
        }])->where(['is_verified' => 0, 'is_active' => 1, 'is_archive' => 0])->orderByDesc('id')->get();

        foreach ($freelancers as $ind => $freelancer) {
            $data[$ind]['freelancer_uuid'] =  $freelancer['freelancer_uuid'];
            $data[$ind]['freelancer'] =  $freelancer['first_name'] . ' ' . $freelancer['last_name'];
            $data[$ind]['email'] =  $freelancer['email'];
            $data[$ind]['default_currency'] =  $freelancer['default_currency'];
            $data[$ind]['profession'] =  $freelancer['profession'];
            $data[$ind]['total_earning'] =  $freelancer['freelancer_earnings']->sum('earned_amount');
        }

        return $data;
    }
    public static function freelancerInfo($freelancer_uuid)
    {
        $earnings = Freelancer::with('bank_detail')->where(['is_verified' => 0, 'is_active' => 1, 'is_archive' => 0, 'freelancer_uuid' => $freelancer_uuid])->first();
        return $earnings->toArray();
    }

    public static function freelanceTransferPayments($freelancer_uuid)
    {
        $freelancer = CommonHelper::getModelInstance('App\Freelancer', 'freelancer_uuid', $freelancer_uuid);
        $result = FreelancerEarning::with('freelancer')->where(['freelancer_withdrawal_id' => null, 'freelancer_id' => $freelancer->id])->orderByDesc('id')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public function processPaymentTransfer($inputs)
    {
        $draw_validate = $this->validateWithDrawDate($inputs);
        if (!$draw_validate) {
            return redirect()->back()->with('error_message', 'Unable to withdraw amount, please check system settings');
        }

        $withdraw_request_params = $this->makeWithdrawRequestDictionary($inputs, 'save_transfer_request');
        DB::beginTransaction();
        $withdraw = FreelancerWithdrawal::create($withdraw_request_params);

        if ($withdraw) {
            foreach ($inputs['freelancer_earning_id'] as $id) {
                FreelancerEarning::where('id', $id)->update(['freelancer_withdrawal_id' => $withdraw->id]);
            }
            DB::commit();
            return redirect(route("freelancerPaymentRequestDetail", ['uuid' => $withdraw->freelancer_withdrawal_uuid]))->with(['success_message' => 'Saved Successfully']);
        }
    }

    public static function validateWithDrawDate($inputs)
    {
        $validate_status = true;
        $system_setting = SystemSetting::where('is_active', 1)->first();
        if (!$system_setting) {
            return false;
        }
        $common_helper = new CommonHelper();
        $withdraw = FreelancerWithdrawal::getLatesFreelancerWithdrawalRequest($inputs['freelancer_id']);

        if ($withdraw) {
            if ($system_setting['withdraw_scheduled_duration'] == 0) {
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 168) === false) {
                    $validate_status = false;
                }
            } elseif ($system_setting['withdraw_scheduled_duration'] == 1) {
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 336) === false) {
                    $validate_status = false;
                }
            } else {
                if ($common_helper->checkDateHours($withdraw['last_withdraw_date'], 730) === false) {
                    $validate_status = false;
                }
            }
        }
        return $validate_status;
    }


    public function makeWithdrawRequestDictionary($inputs, $dictionary_type)
    {
        $data = [];

        if ($dictionary_type == 'update_transfer') {
            $data['reciept_id']                 = $inputs['receipt_id'];
            $data['reciept_url']                = $inputs['file'];
            $data['receipt_date']               = date('Y-m-d');
            $data['schedule_status']            = $inputs['schedule_status'];
            return $data;
        }

        $data['freelancer_withdrawal_uuid']    = Str::uuid()->toString();
        $data['invoice_id']                    = Str::random(13);
        $data['freelancer_id']                 = $inputs['freelancer_id'];
        $data['amount']                        = $inputs['amount'];
        $data['currency']                       = $inputs['currency'];
        $data['transaction_charges']           = 0;
        $data['receipt_date']                  = date('Y-m-d');
        $data['last_withdraw_date']            = $this->getLastWithdrawDate($inputs);
        $data['account_name']                  = $inputs['account_name'];
        $data['account_title']                 = $inputs['account_title'];
        $data['account_number']                = $inputs['account_number'] ?? null;
        $data['iban_account_number']           = $inputs['iban_account_number'] ?? null;

        return $data;
    }

    public static function getLastWithdrawDate($inputs)
    {
        $date = '';
        $system_setting = SystemSetting::where('is_active', 1)->first();
        $withdraw = FreelancerWithdrawal::getLatesFreelancerWithdrawalRequest($inputs['freelancer_id']);

        if ($withdraw && $system_setting) {
            if ($system_setting['withdraw_scheduled_duration'] == 0) {
                $date = date("Y-m-d", strtotime('+168 hour', strtotime($withdraw['last_withdraw_date'])));
            } elseif ($system_setting['withdraw_scheduled_duration'] == 1) {
                $date = date("Y-m-d", strtotime('+336 hour', strtotime($withdraw['last_withdraw_date'])));
            } else {
                $date = date("Y-m-d", strtotime('+730 hour', strtotime($withdraw['last_withdraw_date'])));
            }
        } else {
            $date = date('Y-m-d');
        }
        return $date;
    }

    public function freelancerPaymentRequestDetail($uuid)
    {
        $withdrawal = FreelancerWithdrawal::with('withdrawalEarnings', 'withdrawalFreelancer.bank_detail')->where('freelancer_withdrawal_uuid', $uuid)->first();
        return !empty($withdrawal) ? $withdrawal->toArray() : [];
    }

    public function updateFreelancerPaymentTransfer($inputs)
    {
        // if (!empty($inputs['receipt_url'])) {
        //     $file_size = $inputs['receipt_url']->getSize();

        //     if ($file_size > 1000000) {
        //         return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['max_size_error']);
        //     }
        //     $upload_image = CommonHelper::uploadSingleImage($inputs['receipt_url'], CommonHelper::$s3_image_paths['receipt_file']);

        //     if (!$upload_image['success']) {
        //         return redirect()->back()->with('error', 'Image could not be uploaded');
        //     }
        //     $inputs['file'] = !empty($upload_image['file_name']) ? $upload_image['file_name'] : null;
        // }
        $inputs['file'] = null;
        $withdraw_update_params = $this->makeWithdrawRequestDictionary($inputs, 'update_transfer');

        DB::beginTransaction();
        $withdraw = FreelancerWithdrawal::where('freelancer_withdrawal_uuid', $inputs['uuid'])->update($withdraw_update_params);
        DB::commit();

        if ($withdraw) {
            return response()->json(['message' => 'Successfully Updated !', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Something is going wrong please try again!.', 'status' => 500]);
        }
    }

    public function transferPaymentPDFDownload($uuid)
    {
        $withdrawal_data = FreelancerWithdrawal::with('withdrawalEarnings', 'withdrawalFreelancer')->where('freelancer_withdrawal_uuid', $uuid)->first();
        $pdf = Pdf::loadView('pdf.transfer-payment-pdf', compact('withdrawal_data'));
        return $pdf->download(time() . '_invoice.pdf');
    }

    public function freelancerPaymentRequestList($uuid)
    {
        $freelance = CommonHelper::getModelInstance('App\Freelancer', 'freelancer_uuid', $uuid);
        $withdrawal = FreelancerWithdrawal::with('withdrawalEarnings', 'withdrawalFreelancer')->where('freelancer_id', $freelance->id)->get();
        return !empty($withdrawal) ? $withdrawal->toArray() : [];
    }
}
