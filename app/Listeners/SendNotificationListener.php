<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Helpers\ProcessNotificationHelper;

class SendNotificationListener {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event) {
        \Log::info('=========in notification events========');
        \Log::info('========event data==============');
        \Log::info($event->data);
        \Log::info('========event inputs==============');
        // \Log::info($event->inputs);
        $sendNotification = true;
        $data = $event->data;
        $sendNotification = ProcessNotificationHelper::sendPaymentStatusNotificationToFreelancer($data);
        return $sendNotification;
    }

}
