<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class FundsTransfer extends Model
{
    //
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;
    protected $uuidFieldName = 'funds_transfer_uuid';

    protected $guarded = ['id'];

    protected $fillable =  [
    'reference_no',
    'batch_no',
    'connect_id',
    'file_customer_id',
    'file_authorization_type',
    'total_number_of_payments',
    'status',
    'is_transfered',
];

public function freelancer_earnings()
{
    return $this->hasMany('App\FreelancerEarning','funds_transfers_id','id');
}
}
