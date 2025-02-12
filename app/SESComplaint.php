<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SESComplaint extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ses_complaints';
    protected $primarykey = 'id';
    protected $fillable = [
        'ses_complaint_uuid',
        'type',
        'email_address',
        'message_id',
        'feedback_id',
        'user_agent',
        'source_email_address',
        'source_arn',
        'source_ip',
        'mail_time',
        'sending_account_id',
        'is_archive'
    ];

    protected $uuidFieldName = 'ses_complaint_uuid';

    public static function getByEmail($email){
        return static::where('email_address', '=', $email)->first();
    }

    public static function checkIfNotSpam($email){
        return static::where('email_address', '=', $email)->where('type', '!=', 'not-spam')->exists();
    }

    public function getFreelancerCustomerLinkByEmail(){
        $customerUUID = Customer::where('email', '=', $this->email_address)->pluck('customer_uuid')->first();
        $freelancerUUID = Freelancer::where('email', '=', $this->email_address)->pluck('freelancer_uuid')->first();

        $data = [];
        if (!empty($customerUUID)):
            $data[] = '<a href="'.route('customerDetailPage', $customerUUID).'">Customer</a>';
        endif;
        if (!empty($freelancerUUID)):
            $data[] = '<a href="'.route('freelancerDetailPage', $freelancerUUID).'">Freelancer</a>';
        endif;
        return $data;
    }

}
