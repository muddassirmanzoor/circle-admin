<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalkinCustomer extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'walkin_customers';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'walkin_customer_uuid';
    public $timestamps = true;
    protected $fillable = [ 'walkin_customer_uuid', 'freelancer_id', 'first_name', 'last_name', 'profile_image', 'is_archive' ];

    public function AppointmentClient() {
        return $this->hasMany('\App\Appointment', 'customer_id', 'id');
    }

    protected function saveCustomer($data = []) {
        $save = WalkinCustomer::create($data);
        return !empty($save) ? $save->toArray() : [];
    }

    protected function searchClientWalkinCustomers($freelancer_id = null, $limit = 100) {
        $query = WalkinCustomer::where('is_archive', '=', 0)->where('freelancer_id', '=', $freelancer_id);
        $query = $query->with('AppointmentClient');
        $query = $query->limit($limit);
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function checkCustomer($column, $value) {
        return WalkinCustomer::where($column, '=', $value)->exists();
    }

    protected function getCustomer($column, $value) {
        $result = WalkinCustomer::where($column, '=', $value)->first();
        return !empty($result) ? $result->toArray() : [];
    }

}
