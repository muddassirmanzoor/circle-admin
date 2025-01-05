<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'class_schedules';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'class_schedule_uuid';
    public $timestamps = true;
    protected $fillable = [
        'class_schedule_uuid',
        'class_uuid',
        'date',
        'from_time',
        'to_time',
        'is_archive'
    ];
    public function classes() {
        return $this->hasOne('\App\Classes', 'class_uuid', 'class_uuid');
    }

    public function classBookings() {
        return $this->hasMany('\App\ClassBooking', 'class_schedule_uuid', 'class_schedule_uuid');
    }

    public function getClassTimeAttribute() {
        return $this->class_date . " " . $this->from_time;
    }

//    protected function saveClassSchedule($data) {
//        return ClassSchedule::insert($data);
//    }

    protected function updateClassSchedule($column, $value, $data) {
        return ClassSchedule::where($column, '=', $value)->update($data);
    }

    protected function getClassSchedule($column, $value) {
        $result = ClassSchedule::where($column, '=', $value)->with('classBookings.transaction', 'classBookings.schedule')->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClassScheduleBasedOnUser($inputs) {
        $result = ClassSchedule::where('class_schedule_uuid', '=', $inputs['class_schedule_uuid'])->with(['classBookings' => function($q) use($inputs){
            if ($inputs['login_user_type'] == "customer"){
                $q->where('customer_uuid', $inputs['logged_in_uuid']);
            }
            $q->with('schedule', 'transaction');
        }])->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getMultipleSchedules($ids = [], $status = null) {
        $query = ClassSchedule::whereIn('class_schedule_uuid', $ids);
        if (!empty($status) && $status != 'history') {
            $query = $query->where(function ($inner_q) {
                $inner_q->where('class_date', '>', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->where('class_date', '=', date('Y-m-d'));
                    $q->where('from_time', '>', date('H:i:s'));
                });
            });
        } elseif (!empty($status) && $status == 'history') {
            $query = $query->where(function ($inner_q) {
                $inner_q->where('class_date', '<', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->where('class_date', '=', date('Y-m-d'));
                    $q->where('from_time', '<=', date('H:i:s'));
                });
            });
        }
        $query = $query->with('classBookings.package');
        $query = $query->with('classes.freelancer.profession');
        $query = $query->with('classes.FreelanceCategory.SubCategory');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function deleteClassSchedule($column, $value) {
//        return ClassSchedule::where($column, '=', $value)->delete();
    }

    protected function updateSchedulesWithIds($col, $ids = [], $data = []) {
        $query = ClassSchedule::whereIn($col, $ids)
            ->update($data);
        return $query ? true : false;
    }

    public static function getClassSchedules($column, $ids = []) {
        $query = ClassSchedule::whereIn($column, $ids);
        $result = $query->select('class_date', 'from_time', 'status')
            ->get();
        return !empty($result) ? $result->toArray() : [];
    }


    protected function saveClassSchedule($data) {
        $result = ClassSchedule::insert($data);
        return ($result) ? $result : [];
    }

}
