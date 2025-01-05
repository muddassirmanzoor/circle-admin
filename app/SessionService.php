<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionService extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'session_services';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'session_service_uuid';
    public $timestamps = true;
    protected $fillable = [
        'session_service_uuid',
        'session_uuid',
        'service_uuid',
        'is_archive'
    ];

    public function Service()
    {
        return $this->hasOne('\App\SubCategory', 'sub_category_uuid', 'service_uuid');
    }

    protected function saveSessionService($data) {
        $result = SessionService::insert($data);
        return ($result) ? $result : [];
    }

}
