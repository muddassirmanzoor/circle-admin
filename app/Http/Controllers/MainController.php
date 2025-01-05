<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Freelancer;
use App\Helpers\AdminCommonHelper;
use App\Helpers\AppointmentHelper;
use App\Helpers\CustomerHelper;
use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;
use App\Helpers\FreelancerHelper;
use App\Helpers\PostHelper;
use App\Helpers\MainHelper;
use App\Helpers\CommonHelper;

class MainController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | MainController Class
      |--------------------------------------------------------------------------
      |
      | This class will handle all main functions, which are not related to admin.
      |
     */

    /**
     * Method for admin dashboard.
     *
     * @param  'empty'
     * @return view data.
     */
    public function adminDashboard(Request $request) {
        try {
            $data['active_freelancer_count'] = count(FreelancerHelper::getActiveFreelancers());
            $data['not_active_freelancer_count'] = count(FreelancerHelper::getNotActiveFreelancers());
            $data['blocked_freelancer_count'] = count(FreelancerHelper::getBlockedFreelancers());
            $data['deleted_freelancer_count'] = count(FreelancerHelper::getDeletedFreelancers());
            $data['active_customer_count'] = count(CustomerHelper::getActiveCustomers());
            $data['pending_customer_count'] = count(CustomerHelper::getPendingCustomers());
            $data['blocked_customer_count'] = count(CustomerHelper::getBlockedCustomers());
            $data['deleted_customer_count'] = count(CustomerHelper::getDeletedCustomers());
            $data['pending_appointment_count'] = count(AppointmentHelper::getAllAppointments('pending'));
            $data['confirmed_appointment_count'] = count(AppointmentHelper::getAllAppointments('confirmed'));
            $data['completed_appointment_count'] = count(AppointmentHelper::getAllAppointments('completed'));
            $data['cancelled_appointment_count'] = count(AppointmentHelper::getAllAppointments('cancelled'));
            $data['reported_post_count'] = count(PostHelper::getReportedPost());
            $data['blocked_post_count'] = count(PostHelper::getBlockedPosts());

            return view('dashboard', compact('data'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    public function getAppEarning() {
        try {
            $profit = MainHelper::getAppEarning();
            return view('app_profit', compact('profit'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getAllMessageCodes(Request $request) {
        try {
            $codes = MainHelper::getAllMessageCodes();
            return view('list_message_codes', compact('codes'));
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

}
