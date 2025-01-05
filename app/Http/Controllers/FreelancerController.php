<?php

namespace App\Http\Controllers;

use App\Helpers\AppointmentHelper;
use App\Helpers\BlocktimeHelper;
use App\Helpers\CommonDataHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FreelancerHelper;
use App\Helpers\MessageHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\ScheduleHelper;
use App\Helpers\ProfessionHelper;
use App\Helpers\SubscriberHelper;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FreelancerController extends Controller
{
    public function getAllFreelancers(Request $request)
    {
        try {
            $search = $request->q;
            if ($search == '') {
                $title = "All";
                $freelancers = FreelancerHelper::getAllFreelancers();
                return view('freelancer.freelancers_listing', compact('freelancers', 'title'));
            } else {
                $freelancers = FreelancerHelper::getSearchedFreelancers($search);
                $response = array();
                foreach ($freelancers as $employee) {
                    $response[] = array("id" => $employee->freelancer_uuid, "label" => $employee->first_name);
                }
                echo json_encode($response);
                exit;
            }
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getAllFreelancersWithIban(Request $request)
    {
        try {
            $freelancers = FreelancerHelper::getAllFreelancersWithIbanInfo();
            return view('freelancer.freelancers_iban_list', compact('freelancers'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function editFreelancerIbanInfo($id)
    {
        try {
            $freelancer_detail = FreelancerHelper::getFreelancerDetail($id);
            return view('freelancer.edit_freelancer_iban', compact('freelancer_detail'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateFreelancerBankInfo(Request $request)
    {
        try {
            $inputs = $request->except('_token');
            return FreelancerHelper::updateFreelancerBankDetails($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getNotActiveFreelancers()
    {
        try {
            $title = "Not Active";
            $freelancers = FreelancerHelper::getNotActiveFreelancers();
            return view('freelancer.freelancers_listing', compact('freelancers', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getActiveFreelancers()
    {
        try {
            $title = "Active";
            $freelancers = FreelancerHelper::getActiveFreelancers();
            return view('freelancer.freelancers_listing', compact('freelancers', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getNotVerfiedFreelancers()
    {
        try {
            $title = "Not Verified";
            $where = array(
                'is_verified' => 0,
                'is_archive' => 0
            );
            $freelancers = FreelancerHelper::getNotVerfiedFreelancers($where);
            return view('freelancer.freelancers_listing', compact('freelancers', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getDeletedFreelancers()
    {
        $title = "Deleted";
        $freelancers = FreelancerHelper::getDeletedFreelancers();
        return view('freelancer.freelancers_listing', compact('freelancers', 'title'));
    }

    public function updateFreelancerStatus(Request $request)
    {
        try {
            $inputs = $request->all();
            return $freelancers = FreelancerHelper::updateFreelancerStatus($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function deleteFreelancer(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            return FreelancerHelper::deleteFreelancer($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function freelancerDetailPage($uuid)
    {
        // old code
        // $withdraw_info = PaymentHelper::getFreelancerWithdrawDetails($inputs);
        // $data['freelancer_payment_req'] = FreelancerHelper::getFreelancerPaymentRequests($freelancer->id);
        // $data['available_balance'] =  $withdraw_info['available_withdraw'];
        // $data['pending_balance'] =  $withdraw_info['pending_withdraw'];
        // $data['all_transactions'] =  FreelancerHelper::getAllTransactions($freelancer->id);
        // old code
        try {
            $freelancer                             = CommonHelper::getModelInstance('App\Freelancer', 'freelancer_uuid', $uuid);
            $data['freelancer_uuid']                = $freelancer->freelancer_uuid;
            $data['freelancer_stats']               = AppointmentHelper::getFreelancerStats($freelancer->id);
            $data['freelancer_detail']              = FreelancerHelper::getFreelancerDetail($freelancer->freelancer_uuid);
            $data['freelancer_subcategories']       = FreelancerHelper::getFreelancerSubcategories($freelancer->id);
            $data['freelancer_locations']           = FreelancerHelper::getFreelancerlocations($freelancer->id);
            $data['freelancer_subsciptions']        = FreelancerHelper::getFreelancerSubscriptions($freelancer->id);
            $data['freelancer_classes']             = FreelancerHelper::getFreelancerClassess($freelancer->id);
            $data['freelancer_packages']            = FreelancerHelper::getFreelancerPackages($freelancer->id);
            $data['freelancer_promocodes']          = FreelancerHelper::getFreelancerPromoCodes($freelancer->id);
            $data['all_professions']                = ProfessionHelper::getAllProfessions();
            $data['freelancer_schedule']            = ScheduleHelper::getFreelancerSchedules($freelancer->id);
            $data['freelancer_subcribers']          = SubscriberHelper::getSubscribers($freelancer->id);
            $data['freelancer_appointments']        = AppointmentHelper::getFreelancerAppointments($freelancer->id);;
            $data['freelancer_monthly_subcribers']  = SubscriberHelper::getSubscribersMonthlyCount($freelancer->id);
            $data['freelancer_yearly_subcribers']   = SubscriberHelper::getSubscribersYearlyCount($freelancer->id);
            $data['freelancer_earnings']            = AppointmentHelper::getFreelancerEarnings($freelancer->id);
            $data['freelancer_monthly_earnings']    = AppointmentHelper::getFreelancerMonthlyEarnings($freelancer->id);
            $data['freelancer_yearly_earnings']     = AppointmentHelper::getFreelancerYearlyEarnings($freelancer->id);
            $data['freelancer_clients']             = FreelancerHelper::getFreelancerClients($freelancer->id);

            // freelancer detail information
            $inputs['freelancer']                   = $data['freelancer_detail'];

            // api call prepare params
            $data['transaction_api_params']         = ['login_user_type' => 'freelancer', 'logged_in_uuid' => $freelancer->freelancer_uuid, 'local_timezone' => 'Asia/Karachi'];
            $data['available_api_params']         = ['login_user_type' => 'freelancer', 'type' => 'available', 'logged_in_uuid' => $freelancer->freelancer_uuid, 'local_timezone' => 'Asia/Karachi'];
            $data['pending_api_params']         = ['login_user_type' => 'freelancer', 'type' => 'pending', 'logged_in_uuid' => $freelancer->freelancer_uuid, 'local_timezone' => 'Asia/Karachi'];

            $data['available_balance']              =  FreelancerHelper::getFreelancerBalances($freelancer, 'available');
            $data['pending_balance']                =  FreelancerHelper::getFreelancerBalances($freelancer, 'pending');
            $data['transfer_balance']               =  FreelancerHelper::getFreelancerBalances($freelancer, 'transfer');
            $data['all_transactions']               =  FreelancerHelper::getAllTransactionsAPI($data['transaction_api_params']);
            $data['pending_transactions']           =  FreelancerHelper::getAllTransactionsAPI($data['pending_api_params']);
            $data['availble_transactions']           =  FreelancerHelper::getAllTransactionsAPI($data['available_api_params']);
//            $data['availble_transactions']          =  FreelancerHelper::getAvailableTransactions($freelancer->id);
            $data['withdraw_history']               =  FreelancerHelper::getFreelancerWithdrawHiostory($freelancer->id);
            return view('freelancer.freelancer_detail_page', compact('data'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function freelancerClasses($id)
    {
        try {

            $data = FreelancerHelper::getFreelancerClass($id);
            if (empty($data)) :
                abort(404);
            endif;
            return view('freelancer.classess.show', compact('data'));
        } catch (NotFoundHttpException $exception) {
            abort(404);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function freelancerPackages($id)
    {
        try {

            $data = Package::getSinglePackage('package_uuid', $id);
            //            dd($data);

            //            $data = FreelancerHelper::getFreelancerPackage($id);
            if (empty($data)) :
                abort(404);
            endif;
            return view('freelancer.packages/show', compact('data'));
        } catch (NotFoundHttpException $exception) {
            abort(404);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateFreelancerProfileByAdmin(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return FreelancerHelper::updateFreelancerProfile($inputs);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function appointmentEditForm($id)
    {
        try {
            $inputs = [];
            $inputs['appointment_uuid'] = $id;
            return FreelancerHelper::getAppointmentDetail($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function freelancerAppointmentUpdate(Request $request)
    {
        try {
            $inputs = $request->except('_token');
            return FreelancerHelper::freelancerAppointmentUpdate($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function addNewFreelancerForm()
    {
        $all_professions = ProfessionHelper::getAllProfessions();
        return view('freelancer.add_new_freelancer', compact('all_professions'));
    }

    public function createFreelancerByAdmin(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return FreelancerHelper::createFreelancerByAdmin($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getFreelancerCalendarData(Request $request)
    {
        try {
            $freelancer_uuid = $request->input('freelancer_uuid');
            $customer_appointments = FreelancerHelper::getFreelancerCalendarData($freelancer_uuid);
            return CommonHelper::jsonSuccessResponse(MessageHelper::returnEnglishSuccessMessage()['successful_request'], $customer_appointments);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateFreelancerPicture(Request $request)
    {
        try {
            $freelancer_uuid = $request->input('freelancer_uuid');
            $inputs = $request->input();
            $file = $request->file('freelancer_picture');
            return  FreelancerHelper::updateFreelancerPicture($freelancer_uuid, $file, $inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function saveFreelancerSubscription(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return FreelancerHelper::saveFreelancerSubscription($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getSubscriptionDetail(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return FreelancerHelper::getSubscriptionDetail($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getFreelancersSubscriptions(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $freelancer_subsciptions = FreelancerHelper::getFreelancerSubscriptions($inputs['freelancer_uuid']);
            if (!$freelancer_subsciptions) {
                DB::rollBack();
                return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['empty_error']);
            }
            DB::commit();
            return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request'], $freelancer_subsciptions);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateLocationByAdmin(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return FreelancerHelper::updateLocationByAdmin($inputs);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getTransactionDetail($tr_id, $due_id = "")
    {
        try {
            DB::beginTransaction();
            $data = FreelancerHelper::getTransactionDetail($tr_id, $due_id);
            return view('freelancer.transaction_detail', compact('data'));
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }
}
