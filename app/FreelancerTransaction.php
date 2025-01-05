<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class   FreelancerTransaction extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'freelancer_transactions';
    protected $uuidFieldName = 'freelancer_transaction_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['freelancer_transaction_uuid', 'transaction_id', 'freelancer_id', 'customer_id', 'content_id', 'transaction_type', 'transaction_user', 'transaction_date', 'status', 'comments', 'payment_brand', 'actual_amount', 'total_amount', 'commission_rate', 'exchange_rate', 'from_currency', 'to_currency', 'is_archive', 'created_at', 'updated_at'];

    /*
     * All model relations goes down here
     *
     */

    public function appointment()
    {
        return $this->hasOne('\App\Appointment', 'id', 'content_id');
    }

    public function customer()
    {
        return $this->hasOne('\App\Customer', 'id', 'customer_id');
    }

    public function freelancer()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'freelancer_id');
    }

    public function classBook()
    {
        return $this->hasOne('\App\ClassBooking', 'id', 'content_id');
    }

    public function subscription()
    {
        return $this->hasOne('\App\Subscription', 'id', 'content_id');
    }

    public function payment_due()
    {
        return $this->hasMany('\App\PaymentDue', 'freelancer_transaction_id', 'id');
    }

    protected static function saveTransaction($data)
    {
        return self::create($data);
    }

    protected static function getTransactionDetail($column, $value)
    {
        $query = self::where($column, '=', $value);
        $query->with('appointment.package', 'appointment.promo_code', 'freelancer', 'customer', 'payment_due');
        $query->with('classBook.schedule', 'classBook.promo_code', 'classBook.classObject', 'classBook.package');
        $query->with('subscription.subscription_setting');
        $result = $query->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function calculateEarnings($column, $value, $query_params = [])
    {
        $query = self::where($column, '=', $value);
        $query = $query->where('status', '=', 'confirmed');
        //        if (!empty($query_params['status'])) {
        //            $query = $query->where('status', '=', $query_params['status']);
        //        }
        $result = $query->sum('actual_amount');
        return $result;
    }

    protected static function updateParticularTransaction($column, $value, $data = [])
    {
        return self::where($column, '=', $value)->update($data);
    }

    // protected static function getParticularTransactions($column, $value, $search_params = [])
    // {
    //     $current_date = date('Y-m-d h:i:s');
    //     $query = self::where($column, '=', $value);
    //     $query->with('appointment', 'freelancer', 'customer', 'payment_due');
    //     $query->with('classBook.schedule', 'classBook.classObject');
    //     $query->with('subscription.subscription_setting');
    //     $query->where(function ($qry) use ($search_params, $current_date) {
    //         $qry->whereHas('appointment', function ($nestquery) use ($search_params, $current_date) {
    //             if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
    //                 $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, appointment_date, ?)< 1440', [$current_date]);
    //             }
    //             if (isset($search_params['type']) && ($search_params['type'] == "available")) {
    //                 $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, appointment_date, ?)>= 1440', [$current_date]);
    //             }
    //         });
    //         $qry->orWhereHas('classBook.schedule', function ($nestquery) use ($search_params, $current_date) {
    //             if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
    //                 $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, class_date, ?)< 1440', [$current_date]);
    //             }
    //             if (isset($search_params['type']) && ($search_params['type'] == "available")) {
    //                 $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, class_date, ?)>= 1440', [$current_date]);
    //             }
    //         });
    //         if ($search_params['login_user_type'] == 'freelancer') {
    //             $qry->orWhereHas('payment_due', function ($nestquery) use ($search_params, $current_date) {
    //                 if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
    //                     $nestquery->whereDate('due_date', '>', $current_date);
    //                 }
    //                 if (isset($search_params['type']) && ($search_params['type'] == "available")) {
    //                     $nestquery->whereDate('due_date', '<=', $current_date);
    //                 }
    //             });
    //         } else {
    //             $qry->orWhereHas('subscription');
    //         }
    //     });

    //     if (isset($search_params['type']) && ($search_params['type'] == "pending" || $search_params['type'] == "available")) {
    //         $query = $query->where('status', 'confirmed');
    //     }
    //     $query = $query->orderBy('created_at', 'DESC');

    //     $result = $query->get();

    //     return !empty($result) ? $result->toArray() : [];
    // }
    protected static function getParticularTransactions($column, $value, $search_params = [])
    {
        $current_date = date('Y-m-d h:i:s');
        $query = self::where($column, '=', $value);
        $query->with('appointment', 'freelancer', 'customer',);
        $query->with('classBook.schedule', 'classBook.classObject');
        $query->with('subscription.subscription_setting');
        $query->where(function ($qry) use ($search_params, $current_date) {
            $qry->whereHas('appointment', function ($nestquery) use ($search_params, $current_date) {
                if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
                    $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, appointment_date, ?)< 1440', [$current_date]);
                }
                if (isset($search_params['type']) && ($search_params['type'] == "available")) {
                    $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, appointment_date, ?)>= 1440', [$current_date]);
                }
            });
            $qry->orWhereHas('classBook.schedule', function ($nestquery) use ($search_params, $current_date) {
                if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
                    $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, class_date, ?)< 1440', [$current_date]);
                }
                if (isset($search_params['type']) && ($search_params['type'] == "available")) {
                    $nestquery->whereRaw('TIMESTAMPDIFF(MINUTE, class_date, ?)>= 1440', [$current_date]);
                }
            });
            if ($search_params['login_user_type'] == 'freelancer') {
                $qry = [];
                // $qry->orWhereHas('payment_due', function ($nestquery) use ($search_params, $current_date) {
                //     if (isset($search_params['type']) && ($search_params['type'] == "pending")) {
                //         $nestquery->whereDate('due_date', '>', $current_date);
                //     }
                //     if (isset($search_params['type']) && ($search_params['type'] == "available")) {
                //         $nestquery->whereDate('due_date', '<=', $current_date);
                //     }
                // });
            } else {
                $qry->orWhereHas('subscription');
            }
        });

        if (isset($search_params['type']) && ($search_params['type'] == "pending" || $search_params['type'] == "available")) {
            $query = $query->where('status', 'confirmed');
        }
        $query = $query->orderBy('created_at', 'DESC');

        $result = $query->get();

        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getAllTransactions($column, $value, $is_confirmed = false)
    {

        $query = self::where($column, '=', $value);
        $query->with('appointment', 'freelancer', 'customer', 'payment_due');
        $query->with('classBook.schedule', 'classBook.classObject');
        $query->with('subscription.subscription_setting');
        $query = $query->orderBy('created_at', 'DESC');
        if ($is_confirmed) {
            $query->where('status', 'confirmed');
        }

        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }
}
