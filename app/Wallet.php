<?php

namespace App;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wallet extends Model {

    protected $table = 'wallet';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'wallet_uuid';
    public $timestamps = true;
    protected $fillable = [
        'customer_id',
        'amount',
        'purchase_id',
        'type',
        'is_refunded',
        'customer_card_id',
        'checkout_transaction_reference',
    ];



    public static function getWalletTotalAmount($inputs){
      $total = 0.00;

      $customerId = CommonHelper::getCutomerIdByUuid($inputs['customer_uuid']);

      $records = Wallet::select(DB::raw('sum(amount) as total'), 'type')
          ->where('is_archive',0)
          ->where('customer_id',$customerId)
          ->groupBy('type')
          ->get()->toArray();

      if(isset($records[0]) && $records[0]['type'] == 'credit'){
          $creditAmount = $records[0]['total'];
          $debitAmount = (isset($records[1]))?$records[1]['total']:0.00;
          $final = $creditAmount - $debitAmount;
          $total = ($final > 0)?$final:0.00;
      }else{
          $total = 0.00;
      }

     return (double) $total;
    }



}
