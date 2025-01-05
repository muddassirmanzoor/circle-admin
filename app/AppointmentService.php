<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'appointment_services';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'appointment_service_uuid';
    public $timestamps = true;
    protected $fillable = [
        'appointment_service_uuid',
        'appointment_uuid',
        'service_uuid',
        'is_archive'
    ];

    public function Service()
    {
        return $this->hasOne('\App\SubCategory', 'sub_category_uuid', 'service_uuid');
    }

    protected function saveAppointmentService($data) {
        $result = AppointmentService::create($data);
        return ($result) ? $result : [];
    }

    protected function updateAppointmentService($where, $data)
    {
        return self::where($where)->update($data);
    }

}
