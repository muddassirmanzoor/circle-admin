<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'class_uuid';
    public $timestamps = true;
    protected $fillable = ['class_uuid', 'freelancer_id', 'service_id', 'name', 'image', 'no_of_students', 'currency', 'price', 'start_date', 'end_date', 'status', 'notes', 'address', 'lat', 'lng', 'description_video', 'description_video_thumbnail', 'online_link', 'is_archive'];

    public function schedule()
    {
        return $this->hasMany('\App\ClassSchedule', 'class_id', 'id')->where('is_archive', '=', 0);
    }

    public function FreelancerSubCategory()
    {
        return $this->hasOne('\App\FreelanceCategory', 'sub_category_id', 'service_id');
    }

    public function classBookings()
    {
        return $this->hasMany('\App\ClassBooking', 'class_id', 'id');
    }

    public function singleSchedule()
    {
        return $this->hasOne('\App\ClassSchedule', 'class_id', 'id');
    }

    public function freelancer()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'freelancer_id');
    }

    public function FreelanceCategory()
    {
        return $this->hasOne('\App\FreelanceCategory', 'id', 'service_id');
    }

    protected function saveClass($data)
    {
        $result = Classes::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getMultipleUpcomingClasses($column, $value, $uuid_column_name, $uuid_array = [])
    {
        $query = Classes::where($column, '=', $value)->whereIn($uuid_column_name, $uuid_array);
        $query = $query->where('is_archive', '=', 0);
        $query = $query->with(['schedule' => function ($q) {
            $q->where(function ($inner_sql_q) {
                $inner_sql_q->where('class_date', '=', date('Y-m-d'));
                $inner_sql_q->where('from_time', '>', date('H:i:s'));
            });
            $q->orwhere(function ($inner_sql_q) {
                $inner_sql_q->where('class_date', '>', date('Y-m-d'));
            });
        }]);
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClasses($column, $value, $search_params = [], $limit = null, $offset = null, $type = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (isset($search_params['from_slots']) && !empty($search_params['from_slots'])) {
            $query = $query->whereIn('status', ['confirmed', 'pending']);
        }
        if (isset($search_params['freelancer_category_uuid'])) {
            $query = $query->where('service_uuid', '=', $search_params['freelancer_category_uuid']);
        }
        if (!empty($search_params['date'])) {
            $query = $query->whereHas('schedule', function ($q) use ($search_params) {
                $q->where('class_date', '=', $search_params['date']);
            });
        }
        if (!empty($search_params['status'])) {
            if ($search_params['status'] !== 'history' && $search_params['status'] !== 'past') {
                if ($search_params['status'] == 'pending') {
                    $search_params['status'] = 'confirmed';
                }
                $query = $query->whereHas('schedule', function ($q) use ($search_params) {
                    //                    $q->where('class_date', '>=', date('Y-m-d'));
                    //                    $q->where('status', '=', $search_params['status']);
                    $q->where(function ($inner_sql_qr) use ($search_params) {
                        $inner_sql_qr->where('class_date', '=', date('Y-m-d'));
                        $inner_sql_qr->where('from_time', '>', date('H:i:s'));
                        $inner_sql_qr->where('status', '=', $search_params['status']);
                    });
                    $q->orwhere(function ($inner_sql_qr) use ($search_params) {
                        $inner_sql_qr->where('class_date', '>', date('Y-m-d'));
                        $inner_sql_qr->where('status', '=', $search_params['status']);
                    });
                });
            } elseif ($search_params['status'] === 'history' || $search_params['status'] === 'past') {
                $query = $query->whereHas('schedule', function ($q) use ($search_params) {
                    $q->where('class_date', '<=', date('Y-m-d'));
                    $q->where(function ($inner_sqli) use ($search_params) {
                        $inner_sqli->where('class_date', '=', date('Y-m-d'));
                        $inner_sqli->where('from_time', '<=', date('H:i:s'));
                    });
                    $q->orwhere(function ($inner_sqli) use ($search_params) {
                        $inner_sqli->where('class_date', '<', date('Y-m-d'));
                    });
                });
            }
        }
        $query = $query->with('schedule.classBookings.customer');
        $query = $query->with(['schedule' => function ($q) use ($search_params) {
            if (!empty($search_params['status']) && ($search_params['status'] !== 'history' && $search_params['status'] !== 'past')) {
                $q->where(function ($inner_sql_q) use ($search_params) {
                    $inner_sql_q->where('class_date', '=', date('Y-m-d'));
                    $inner_sql_q->where('from_time', '>', date('H:i:s'));
                    $inner_sql_q->where('status', '=', $search_params['status']);
                });
                $q->orwhere(function ($inner_sql_q) use ($search_params) {
                    $inner_sql_q->where('class_date', '>', date('Y-m-d'));
                    $inner_sql_q->where('status', '=', $search_params['status']);
                });
            } elseif (!empty($search_params['status']) && ($search_params['status'] === 'history' || $search_params['status'] === 'past')) {
                $q->where(function ($inner_sql) use ($search_params) {
                    $inner_sql->where('class_date', '=', date('Y-m-d'));
                    $inner_sql->where('from_time', '<=', date('H:i:s'));
                });
                $q->orwhere(function ($inner_sql) use ($search_params) {
                    $inner_sql->where('class_date', '<', date('Y-m-d'));
                });
            }
        }]);
        $query = $query->with('freelancer');
        $query = $query->with('FreelanceCategory');
        if (empty($type)) {
            if (!empty($offset)) {
                $query = $query->offset($offset);
            }
            if (!empty($limit)) {
                $query = $query->limit($limit);
            }
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getAvailableClasses($column, $value, $search_params = [], $limit = null, $offset = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (isset($search_params['freelancer_category_uuid'])) {
            $query->where('service_uuid', '=', $search_params['freelancer_category_uuid']);
        }
        if (!empty($search_params['date'])) {
            $query = $query->whereHas('singleSchedule', function ($q) use ($search_params) {
                $q->where('class_date', '=', $search_params['date']);
                if (date('Y-m-d') == $search_params['date']) {
                    $q->where('from_time', '>', date('H:i:s'));
                }
            });
            $query = $query->with('singleSchedule.classBookings.customer');
            $query = $query->with(['singleSchedule' => function ($sch_qry) use ($search_params) {
                $sch_qry->where('class_date', '=', $search_params['date']);
                $sch_qry->where('status', '=', "confirmed");
                $sch_qry->orderBy('from_time', 'ASC');
            }]);
        }
        if (!empty($search_params['class_uuid'])) {
            $query = $query->whereHas('singleSchedule', function ($q) use ($search_params) {
                $q->where('class_uuid', '=', $search_params['class_uuid']);
            });
        }
        $query->with('FreelanceCategory');
        $query->with('freelancer');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getFreelancerUpcomingClasses($column, $value, $date = null, $limit = null, $offset = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (!empty($date)) {

            $query = $query->whereHas('schedule', function ($qry) use ($date) {
                $qry->where('status', '=', 'confirmed');
                $qry->where(function ($inner_q) use ($date) {
                    $inner_q->where('class_date', '>', $date);
                    $inner_q->orWhere(function ($q) use ($date) {
                        $q->where('class_date', '=', $date);
                        $q->where('from_time', '>', date('H:i:s'));
                    });
                });
            });
        }

        $query = $query->where('status', '=', 'confirmed');
        $query = $query->with('schedule.classBookings.customer');
        $query = $query->with(['schedule' => function ($q) use ($date) {
            $q->where(function ($inner_qy) use ($date) {
                $inner_qy->where('class_date', '>', $date);
                $inner_qy->orWhere(function ($qy) use ($date) {
                    $qy->where('class_date', '=', $date);
                    $qy->where('from_time', '>', date('H:i:s'));
                });
            });
            $q->where('status', '=', 'confirmed');
        }]);
        $query = $query->with('FreelanceCategory.category');
        $query = $query->with('freelancer');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function searchClassesByDate($column, $value, $date = null, $limit = null, $offset = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (!empty($date)) {
            $query = $query->whereHas('schedule', function ($qry) use ($date) {
                $qry->where(function ($inner_q) use ($date) {
                    $inner_q->where('class_date', '=', $date);
                });
            });
        }
        $query = $query->where('status', '=', 'confirmed');
        $query = $query->with('schedule.classBookings.customer');
        $query = $query->with(['schedule' => function ($q) use ($date) {
            $q->where(function ($inner_qy) use ($date) {
                $inner_qy->where('class_date', '=', $date);
            });
            $q->where('status', '=', 'confirmed');
        }]);
        $query = $query->with('FreelanceCategory.category');
        $query = $query->with('freelancer');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getUpcomingClassesCount($column, $value, $date = null)
    {
        $query = Classes::where($column, '=', $value);
        if (!empty($date)) {
            $query = $query->whereHas('schedule', function ($query) use ($date) {
                $query->where('class_date', '>=', $date);
            });
        }
        return $query->count();
    }

    protected function getClassesWithinDate($column, $value, $search_params = [])
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (!empty($search_params)) {
            $query = $query->whereHas('schedule', function ($q) use ($search_params) {
                $q->where('class_date', '>=', $search_params['start_date'])->where('class_date', '<=', $search_params['end_date']);
            });
        }
        $query = $query->with('schedule');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClassDetail($column, $value)
    {
        $query = Classes::where($column, '=', $value);
        //        $query = Classes::whereHas('classBookings', function($q) {
        //                    $q->where('status', '=', 'confirmed');
        //                    $q->ORwhere('status', '=', 'pending');
        //                });
        $query = $query->with(['classBookings' => function ($query) {
            $query->where('status', '=', 'confirmed')
                ->ORwhere('status', '=', 'pending');
        }]);
        //        $query = $query->with(['schedule' => function($query) {
        //                $query->$query->whereHas('classBookings', function($q) {
        //                    $q->where('status', '=', 'confirmed');
        //                    $q->ORwhere('status', '=', 'pending');
        //                });
        //            }]);
        $query = $query->with('freelancer.profession', 'schedule.classBookings.customer');
        $query = $query->with('FreelanceCategory.SubCategory');
        $result = $query->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getClassesList($column, $value, $type = 'all', $search_params = [], $limit = null, $offset = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        $query = $query->with('FreelanceCategory.SubCategory', 'classBookings.customer', 'freelancer', 'singleSchedule');
        if (isset($search_params['status']) && !empty($search_params['status'])) {
            $query->where('status', $search_params['status']);
        }
        $query->orderBy('id', 'desc');
        if ($type == 'first')
            $result = $query->first();
        else {
            if (!empty($offset))
                $query = $query->offset($offset);
            if (!empty($limit))
                $query = $query->limit($limit);
            $result = $query->get();
        }
        return !empty($result) ? $result->toArray() : [];
    }

    protected function updateClass($column, $value, $data)
    {
        $result = Classes::where($column, '=', $value)->update($data);
        if (!$result) {
            return [];
        }
        return self::getSingleClass($column, $value);
    }

    protected function getSingleClass($column, $value)
    {
        $result = Classes::where($column, '=', $value)->with('schedule.classBookings')->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function checkFreelancerUpcomingClasses($column, $value, $date = null, $time = null)
    {

        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (!empty($date)) {
            $query = $query->whereHas('schedule', function ($query) use ($date, $time) {
                $query->where('class_date', '>', $date);
                $query->orWhere(function ($q) {
                    $q->where('class_date', '=', date('Y-m-d'));
                    $q->where('from_time', '>', date('H:i:s'));
                });
            });
        }
        $query = $query->with('schedule');
        $result = $query->exists();

        return !empty($result) ? $result : false;
    }

    protected function checkClassSearviceExist($column, $value, $query_data = [])
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        $query = $query->where('service_uuid', $query_data['service_uuid']);
        if (!empty($query_data['date'])) {
            $query = $query->whereHas('schedule', function ($q) use ($query_data) {
                $q->where('class_date', '>=', $query_data['date']);
            });
        }
        $query = $query->withCount('schedule');
        $query = $query->with('schedule');
        $result = $query->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function searchClasses($column, $value, $query_parameters = [], $limit = null, $offset = null)
    {
        $query = Classes::where($column, '=', $value);
        $query = $query->where('is_archive', '=', 0);
        if (isset($query_parameters['status'])) {
            $query = $query->where('status', '=', $query_parameters['status']);
        }
        if (isset($query_parameters['start_date'])) {
            $query = $query->where('start_date', '>=', $query_parameters['start_date']);
        }
        if (isset($query_parameters['end_date'])) {
            $query = $query->where('end_date', '<=', $query_parameters['end_date']);
        }
        if (isset($query_parameters['from_time']) || isset($query_parameters['to_time'])) {
            $query = $query->whereHas('schedule', function ($sql) use ($query_parameters) {
                if (isset($query_parameters['from_time'])) {
                    $sql->where("from_time", "=", $query_parameters['from_time']);
                }
                if (isset($query_parameters['to_time'])) {
                    $sql->where("to_time", "=", $query_parameters['to_time']);
                }
            });
        }

        //        if (isset($query_parameters['customer'])) {
        //            $query = $query->where('customer_uuid', '=', $query_parameters['customer']);
        //        }
        if (isset($query_parameters['service_uuid'])) {
            $query = $query->where('service_uuid', '=', $query_parameters['service_uuid']);
        }
        $query = $query->with('FreelanceCategory.SubCategory', 'freelancer', 'schedule');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function updateClassStatus($column, $value, $data)
    {
        return Classes::where($column, '=', $value)->update($data);
    }

    protected function getClassRevenue($column, $value)
    {
        return Classes::where($column, '=', $value)
            ->where('status', '=', 'completed')
            ->sum('price');
    }

    protected function getClassUuids($column, $value, $limit = null, $offset = null)
    {
        $result = Classes::where($column, '=', $value)
            ->pluck('class_uuid');
        return !empty($result) ? $result->toArray() : [];
    }

    public static function pluckFavoriteIds($column, $ids, $pluck_data = null)
    {
        $query = Classes::whereIn($column, $ids);
        $result = $query->pluck($pluck_data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getFreelancerClassess($freelancer_uuid)
    {
        $classess = array();
        if (!empty($freelancer_uuid)) {
            $classess = self::where('freelancer_id', '=', $freelancer_uuid)->where(['is_archive' => 0])->orderBy('id', 'desc')->get();
        }
        return !empty($classess) ? $classess->toArray() : [];
    }

    protected function getFreelancerClassSchedule($column, $value)
    {
        $query = Classes::where($column, '=', $value)->where('is_archive', '=', 0);
        $query = $query->with('schedule');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getCustomerClassBooking($column, $value)
    {
        $query = Classes::where($column, '=', $value)->where('is_archive', '=', 0);
        $query = $query->with('classBookings');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function find($class_uuid)
    {
        return Classes::where('class_uuid', "=", $class_uuid)->first();
    }
}
