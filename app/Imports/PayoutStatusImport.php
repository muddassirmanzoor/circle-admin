<?php

namespace App\Imports;

use App\Events\NotificationEvent;
use App\FreelancerEarning;
use App\FreelancerWithdrawal;
use App\FundsTransfer;
use App\Helpers\EmailSendingHelper;
use App\Helpers\ProcessNotificationHelper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayoutStatusImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row)
        {
            if(isset($row['status']) && !empty($row['status']))
            {
                // if($row['status'] == 'failed')
                // {

                    $freelancer_withdrawal = FreelancerWithdrawal::with('freelancer_earning.funds_trasnfer')->where('fp_payment_reference',$row['payment_reference'])->first();
                    if(isset($freelancer_withdrawal['freelancer_earning']['funds_trasnfer']))
                    {
                        $funds_transfer_uuid = $freelancer_withdrawal['freelancer_earning']['funds_trasnfer']['funds_transfer_uuid'];
                        FundsTransfer::where('funds_transfer_uuid',$funds_transfer_uuid)->update(['status' => 'completed']);
                        $notification_data['payout_uuid'] = $funds_transfer_uuid;
                        $notification_data['freelancer_id'] = $freelancer_withdrawal['freelancer_id'];
                        $email_data['email'] = $row['email_id'];
                        $email_data['message'] = 'Your payout has been failed due to this reason ('.$row['failed_reason'].')';
                        // EmailSendingHelper::sendPayoutFailedEmailToFreelancer($email_data);
                        ProcessNotificationHelper::sendPaymentStatusNotificationToFreelancer($notification_data);
                        event(new NotificationEvent($notification_data));
                    }
                // }
                    FreelancerWithdrawal::where('fp_payment_reference',$row['payment_reference'])->update(['transfer_status'=>$row['status'],'billing_reason'=>$row['failed_reason'],'updated_at'=>null]);
                    FreelancerEarning::where('freelancer_withdrawal_id',$freelancer_withdrawal['id'])->update(['transfer_status' => $row['status']]);
                    
            }

            // EmailSendingHelper::sendPayoutFailedEmailToAdmin($email_data);
        }
        return true;
    }
}
