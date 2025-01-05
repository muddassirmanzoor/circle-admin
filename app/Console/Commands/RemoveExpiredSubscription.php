<?php

namespace App\Console\Commands;

use App\Subscription;

class RemoveExpiredSubscription extends BaseCommand {

    /**
     * @author ILSA Interactive
     * @var string
     */
    protected $signature = 'subscription:remove';

    /**
     * The console command name To Display.
     *
     * @var string
     */
    protected $commandName = 'Subscriptions Remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This job automatically archives a customer subscription if the existing subscription period is over';

    /**
     * File Log Channel
     *
     * @var string
     */
    protected $logChannel = 'cron_subscription_remove';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {

        parent::__construct();
    }

    /**

     * Execute the console command.

     *

     * @return mixed

     */
    public function handle() {
        $this->log('Initiating');
        $process_subscription = self::automateProcess();

        if (!$process_subscription['success']) {
            $this->log('Process failed with exception', [
                'message' => $process_subscription['message']
            ], 'error');
            $this->InsertDBLog(0);
        }
        else{
            $this->log($process_subscription['message']);
            $this->InsertDBLog();
        }
    }

    public function automateProcess() {
        try {
            $subscriptions = Subscription::getActiveCancelledSubscriptions();
            $toArchive = [];
            if (!empty($subscriptions)) {
                foreach ($subscriptions as $key => $single_subscription) {
                    if (empty($single_subscription['subscription_setting'])):
                        $toArchive[] = $single_subscription['subscription_uuid'];
                        continue;
                    endif;
                    $check_date = null;
                    if ($single_subscription['subscription_setting']['type'] == 'monthly') {
                        $check_date = date('Y-m-d', strtotime('+1 month', strtotime($single_subscription['subscription_date'])));
                    } elseif ($single_subscription['subscription_setting']['type'] == 'quarterly') {
                        $check_date = date('Y-m-d', strtotime('+3 months', strtotime($single_subscription['subscription_date'])));
                    } elseif ($single_subscription['subscription_setting']['type'] == 'annual') {
                        $check_date = date('Y-m-d', strtotime('+1 year', strtotime($single_subscription['subscription_date'])));
                    }
                    $current_date = date('Y-m-d');
                    if (!empty($check_date) && $current_date >= $check_date) { //if current or previous date expired subscription
                        $toArchive[] = $single_subscription['subscription_uuid'];
                    }
                }
                if (!empty($toArchive)):
                    $this->log('Removing Subscriptions.', [
                        'subscriptions' => $toArchive
                    ]);
                endif;
                if (!$this->cancelSubscriptions($toArchive)):
                    throw new \Exception('Unable to cancel subscriptions');
                endif;
            }
            if (empty($toArchive)):
                $this->log('No active cancelled subscription found.');
            else:
                $this->log('Removed Subscriptions.', [
                    'subscriptions' => $toArchive
                ]);
            endif;
            return ['success' => true, 'message' => 'Archive subscriptions job successfully executed'];
        } catch (\Illuminate\Database\QueryException $ex) {
            return ['success' => false, 'message' => $ex->getMessage()];
        } catch (\Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    protected function cancelSubscriptions($uuids){
        if (empty($uuids)):
            return true;
        endif;
        return Subscription::whereIn('subscription_uuid', $uuids)->update([
            'is_archive' => 1
        ]);
    }

}
