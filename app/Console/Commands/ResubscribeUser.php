<?php

namespace App\Console\Commands;

use App\Subscription;
use App\FreelancerTransaction;
use App\Helpers\HyperpayHelper;
use App\Helpers\HyperpayResponseCodesHelper;
use Illuminate\Support\Facades\Log;

class ResubscribeUser extends BaseCommand {

    /**
     * @author ILSA Interactive
     * @var string
     */
    protected $signature = 'subscription:update';

    /**
     * The console command name To Display.
     *
     * @var string
     */
    protected $commandName = 'Subscriptions Update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This job automatically subscribe a user if the existing subscription period is over';

    /**
     * File Log Channel
     *
     * @var string
     */
    protected $logChannel = 'cron_subscription_update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $this->log('Initiating');
        $process_subscription = self::automateProcess();
        if (!$process_subscription['success']) {
            $this->log('Process failed with exception', [
                'message' => $process_subscription['message'] ?? '',
                'recent_subscription' => $process_subscription['recent_subscription'] ?? '',
            ], 'error');
            $this->InsertDBLog(0);
        }
        else{
            $this->log($process_subscription['message']);
            $this->InsertDBLog();
        }
    }

    public function automateProcess() {
        $single_subscription = null;
        try {
            $subscriptions = Subscription::getActiveSubscriptions();
            if (!empty($subscriptions)) {
                foreach ($subscriptions as $key => $single_subscription) {
                    if (empty($single_subscription['subscription_setting'])):
                        continue;
                    endif;
                    $check_date = null;
                    if ($single_subscription['subscription_setting']['type'] == 'monthly') {
                        $check_date = date('Y-m-d', strtotime('+1 month', strtotime($single_subscription['subscription_date'])));
                    } elseif ($single_subscription['subscription_setting']['type'] == 'quarterly') {
                        $check_date = date('Y-m-d', strtotime('+3 months', strtotime($single_subscription['subscription_date'])));
                    } elseif ($single_subscription['subscription_setting']['type'] == 'annual') {
                        $check_date = date('Y-m-d', strtotime('+1 year', strtotime($single_subscription['subscription_date'])));
                    }
                    $current_date = date('Y-m-d');
                    if (!empty($check_date) && $current_date >= $check_date) {
                        $this->log('--------------------');
                        $this->log('Updating: '.$single_subscription['subscription_uuid']);
                        $response = self::updateSubscription($single_subscription);
                        if (!$response['success']):
                            $this->log('Response: ', [
                                'response' => $response
                            ], 'error');
                            throw new \Exception('Unable to update Subscription: '.$single_subscription['subscription_uuid']);
                        else:
                            $this->log('Response: ', [
                                'response' => $response
                            ]);
                        endif;

                        $this->log('Updated: '.$single_subscription['subscription_uuid']);
                        $this->log('--------------------');
                    }
                }
            }
            return ['success' => true, 'message' => 'Update subscription job successfully executed'];
        } catch (\Illuminate\Database\QueryException $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), 'recent_subscription' => $single_subscription];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage(), 'recent_subscription' => $single_subscription];
        }
    }

    public function updateSubscription($subscription = []) {
        $payment_inputs = [
            'amount' => $subscription['subscription_setting']['price'],
            'currency' => strtolower($subscription['subscription_setting']['currency']) == 'pound' ? 'GBP' : $subscription['subscription_setting']['currency'],
//            'currency' => 'SAR',
        ];
        //For testing.
//        $response = new \stdClass();
//        $response->id = 'n3hgj4gj-26f5-652b-51b6-665g872c6g25';
//        $result = [
//            'success' => true,
//            'response' => $response,
//        ];
        $result = self::processRecurringPaymentRequest($payment_inputs, $subscription['card_registration_id']);
        if (!$result['success']) {
            $this->log('HyperPay error:', [
                'response' => $result,
                'payment_inputs' => $payment_inputs
            ], 'error');
            return ['success' => false, 'message' => 'Subscription could not be renewed'];
        }
        $subscription_inputs = [];
        $subscription_inputs['transaction_id'] = $result['response']->id;
        $subscription_inputs['card_registration_id'] = $subscription['card_registration_id'];
        $subscription_inputs['subscription_settings_uuid'] = $subscription['subscription_settings_uuid'];
//        $explode = explode(" ", $subscription['subscription_date']);
//        $subscription_date = self::changeDate(date("Y-m-d"), 2, '+') . " " . $explode[1];
        $subscription_inputs['subscription_date'] = date("Y-m-d H:i:s");
        $subscription_inputs['subscriber_uuid'] = $subscription['subscriber_uuid'];
        $subscription_inputs['subscribed_uuid'] = $subscription['subscribed_uuid'];
        $add_subscription = Subscription::createSubscription($subscription_inputs);
        if (empty($add_subscription)) {
            $this->log('Subscription create error:', [
                'inputs' => $subscription_inputs
            ], 'error');
            return ['success' => false, 'message' => 'Subscription could not be renewed'];
        }
        $this->log('Subscription Created: '.$add_subscription['subscription_uuid']);
        $transaction_inputs = [];
        $transaction_inputs['transaction_id'] = $add_subscription['transaction_id'];
        $transaction_inputs['freelancer_uuid'] = $add_subscription['subscribed_uuid'];
        $transaction_inputs['customer_uuid'] = $add_subscription['subscriber_uuid'];
        $transaction_inputs['content_uuid'] = $add_subscription['subscription_uuid'];
        $transaction_inputs['transaction_type'] = "subscription";
        $transaction_inputs['transaction_user'] = "customer";
        $transaction_inputs['transaction_date'] = date("Y-m-d H:i:s");
        $transaction_inputs['status'] = "confirmed";
        $transaction_inputs['comments'] = "Subscription renewed automatically";
        $transaction_inputs['actual_amount'] = $subscription['subscription_setting']['price'];
        $transaction_inputs['total_amount'] = $subscription['subscription_setting']['price'];
        $transaction_inputs['commission_rate'] = 0;
        $save_transaction = FreelancerTransaction::saveTransaction($transaction_inputs);
        if (!$save_transaction) {
            $this->log('Freelancer Transaction create error: ', [
                'inputs' => $transaction_inputs
            ], 'error');
            return ['success' => false, 'message' => 'Transaction data could not be saved'];
        }
        $this->log('Freelancer Transaction Created: '.$save_transaction['freelancer_transaction_uuid']);
        //Archive previous Subscription
        Subscription::where('subscription_uuid', '=', $subscription['subscription_uuid'])->update([
            'is_archive' => 1
        ]);
        return ['success' => true, 'message' => 'Subscription updated successfully'];
    }

    public static function processRecurringPaymentRequest($payment_data = [], $registration_id = null) {
        $amount = !empty($payment_data['amount']) ? $payment_data['amount'] : 0;
        $currency = !empty($payment_data['currency']) ? $payment_data['currency'] : 'SAR';
        $paymentType = 'DB';
        $url = config('general.globals.hyperpay_base_address') . "/v1/registrations/" . $registration_id . "/payments";
        $request_inputs = "entityId=" . config('general.globals.hyperpay_entity_id') .
                "&amount=" . $amount .
                "&currency=" . $currency .
                "&paymentType=" . $paymentType .
                "&shopperResultUrl=" . url('/') .
                "&testmode=EXTERNAL" .
                "&recurringType=REPEATED";
        $request_response = HyperpayHelper::sendHyperpayRequest($url, 'post', $request_inputs);
        Log::channel('cron_subscription_update')->info('HyperPay Response: ', [
            'response' => $request_response,
            'url' => $url,
            'inputs' => $request_inputs,
        ]);
        $decoded_response = json_decode($request_response);
        if (!empty($decoded_response->result->code) && in_array($decoded_response->result->code, HyperpayResponseCodesHelper::$success_codes)) {
            return ['success' => true, 'response' => $decoded_response, 'message' => $decoded_response->result->description];
        }
        return ['success' => false, 'response' => $decoded_response, 'message' => $decoded_response->result->description];
    }
}
