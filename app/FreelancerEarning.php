<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreelancerEarning extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'freelancer_earnings';
    protected $uuidFieldName = 'freelancer_earnings_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['freelancer_earnings_uuid', 'freelancer_id', 'earned_amount', 'purchase_id', 'subscription_id', 'class_booking_id', 'appointment_id', 'amount_due_on', 'currency', 'freelancer_withdrawal_id', 'is_archive', 'created_at', 'updated_at'];

    /*
     * All model relations goes down here
     *
     */
    public function appointment()
    {
        return $this->hasOne('\App\Appointment', 'id', 'appointment_id');
    }

    public function freelancer()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'freelancer_id');
    }

    public function classBook()
    {
        return $this->hasOne('\App\ClassBooking', 'id', 'class_booking_id');
    }

    public function subscription()
    {
        return $this->hasOne('\App\Subscription', 'id', 'subscription_id');
    }

    public function purchases()
    {
        return $this->belongsTo('\App\Purchases', 'purchase_id', 'id');
    }
    public function funds_trasnfer()
    {
        return $this->belongsTo('\App\FundsTransfer', 'funds_transfers_id', 'id');
    }
    public static function getSumOfCol($col, $val, $key) {
        $result = self::where($col, $val)
                ->where('freelancer_withdrawal_id', '=', null)
                ->sum($key);
        return ($result != 0) ? $result : 0;
    }

    public static function getEarningWRTTime($col, $val, $key) {
        $date = strtotime(date('Y-m-d H:i:s'));
        $query = self::where($col, $val)->where('freelancer_withdrawal_id', '=', null)
        ->where('is_archive', '=', 0);
        if ($key == 'pending') {
            $query = $query->where('amount_due_on', '>', $date);
        }
        if ($key == 'available') {
            $query = $query->where('amount_due_on', '<', $date);
        }
        $query->with('purchases.customer','freelancer.primaryLocation.location');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public static function updateFreelancerEarningWithFundsTransfer($funds_transfer_id) {
        $date = strtotime(date('Y-m-d H:i:s'));
        return self::where('is_archive',0)
        ->whereNull('freelancer_withdrawal_id')
        ->whereNull('funds_transfers_id')
        ->where('amount_due_on', '>', $date)
        ->update(['funds_transfers_id' => $funds_transfer_id,'transfer_status' => 'in_progress']);
    }

    public static function getFreelancerEarnings($col,$val) {
        return self::where('is_archive',0)
        ->with('freelancer.bank_detail')
        ->where($col,$val)
        ->orderBy('id','asc')
        ->get();
    }
}
