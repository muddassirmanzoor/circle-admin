<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use Notifiable;

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'customer_uuid';

    public $timestamps = true;

    protected $fillable = ['customer_uuid', 'user_id', 'type', 'first_name', 'last_name', 'title', 'email', 'phone_number', 'password', 'profile_image', 'cover_image', 'country_name', 'country_code', 'dob', 'gender', 'facebook_id', 'google_id', 'apple_id', 'is_verified', 'is_active', 'is_archive'];

    public function AppointmentClient()
    {
        return $this->hasMany('\App\Appointment', 'customer_id', 'id');
    }

    protected  function getAllCustomers($where = [])
    {
        $all_customers = array();
        if (!empty($where)) {
            $all_customers = self::where($where)->orderBy('id', 'desc')->get();
        } else {
            $all_customers = self::orderBy('id', 'desc')->get();
        }
        return !empty($all_customers) ? $all_customers : [];
    }


    protected  function updateCustomerProfile($where = [], $data = [])
    {
        return self::where($where)->update($data);
    }


    protected  function updateCustomerdataById($data, $id)
    {
        self::where('customer_uuid', '=', $id)->update($data);
    }

    protected  function getCustomerDetail($customer_uuid = '')
    {
        $customers = array();
        if (!empty($customer_uuid)) {
            $customers = self::where('customer_uuid', '=', $customer_uuid)->first();
        }
        return !empty($customers) ? $customers : '';
    }

    protected function createCustomer($data = [])
    {
        $result = self::create($data);
        return ($result) ? $result->toArray() : [];
    }

    protected function getNewCurrentMonthCustomer()
    {
        $all_customers = Customer::where(['is_archive' => 0, 'is_verified' => 1])->whereMonth('created_at', date('m'))->get();
        return !empty($all_customers) ? $all_customers : [];
    }

    protected function searchClientCustomers($ids = [], $limit = 100)
    {
        $query = Customer::where('is_archive', '=', 0)->whereIn('id', $ids);
        $query = $query->with('AppointmentClient');
        $query = $query->limit($limit);
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }
}
