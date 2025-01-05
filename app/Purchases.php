<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    protected $uuidFieldName = 'purchases_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_unique_id',
        'customer_id',
        'freelancer_id',
        'purchase_datetime',
        'account_title',
        'type',
        'purchased_by',
        'purchased_in_currency',
        'service_provider_currency',
        'conversion_rate',
        'appointment_id',
        'class_booking_id',
        'purchased_package_id',
        'subscription_id',
        'customer_card_id ',
        'circl_fee',
        'transaction_charges',
        'service_amount',
        'total_amount',
        'discount',
        'discount_type',
        'total_amount_percentage',
        'tax',
        'circl_fee_percentage',
        'is_refund',
        'status',
        'is_archive',
    ];

    public function subscription()
    {
        return $this->belongsTo('\App\Subscription', 'subscription_id', 'id');
    }

    public function purchasesTransition()
    {
        return $this->belongsTo('\App\PurchasesTransition', 'id', 'purchase_id');
    }

    public function appointment()
    {
        return $this->belongsTo('\App\Appointment', 'appointment_id', 'id');
    }

    public function wallet()
    {

        return $this->belongsTo('\App\Wallet', 'id', 'purchase_id');
    }

    public function customer()
    {
        return $this->belongsTo('\App\Customer', 'customer_id', 'id');
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class, 'freelancer_id', 'id');
    }

    public function AppointmentPackage()
    {
        return $this->belongsTo('\App\PurchasesPackage', 'purchased_package_id', 'id');
    }

    public function classbooking()
    {
        return $this->belongsTo('\App\ClassBooking', 'class_booking_id', 'id');
    }

    public function appointment_subscription()
    {
        return $this->belongsTo('\App\Subscription', 'subscription_id', 'id');
    }

    public static function createPurchase($params)
    {
        $result = Purchases::create($params);
        return ($result) ? $result->toArray() : null;
    }

    public static function getTransitionDetail($transitionId)
    {
        $result = Purchases::where('purchases_uuid', $transitionId)
            ->with([
                'appointment.promo_code',
                'classbooking.classObject',
                'classbooking.schedule',
                'customer',
                'freelancer',
                'AppointmentPackage.package',
                'AppointmentPackage.appointments',
                'AppointmentPackage.classBooking.classObject',
                'wallet',
                'purchasesTransition',
                'appointment_subscription.subscription_setting'
            ])
            ->first();
        return ($result) ? $result->toArray() : [];
    }

    public static function getAllTransition($col, $val)
    {
        $result = Purchases::where($col, $val)
            ->with(['appointment', 'AppointmentPackage.appointments', 'classbooking.schedule', 'classbooking.classObject', 'wallet', 'purchasesTransition', 'AppointmentPackage.classBooking.classObject'])
            ->orderBy('id', 'DESC')
            ->get();
        return ($result) ? $result->toArray() : [];
    }

    public static function getSumOfCol($col, $val, $key)
    {
        $result = Purchases::where($col, $val)
            ->whereIn('status', ['pending', 'confirmed'])
            //                ->whereHas('FreelancerEarning', function ($q) {
            //                    $q->where('freelancer_withdrawal_id', '!=', null);
            //                })
            ->sum($key);
        return ($result != 0) ? $result : 0;
    }

    public static function getTrasanctionByType($col, $val, $limit, $offset, $type)
    {
        $result = Purchases::where([$col => $val, 'status' => $type])
            //                ->whereHas('FreelancerEarning', function ($q) {
            //                    $q->where('freelancer_withdrawal_id', '!=', null);
            //                })
            ->with(['appointment', 'AppointmentPackage.appointments', 'classbooking.schedule', 'classbooking.classObject', 'wallet', 'purchasesTransition', 'AppointmentPackage.classBooking.classObject'])
            ->orderBy('id', 'DESC')
            ->get();
        return ($result) ? $result->toArray() : [];
    }

    public static function getPurchasesWithStatus($col, $val, $status) {
        $result = Purchases::where($col, $val)
                ->whereIn('status', [$status])
                ->get();
        return ($result) ? $result->toArray() : [];
    }
}
