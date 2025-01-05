<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helpers\CommonHelper;
use App\Helpers\CustomerHelper;
use App\Helpers\AppointmentHelper;
use App\Helpers\FreelancerDataHelper;
use App\Helpers\FreelancerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function getAllCustomers(Request $request)
    {
        try {
            $search = $request->q;
            if($search == ''){
                $title = "All";
                $customers = CustomerHelper::getAllCustomers();
                return view('customer.customers_listing', compact('customers', 'title'));
            }
            else
            {
                $customers = CustomerHelper::getSearchedCustomer($search);
                $response = array();
                foreach($customers as $customer){
                    $response[] = array("id"=>$customer->customer_uuid,"label"=>$customer->first_name);
                }
                echo json_encode($response);
                exit;
            }
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
        
    }

    public function getBlockedCustomers()
    {
        $title = "Blocked";
        $customers = CustomerHelper::getBlockedCustomers();
        return view('customer/customers_listing', compact('customers', 'title'));
    }

    public function getActiveCustomers()
    {
        $title = "Active";
        $customers = CustomerHelper::getActiveCustomers();
        return view('customer/customers_listing', compact('customers', 'title'));
    }

    public function getPendingCustomers()
    {
        $title = "Pending";
        $customers = CustomerHelper::getPendingCustomers();
        return view('customer/customers_listing', compact('customers', 'title'));
    }

    public function getDeletedCustomers()
    {
        $title = "Deleted";
        $customers = CustomerHelper::getDeletedCustomers();
        return view('customer/customers_listing', compact('customers', 'title'));
    }

    public function updateCustomerStatus(Request $request)
    {
        if($request->input(['is_active']) == 1){
            $active = 1;
            $archive = 0;
        }else{
            $active = 0;
            $archive = 0;
        }
        $update = array(
            'is_active' => $active,
            'is_archive' => $archive,
            'is_verified' => 1
        );
        Customer::updateCustomerdataById($update, $request->input(['customer_uuid']));
        return response()->json(['response' => 'success']);
    }

    public function deleteCustomer(Request $request)
    {
        Customer::updateCustomerdataById(array('is_archive' => 1), $request->input(['customer_uuid']));
        return response()->json(['response' => 'success']);
    }

    public function customerDetailPage($uuid)
    {
        $customer = CommonHelper::getModelInstance('App\Customer', 'customer_uuid', $uuid);
        $data['customer_uuid'] = $customer->customer_uuid;
        $data['customer_detail'] = CustomerHelper::getCustomerDetail($customer->customer_uuid);
        $data['customer_interests'] = CustomerHelper::getCustomerIntrusts($customer->id);
        $data['customer_appointments'] = AppointmentHelper::getCustomerAppointmentsData($customer->id); // customer appointments loaded through ajax call.
        $data['customer_classes'] = CustomerHelper::getcustomerClasses($customer->id);
        // $data['freelancer_locations'] = CustomerHelper::getCustomerlocations($customer->customer_uuid);
        $data['customer_subscriptions'] = CustomerHelper::getCustomerSubscriptions($customer->id);
        $all_freelancers = FreelancerHelper::getActiveFreelancers();
        $data['all_freelancers'] = FreelancerDataHelper::makeFreelancersSelectArr($all_freelancers);

        return view('customer.customer_detail_page', compact('data'));
    }

    public function updateCustomerProfileByAdmin(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return CustomerHelper::updateCustomerProfile($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function customerSchedules()
    {
        $title = "Schedule";
        $customers = CustomerHelper::getAllCustomers();
        return view('customer/customers_subscription', compact('customers', 'title'));
    }

    public function addNewCustomerForm()
    {
        return view('customer/add_new_customer');
    }

    public function createCustomerByAdmin(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return CustomerHelper::createCustomerByAdmin($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateCustomerPicture(Request $request)
    {
        try{
            $customer_id = $request->input('customer_uuid');
            $file = $request->file('customer_picture');
            return  CustomerHelper::updateCustomerPicture($customer_id, $file);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function saveCustomerSubscription(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return CustomerHelper::saveCustomerSubscription($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getCustomerSubscriptionDetail(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return CustomerHelper::getSubscriptionDetail($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

}
