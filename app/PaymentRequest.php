<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'payment_requests';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'payment_request_uuid';
    public $timestamps = true;



    public function freelancer()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'user_id');
    }

    protected function getPaymentRequests($status = "all")
    {

        $requests = self::with('freelancer.bank_detail');
        if ($status != "all"){
            $requests = $requests->where('is_processed', $status);
        }
        $requests = $requests->orderBy('id','DESC')->get();

        return ($requests) ? $requests->toArray() : [];
    }

    protected function getPaymentRequestDetail($uuid){
        $req = self::with('freelancer.bank_detail')->where('payment_request_uuid', $uuid)->first();
        return ($req) ? $req->toArray() : [];
    }

    protected function updatePaymentRequest($req_uuid, $data){
        $resp = self::where('payment_request_uuid', $req_uuid)->update($data);
        return $resp;
    }

    protected function getUserTotalWithdraw($freelancer_id){
        $total_withdraw = self::where('user_id', $freelancer_id)->where('is_processed', 2)->sum('requested_amount');
        return $total_withdraw;
    }

    protected function getPaymentRequestAmount($status, $freelancer_id) {
        $amount = self::where('user_id', $freelancer_id)->where('is_processed', $status)->sum('requested_amount');
        return $amount;
    }
}
