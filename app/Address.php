<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'address_uuid';
    public $timestamps = true;
    protected $fillable = [
        'address_uuid',
        'customer_uuid',
        'freelancer_uuid',
        'appointment_uuid',
        'location_uuid',
        'is_archive'
    ];

    public function AddressCustomer()
    {
        return $this->hasOne('\App\Freelancer', 'freelancer_uuid', 'customer_uuid');
    }

    public function AddressFreelancer()
    {
        return $this->hasOne('\App\Freelancer', 'freelancer_uuid', 'freelancer_uuid');
    }

    public function AddressAppointment()
    {
        return $this->hasOne('\App\Appointment', 'appointment_uuid', 'appointment_uuid');
    }

    public function AddressLocations()
    {
        return $this->hasOne('\App\Location', 'location_uuid', 'location_uuid');
    }

    protected function getFreelancerAddressess($freelancer_uuid)
    {
        $locations = array();
        if(!empty($freelancer_uuid)){
            $locations = self::with('AddressLocations')
                ->where('addresses.freelancer_uuid', '=', $freelancer_uuid)->orderBy('addresses.id', 'desc')->get();
        }
        return !empty($locations) ? $locations->toArray() : [];
    }
}
