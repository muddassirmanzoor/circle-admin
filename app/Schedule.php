<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'schedule_uuid';
    public $timestamps = true;
    protected $fillable = ['schedule_uuid', 'freelancer_id', 'day', 'from_time', 'to_time', 'is_archive'];

    protected function saveSchedule($data)
    {
        $result = Schedule::insert($data);
        return ($result) ? $result : [];
    }

    protected  function getFreelancerWeeklyTimings($freelancer_id)
    {
        $schedule = array();
        if (!empty($freelancer_id)) {
            $schedule = self::where('schedules.freelancer_id', '=', $freelancer_id)->orderBy('schedules.id', 'desc')->get();
        }
        return !empty($schedule) ? $schedule->toArray() : [];
    }

    protected function updateSchedule($where, $data)
    {
        return self::where($where)->update($data);
    }
}
