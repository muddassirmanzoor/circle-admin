<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedTime extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'blocked_timings';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'blocked_time_uuid';
    public $timestamps = true;
    protected $fillable = [
        'blocked_time_uuid',
        'freelancer_uuid',
        'start_date',
        'end_date',
        'from_time',
        'to_time',
        'notes',
        'is_archive'
    ];

    protected function saveSchedule($data) {
        $result = BlockedTime::insert($data);
        return ($result) ? $result : [];
    }

    protected function getFreelancerTimings($freelancer_uuid)
    {
        $bTimings = array();
        if(!empty($freelancer_uuid)){
            $bTimings = self::where('blocked_timings.freelancer_uuid', '=', $freelancer_uuid)->orderBy('blocked_timings.id', 'desc')->get();
        }
        return !empty($bTimings) ? $bTimings->toArray() : [];
    }

    protected function updateAppointment($where, $data)
    {
        return self::where($where)->update($data);
    }

}
