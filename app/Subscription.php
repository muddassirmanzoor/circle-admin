<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'subscription_uuid';
    public $timestamps = true;
    protected $fillable = ['subscription_uuid', 'subscription_settings_id', 'subscriber_id', 'subscribed_id', 'subscription_date', 'transaction_id', 'card_registration_id', 'price', 'auto_renew', 'is_archive'];

    protected static function createSubscription($data)
    {
        $result = Subscription::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    public function SubscriptionSetting()
    {
        return $this->hasOne('\App\SubscriptionSetting', 'id', 'subscription_settings_id');
    }

    public function SubscriberDetail()
    {
        return $this->hasOne('\App\Customer', 'id', 'subscriber_id');
    }

    public function SubscribedDetail()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'subscribed_id');
    }

    public function customer()
    {
        return $this->hasOne('\App\Customer', 'id', 'subscriber_id');
    }

    public function purchaseSubscription()
    {
        return $this->hasOne('\App\Purchases', 'subscription_id', 'id');
    }

    protected static function checkSubscription($column, $value)
    {
        return Subscription::where($column, '=', $value)->first();
    }

    protected static function checkSubscriber($subscriber_id, $subscribed_id)
    {
        $result = Subscription::where('subscriber_id', '=', $subscriber_id)->where('subscribed_id', '=', $subscribed_id)->where('is_archive', '=', 0)->first();
        return !empty($result) ? true : false;
    }

    protected static function getFreelancerFollowers($column, $value)
    {
        $result = self::with('GetFollower')->where($column, '=', $value)->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getSubscribedIds($column, $value)
    {
        $result = self::where($column, '=', $value)->pluck("subscribed_id");
        return !empty($result) ? $result->toArray() : [];
    }

    public function subscription_setting()
    {
        return $this->hasOne('\App\SubscriptionSetting', 'id', 'subscription_settings_id');
    }

    protected  function getCustomerSubscriptions($customer_id)
    {
        $subscriptions = array();
        if (!empty($customer_id)) {
            $subscriptions = self::with('SubscriptionSetting', 'SubscribedDetail')
                ->where('subscriptions.subscriber_id', '=', $customer_id)->orderBy('subscriptions.id', 'desc')->get();
        }
        return !empty($subscriptions) ? $subscriptions->toArray() : [];
    }

    protected static function updateSubscription($where = [], $data = [])
    {
        return self::where($where)->update($data);
    }

    protected static function getSubscriptionDetail($subscription_id = '')
    {
        $subscriptions = array();
        if (!empty($subscription_id)) {
            $subscriptions = self::where('subscription_id', '=', $subscription_id)->first();
        }
        return !empty($subscriptions) ? $subscriptions : '';
    }

    protected static function getSubscribers($column, $value, $limit = null, $offset = null)
    {
        $query = Subscription::where($column, '=', $value);
        $query = $query->with('customer');
        $query = $query->with('SubscriptionSetting');
        $query = $query->with('purchaseSubscription');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $query = $query->orderBy('created_at', 'DESC');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getSubscribersCount($column, $value)
    {
        return Subscription::where($column, '=', $value)->where('is_archive', 0)->count();
    }

    public static function getFavouriteProfileIds($column, $value, $pluck_field)
    {
        $result = Subscription::where($column, '=', $value)->pluck($pluck_field);
        return !empty($result) ? $result->toArray() : [];
    }

    public static function getActiveSubscriptions()
    {
        $result = Subscription::where('is_archive', '=', 0)->where('auto_renew', 1)->with('subscription_setting')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public static function getActiveCancelledSubscriptions()
    {
        $result = Subscription::where('is_archive', '=', 0)->where('auto_renew', 0)->with('subscription_setting')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function cancelSubscription($col, $val, $data)
    {
        return Subscription::where($col, '=', $val)->update($data);
    }

    protected static function getSubscribersMonthlyCount($column, $value)
    {
        return Subscription::where($column, '=', $value)->whereMonth('subscription_date', \Carbon\Carbon::now()->month)->count();
    }

    protected static function getSubscribersYearlyCount($column, $value)
    {
        return Subscription::where($column, '=', $value)->whereMonth('subscription_date', \Carbon\Carbon::now()->year)->count();
    }
}
