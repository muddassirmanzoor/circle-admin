<?php

namespace App\Helpers;

// use App\Exports\FundsTransferCSV;

use App\Events\NotificationEvent;
use App\Exports\FundsTransferCSV;
use App\Freelancer;
use App\FreelancerEarning;
use App\FreelancerWithdrawal;
use App\FundsTransfer;
use App\Imports\FundsTransferImport;
use App\Imports\PayoutStatusImport;
use App\PaymentDue;
use App\PaymentLog;
use App\PaymentRequest;
use App\SystemSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Uuid;

class PayoutHelper
{
    public static function getAllFreelancerAvailableEarnings($type = null)
    {
        $data = [];
        $freelancers = Freelancer::with(['freelancer_earnings'=>function($q) use($type){
            $date = strtotime(date('Y-m-d H:i:s'));
            if($type == 'available')
            {
                $q->whereNull('freelancer_withdrawal_id')
                ->whereNull('funds_transfers_id')
                ->where('amount_due_on', '<', $date)
                ->where('earned_amount','>',0)
                ->whereIn('transfer_status',['pending']);
            }
            if($type == 'progress')
            {
                $q->whereNull('freelancer_withdrawal_id')
                ->where('transfer_status','in_progress')
                ->whereNotNull('funds_transfers_id');
            }
            if($type == 'completed')
            {
                $q->whereNotNull('freelancer_withdrawal_id')
                ->where('transfer_status','transferred')
                ->whereNotNull('funds_transfers_id');
            }
            if($type == 'failed')
            {
                $q->whereNotNull('freelancer_withdrawal_id')
                ->where('transfer_status','failed')
                ->whereNotNull('funds_transfers_id');
            }
            $q->where('is_archive',0);
        }])->whereHas('freelancer_earnings',function($q) use ($type){
            $date = strtotime(date('Y-m-d H:i:s'));
            if($type == 'available')
            {
                $q->whereNull('freelancer_withdrawal_id')
                ->whereNull('funds_transfers_id')
                ->where('amount_due_on', '<', $date)
                ->where('earned_amount','>',0)
                ->whereIn('transfer_status',['pending']);
            }
            if($type == 'progress')
            {
                $q->whereNull('freelancer_withdrawal_id')
                ->where('earned_amount','>',0)
                ->where('transfer_status','in_progress')
                ->whereNotNull('funds_transfers_id');
            }
            if($type == 'completed')
            {
                $q->whereNotNull('freelancer_withdrawal_id')
                ->where('earned_amount','>',0)
                ->where('transfer_status','transferred')
                ->whereNotNull('funds_transfers_id');
            }
            if($type == 'failed')
            {
                $q->whereNotNull('freelancer_withdrawal_id')
                ->where('earned_amount','>',0)
                ->where('transfer_status','failed')
                ->whereNotNull('funds_transfers_id');
            }
            $q->where('is_archive',0);
        })->where('has_bank_detail',1)->where(['is_verified' => 0, 'is_active' => 1, 'is_archive' => 0])->orderByDesc('id')->get();
        foreach ($freelancers as $ind => $freelancer) {
            $data[$ind]['freelancer_uuid'] =  $freelancer['freelancer_uuid'];
            $data[$ind]['freelancer'] =  $freelancer['first_name'] . ' ' . $freelancer['last_name'];
            $data[$ind]['email'] =  $freelancer['email'];
            $data[$ind]['default_currency'] =  $freelancer['default_currency'];
            $data[$ind]['profession'] =  $freelancer['profession'];
            $data[$ind]['status'] =  $freelancer;
            $data[$ind]['total_earning'] =  $freelancer['freelancer_earnings']->sum('earned_amount');
        }

        return $data;
    }
    public static function getAllFreelancersPayouts($type= null)
    {

        $funds_transfers = FundsTransfer::with('freelancer_earnings')->where('status',$type)->orderBy('id','desc')->get();
        $data = [];
        foreach ($funds_transfers as $ind => $funds_transfer) {
            $data[] = [
                'funds_transfer_uuid'=>$funds_transfer['funds_transfer_uuid'],
                'reference_no'=>$funds_transfer['reference_no'],
                'connect_id'=>$funds_transfer['connect_id'],
                'file_customer_id'=>$funds_transfer['file_customer_id'],
                'file_authorization_type'=>$funds_transfer['file_authorization_type'],
                'total_number_of_payments'=>$funds_transfer['total_number_of_payments'],
                'total_amount'=>$funds_transfer['freelancer_earnings']->sum('earned_amount'),
                'status'=>$funds_transfer['status'],
                'is_transfered'=>$funds_transfer['is_transfered'],
                'created_date'=>$funds_transfer['created_at']->format('Y-m-d'),
                'created_time'=>$funds_transfer['created_at']->format('h:i:a'),

            ];
        }
        return $data;
    }
    public static function payoutDetails($inputs)
    {
        $payouts = FundsTransfer::with('freelancer_earnings')->where('funds_transfer_uuid',$inputs['uuid'])
        ->first();
        $freelancer_withdrawl_ids = $payouts['freelancer_earnings']->unique('freelancer_withdrawal_id')->pluck('freelancer_withdrawal_id');
        $freelancers = FreelancerWithdrawal::with('withdrawalFreelancer')->whereIn('id',$freelancer_withdrawl_ids)->orderBy('id')->get();
        return !empty($freelancers)?$freelancers->toArray():[];
    }
    public static function fundsTransferCSV($inputs)
    {
        $date = strtotime(date('Y-m-d H:i:s'));

        $freelancers = Freelancer::getFreelancersForFundsTransfer('is_archive',0);
        if(count($freelancers) < 1)
        {
            return redirect()->back()->with('error_message', 'Payout already initiated');
        }
        $funds_transfer_data = self::prepareFundsTransferParams(count($freelancers));
        $funds_transfer = FundsTransfer::create($funds_transfer_data);
        // FreelancerEarning::updateFreelancerEarningWithFundsTransfer($funds_transfer['id']);
        // $freelancer_earnings = FreelancerEarning::getFreelancerEarnings('funds_transfers_id',$funds_transfer['id']);
        $freelancer_payouts['withdraws'] = self::prepareFreelancerWithdrawlParams($freelancers,$funds_transfer);
        $freelancer_payouts['funds_transfer_reference_no'] = $funds_transfer['reference_no'];
        // return Excel::download(new FundsTransferCSV($freelancers), $freelancers['funds_transfer_reference_no'].' '.date('Y-m-d h:i:s').'.csv');
        // event(new NotificationEvent($data));

        return $freelancer_payouts;
    }

    public static function prepareFundsTransferParams($total_number_of_payments)
    {
        return [
            // 'funds_transfer_uuid' =>
            'reference_no'=>rand(00000000,99999999),
            'connect_id'=>'ABV',
            'file_customer_id'=>'ABV',
            'file_authorization_type'=>'P',
            'total_number_of_payments'=>$total_number_of_payments,
            'status'=>'in_progress',
            'is_transfered'=>'0',
            'batch_no'=>rand(000000,999999)
        ];

    }

    public static function prepareFreelancerWithdrawlParams($freelancers,$payout)
    {
        $data = [];
        if(!empty($freelancers))
        {
            $value_date = new \DateTime();
            $value_date->modify('+2 day');
            $value_date = $value_date->format('Y-m-d');
            foreach($freelancers as $freelancer)
            {
                // dd($freelancer['freelancer_earnings']->sum('earned_amount'));
                // foreach($freelancer as $freelancer_earning)
                // {
                //     $earned_amount +=
                // }
                $freelancer_ids = $freelancer['freelancer_earnings']->pluck('id')->toArray();
                $data = [
                    'freelancer_withdrawal_uuid'=>Uuid::uuid4()->toString(),
                    'freelancer_id' => $freelancer['id'],
                    'fp_account_title' => 'NABRAS FOR TECHNICAL CO',
                    'fp_account_number' => '011-831989-082',
                    'fp_iban_account_number' => 'SA3645000000011831989082',
                    'fp_currency' => 'POUND',
                    'fp_currency_code' => 'GBP',
                    'value_date' => $value_date,
                    'fp_ordering_party_name' => 'Nebras Co.',
                    'fp_ordering_party_address_1' => 'RGHA7010',
                    'fp_ordering_party_address_2' => null,
                    'fp_ordering_party_address_3' => null,
                    'fp_payment_reference' => rand(000000,999999),
                    'amount' => $freelancer['freelancer_earnings']->sum('earned_amount'),
                    'currency' => $freelancer['default_currency'],
                    'currency_code' => (strtolower($freelancer['default_currency']) == 'pound')?'GBP':'SA',
                    // 'transaction_charges' => $freelancer[''],
                    'account_title' => $freelancer['bank_detail']['account_title'],
                    'account_name' => $freelancer['bank_detail']['account_name'],
                    'account_number' => $freelancer['bank_detail']['account_number'],
                    'iban_account_number' => $freelancer['bank_detail']['iban_account_number'],
                    'beneficiary_address_1' =>  $freelancer['primaryLocation']['location']['address'],
                    'beneficiary_address_2' => $freelancer['secondaryLocation']['location']['address'] ?? '',
                    // 'beneficiary_address_1' =>  null,
                    // 'beneficiary_address_2' => null,
                    'beneficiary_address_3' => null,
                    'swift_code' => $freelancer['bank_detail']['sort_code'],
                    'last_withdraw_date' => date('Y-m-d'),
                    'schedule_status' => 'in_progress',
                    'transfer_status' => 'in_progress',
                    'bank_country' => $freelancer['bank_detail']['location_type']

                ];
                $freelancer_withdrawl = FreelancerWithdrawal::create($data);
                $data['email'] = $freelancer['email'];
                $freelancer_payouts[] = $data;
                FreelancerEarning::whereIn('id',$freelancer_ids)->update(['freelancer_withdrawal_id' => $freelancer_withdrawl['id'],'funds_transfers_id' => $payout['id'],'transfer_status' => 'in_progress']);
                $data['payout_uuid'] = $payout['funds_transfer_uuid'];
                ProcessNotificationHelper::sendPaymentStatusNotificationToFreelancer($data);
            }
        }
        return $freelancer_payouts;
    }


    public static function updatePayoutStatusWithFile($inputs) {

        Excel::import(new PayoutStatusImport, $inputs['file']);
        return true;
    }
    public static function sendNotification($data = []) {

        $data['payout_uuid'] = '941d0120-60c7-11ed-abfa-a9b80b510cd8';
        $data['freelancer_id'] = 1;
        // event(new NotificationEvent($data));

        ProcessNotificationHelper::sendPaymentStatusNotificationToFreelancer($data);
    }

    public static function freelancerCSV($inputs) {

        $freelancers = self::payoutDetails($inputs);
        return $freelancers;
    }

}
