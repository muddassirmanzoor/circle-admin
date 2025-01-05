<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSetting extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'subscription_settings';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'subscription_settings_uuid';
    public $timestamps = true;
    protected $fillable = ['subscription_settings_uuid', 'freelancer_id', 'price', 'currency', 'type', 'is_archive'];

    protected function getFreelancerSubs($where = '')
    {
        $all_subs = array();
        if (!empty($where)) {
            $all_subs = self::where($where)->orderBy('id', 'desc')->get();
        } else {
            $all_subs = self::orderBy('id', 'desc')->get();
        }
        return !empty($all_subs) ? $all_subs : '';
    }

    protected function createSubscription($data = []) {
        $result = SubscriptionSetting::create($data);
        return ($result) ? $result->toArray() : [];
    }

    protected function updateSubscription($where = [], $data = []) {
        $result = self::where($where)->update($data);
        return $result ? true : false;
    }

    protected function getSubscriptionDetail($subscription_uuid = '') {
        $subscriptions = array();
        if (!empty($subscription_uuid)) {
            $subscriptions = self::where('subscription_settings_uuid', '=', $subscription_uuid)->first();
        }
        return !empty($subscriptions) ? $subscriptions : '';
    }
}
