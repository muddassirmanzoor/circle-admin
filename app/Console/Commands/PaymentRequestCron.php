<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Helpers\PaymentHelper;
use App\PaymentLog;
use App\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentRequestCron extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentreq:cron';

    /**
     * The console command name To Display.
     *
     * @var string
     */
    protected $commandName = 'HyperPay Payment Request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment Request Cron Job';

    /**
     * File Log Channel
     *
     * @var string
     */
    protected $logChannel = 'cron_payment_request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log('Initiating');

        try{
            DB::beginTransaction();

            $hyperpay_auth = $this->getAuthToken();

            $this->log('HyperPay Auth Result:', [
                'result' => $hyperpay_auth
            ]);
            $request_data = $this->getProcessedRequests();
            if (empty($request_data)):
                $this->log('No remaining Processed request found');
            endif;

            if (empty($hyperpay_auth['data']['accessToken'])):
                $this->log('Hyper Pay access token is empty');
            endif;

            if (isset($hyperpay_auth['data']['accessToken']) && $request_data){
                foreach ($request_data as $req) {
                    $inquiry_info = $this->getSplirOrderInquiry($req['payment_request_uuid'], $hyperpay_auth['data']['accessToken']);
                    if (isset($inquiry_info['data'][0]['payoutStatus']) && ($inquiry_info['data'][0]['payoutStatus'] =="Batched" || $inquiry_info['data'][0]['payoutStatus'] =="Canceled")){
                        $is_processed = ($inquiry_info['data'][0]['payoutStatus'] == "Canceled" ? 3 : 2);

                        PaymentRequest::where('payment_request_uuid', $req['payment_request_uuid'])->update(['is_processed' => $is_processed]);
                        $this->savePaymentLogs($req, $inquiry_info);
                    }
                }
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            $this->log('Process failed with exception', [
                'message' => $exception->getMessage()
            ], 'error');
            $this->InsertDBLog(0);
            return;
        }

        $this->log('Job ended');
        $this->InsertDBLog();

//        Log::info("job ended");
    }

    public function getProcessedRequests(){
        $reqs = PaymentRequest::where('is_processed', 1)->get();
        return $reqs;
    }

    public function getAuthToken(){
        $auth_data = PaymentHelper::sendOrderSplitAuthRequest();
        return $auth_data;
    }

    public function getSplirOrderInquiry($req_uuid, $token){
        $inquiry_info = PaymentHelper::sendInquiryOrderSplitRequest($req_uuid, $token);
        return $inquiry_info;
    }

    public function savePaymentLogs($req, $inquiry_info){

        $pay_log = ['payment_request_uuid' => $req['payment_request_uuid'], 'processed_by' => 'admin', 'gateway_response' => json_encode($inquiry_info),
            'amount' => $req['final_amount'], 'currency' => $req['currency'], 'hyperpay_unique_id' => $inquiry_info['data'][0]['uniqueId']];
        PaymentLog::create($pay_log);

    }

}
