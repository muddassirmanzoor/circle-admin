<?php

namespace App\Helpers;

use App\Subscription;
use Illuminate\Support\Facades\Validator;

Class SubscriberHelper {

    public static function getSubscribers($freelancerId) {
        $subscriber_data = Subscription::getSubscribers('subscribed_id', $freelancerId);
        $response['subscriber_count'] = Subscription::getSubscribersCount('subscribed_id', $freelancerId);
        $response['subscribers'] = SubscriberResponseHelper::makeSubscriberResponse($subscriber_data);
        return $response;
    }

    public static function getSubscribersMonthlyCount($freelancerId) {
        return Subscription::getSubscribersMonthlyCount('subscribed_id', $freelancerId);
    }

    public static function getSubscribersYearlyCount($freelancerId) {
        return Subscription::getSubscribersYearlyCount('subscribed_id', $freelancerId);
    }

}

?>
