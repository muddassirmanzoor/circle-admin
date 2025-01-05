<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDue extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'payment_due';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'payment_due_uuid';
    public $timestamps = true;

    public function freelancer_transaction(){
        return $this->hasOne('\App\FreelancerTransaction', 'freelancer_transaction_uuid', 'freelancer_transaction_uuid');
    }

    public function freelancer(){
        return $this->hasOne('\App\Freelancer', 'freelancer_uuid', 'user_uuid');
    }

    protected function getUserAllPaymentDues($freelancer_uuid, $datetime){

        $dues = self::with('freelancer_transaction')->where('user_uuid', $freelancer_uuid)->whereDate('due_date', '<=', $datetime)->get();
        return ($dues) ? $dues->toArray() : [];
    }

    protected function getUserTotalEarnings($freelancer_uuid, $dueCheck=false, $currency = "SAR"){
        $amount_column = $currency == "Pound" ? 'pound_amount' : 'sar_amount';
        $total_earnings = self::where('user_uuid', $freelancer_uuid)->where('status', 0);
        if ($dueCheck){
            $total_earnings = $total_earnings->whereDate('due_date', '<=', date('Y-m-d'));
        }
        $total_earnings = $total_earnings->sum($amount_column);
        return round($total_earnings, 2);
    }

    protected function getUserPendingBalance($freelancer_uuid, $currency = "SAR"){
        $amount_column = strtolower($currency) == "pound" ? 'pound_amount' : 'sar_amount';
        $total_earnings = self::where('user_uuid', $freelancer_uuid)->where('status', 0)->whereDate('due_date', '>', date('Y-m-d'))->sum($amount_column);
        return $total_earnings;
    }
}
