<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'session_uuid';
    public $timestamps = true;
    protected $fillable = [
        'session_uuid',
        'freelancer_uuid',
        'customer_uuid',
        'customer_name',
        'session_date',
        'from_time',
        'to_time',
        'location',
        'latitude',
        'longitude',
        'price',
        'status',
        'notes',
        'is_archive'
    ];


    public function SessionServices()
    {
        return $this->hasMany('\App\SessionService', 'session_uuid', 'session_uuid');
    }

    protected  function getFreelancerSessions($freelancer_uuid)
    {
        $sessions = array();
        if(!empty($freelancer_uuid)){
            $sessions = self::with('SessionServices.Service')
                ->where('sessions.freelancer_uuid', '=', $freelancer_uuid)->orderBy('sessions.id', 'desc')->get();
        }
        return !empty($sessions) ? $sessions->toArray() : [];
    }

    protected  function getCustomerSessions($customer_uuid)
    {
        $sessions = array();
        if(!empty($customer_uuid)){
            $sessions = self::with('SessionServices.Service')
                ->where('sessions.customer_uuid', '=', $customer_uuid)->orderBy('sessions.id', 'desc')->get();
        }
        return !empty($sessions) ? $sessions->toArray() : [];
    }

}
