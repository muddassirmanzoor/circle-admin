<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreelancerWithdrawal extends Model
{
    protected $table = 'freelancer_withdrawal';

    protected $primaryKey = 'id';
    protected $uuidFieldName = 'freelancer_withdrawal_uuid';
    public $timestamps = true;

    protected $fillable = ['freelancer_withdrawal_uuid', 'freelancer_id', 'reciept_id', 'reciept_url', 'receipt_date', 'invoice_id', 'amount', 'receipt_date', 'currency',
        'transaction_charges', 'account_title', 'account_name', 'account_number', 'iban_account_number', 'last_withdraw_date', 'schedule_status', 'is_archived','fp_account_title',
        'fp_account_number','fp_iban_account_number','fp_currency','fp_currency_code','value_date','fp_ordering_party_name','fp_ordering_party_address_1','fp_ordering_party_address_2','fp_ordering_party_address_3','fp_payment_reference','transfer_status','payment_reference_no','bank_country','beneficiary_address_1','beneficiary_address_2','beneficiary_address_3'
    ];

    protected function getLatesFreelancerWithdrawalRequest($freelancer_id)
    {
        $resp = $this->where('freelancer_id', $freelancer_id)->first();
        return !empty($resp) ? $resp->toArray() : [];
    }

    public function withdrawalEarnings()
    {
        return $this->hasMany('App\FreelancerEarning', 'freelancer_withdrawal_id', 'id');
    }
    public function freelancer_earning()
    {
        return $this->hasOne('App\FreelancerEarning', 'freelancer_withdrawal_id', 'id');
    }
    public function withdrawalFreelancer()
    {
        return $this->hasOne('App\Freelancer', 'id', 'freelancer_id');
    }
}
