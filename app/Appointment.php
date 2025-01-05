<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'appointment_uuid';
    public $timestamps = true;
    protected $fillable = ['appointment_uuid', 'customer_id', 'freelancer_id', 'service_id', 'package_id', 'purchased_package_uuid', 'title', 'type', 'status', 'price', 'appointment_date', 'from_time', 'to_time', 'is_archive', 'is_online', 'online_link', 'discount', 'travelling_charges', 'discounted_price', 'paid_amount', 'package_paid_amount', 'promocode_uuid', 'has_rescheduled', 'created_by'];

    //    protected $casts = [
    //        'appointment_date' => 'datetime:Y-m-d',
    //        'from_time' => 'datetime:h:i:s',
    //        'to_time' => 'datetime:h:i:s',
    //    ];

    public function AppointmentServices()
    {
        return $this->hasMany('\App\FreelancerCategory', 'id', 'service_id');
    }

    public function AppointmentCustomer()
    {
        return $this->hasOne('\App\Customer', 'id', 'customer_id');
    }

    public function AppointmentWalkinCustomer()
    {
        return $this->hasOne('\App\WalkinCustomer', 'walkin_customer_id', 'customer_id');
    }

    public function AppointmentFreelancer()
    {
        return $this->hasOne('\App\Freelancer', 'id', 'freelancer_id');
    }

    public function review()
    {
        return $this->hasOne('\App\Review', 'content_id', 'id');
    }

    public function package()
    {
        return $this->hasOne('\App\Package', 'id', 'package_id');
    }

    public function transaction()
    {
        return $this->hasOne('\App\FreelancerTransaction', 'content_id', 'id');
    }

    public function promo_code()
    {
        return $this->hasOne('\App\PromoCode', 'id', 'promocode_id');
    }

    public function RescheduledAppointments()
    {
        return $this->hasMany('\App\RescheduledAppointment', 'appointment_id', 'id');
    }

    public function LastRescheduledAppointment()
    {
        return $this->hasOne('\App\RescheduledAppointment', 'appointment_id', 'id')->orderBy('created_at', 'desc');
    }

    protected static function getFreelancerAppointments($freelancer_id)
    {
        $appointments = array();
        if (!empty($freelancer_id)) {
            $appointments = self::with('AppointmentCustomer', 'AppointmentServices')->where('appointments.freelancer_id', '=', $freelancer_id)->orderBy('appointments.id', 'desc')->get();
        }
        return !empty($appointments) ? $appointments->toArray() : [];
    }

    protected static function getCustomerAppointments($column, $value)
    {
        $appointments = array();
        if (!empty($customer_uuid)) {
            $appointments = self::with('AppointmentServices.Service', 'AppointmentFreelancer', 'AppointmentCustomer')
                ->where($column, $value)
                ->orderBy('appointments.id', 'desc')->get();
        }
        return !empty($appointments) ? $appointments->toArray() : [];
    }

    protected static function getCustomerAllAppointments($column, $value, $quey_parameters = [], $limit = null, $offset = null)
    {
        $quey_parameters = isset($quey_parameters['search_params']) ? $quey_parameters['search_params'] : [];
        $query = Appointment::where($column, '=', $value);
        if (isset($quey_parameters['appoint_type']) && $quey_parameters['appoint_type'] !== 'history') {
            if ($quey_parameters['appoint_type'] !== 'upcoming' && $quey_parameters['appoint_type'] !== 'past' && $quey_parameters['appoint_type'] !== 'history') {
                $query = $query->where('status', '=', $quey_parameters['appoint_type']);
                //                $query = $query->where('appointment_date', '>=', date('Y-m-d'));
                $query = $query->where(function ($inner_q) {
                    $inner_q->where('appointment_date', '>', date('Y-m-d'));
                    $inner_q->orWhere(function ($q) {
                        $q->where('appointment_date', '=', date('Y-m-d'));
                        $q->where('from_time', '>', date('H:i:s'));
                    });
                });
            } elseif ($quey_parameters['appoint_type'] === 'upcoming') {
                $query = $query->where('status', '!=', 'cancelled');
                $query = $query->where('status', '!=', 'rejected');
                //                $query = $query->where('appointment_date', '>=', date('Y-m-d'));
                $query = $query->where(function ($inner_q) {
                    $inner_q->where('appointment_date', '>', date('Y-m-d'));
                    $inner_q->orWhere(function ($q) {
                        $q->where('appointment_date', '=', date('Y-m-d'));
                        $q->where('from_time', '>', date('H:i:s'));
                    });
                });
            }
        } elseif (isset($quey_parameters['appoint_type']) && ($quey_parameters['appoint_type'] === 'history' || $quey_parameters['appoint_type'] === 'past')) {
            //            $query = $query->where('appointment_date', '<=', date('Y-m-d'));
            $query = $query->where(function ($inner_q) {
                $inner_q->where('appointment_date', '<', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->where('appointment_date', '=', date('Y-m-d'));
                    $q->where('from_time', '<=', date('H:i:s'));
                });
            });
        }
        if (isset($quey_parameters['date'])) {
            $query = $query->where('appointment_date', '=', $quey_parameters['date']);
        }
        if (isset($quey_parameters['from_time'])) {
            $query = $query->where('from_time', '=', $quey_parameters['from_time']);
        }
        if (isset($quey_parameters['to_time'])) {
            $query = $query->where('to_time', '=', $quey_parameters['to_time']);
        }
        $query = $query->with('AppointmentServices.SubCategory', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $query = $query->with('LastRescheduledAppointment');
        $query = $query->orderBy('created_at', 'DESC');
        //        if (!empty($offset)) {
        //            $query = $query->offset($offset);
        //        }
        //        if (!empty($limit)) {
        //            $query = $query->limit($limit);
        //        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getFreelancerAllAppointments($column, $value, $quey_parameters = [], $limit = null, $offset = null, $type = null)
    {
        $query = Appointment::where($column, '=', $value);
        if (isset($quey_parameters['status']) && $quey_parameters['status'] !== 'history') {
            $query = $query->where('status', '=', $quey_parameters['status']);
            //            $query = $query->where('appointment_date', '>=', date('Y-m-d'));
            $query = $query->where(function ($inner_q) {
                $inner_q->where('appointment_date', '>', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->where('appointment_date', '=', date('Y-m-d'));
                    $q->where('from_time', '>', date('H:i:s'));
                });
            });
        } elseif (isset($quey_parameters['status']) && $quey_parameters['status'] === 'history') {

            $query = $query->where('status', '!=', 'pending');
            $query = $query->where(function ($inner_q) {
                $inner_q->whereDate('appointment_date', '<', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->whereDate('appointment_date', '=', date('Y-m-d'));
                    $q->where('from_time', '<', date('H:i:s'));
                });
            });
        }
        if (isset($quey_parameters['date'])) {
            $query = $query->where('appointment_date', '=', $quey_parameters['date'])->where('status', '<>', 'cancelled')->where('status', '<>', 'rejected');
        }
        if (isset($quey_parameters['from_time'])) {
            $query = $query->where('from_time', '=', $quey_parameters['from_time']);
        }
        if (isset($quey_parameters['to_time'])) {
            $query = $query->where('to_time', '=', $quey_parameters['to_time']);
        }
        $query = $query->with('AppointmentServices.SubCategory', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $query = $query->with('LastRescheduledAppointment');
        $query = $query->orderBy('created_at', 'DESC');
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

    protected static function getClientAppointmentsRevenue($freelancer_id, $customer_uuid)
    {
        return Appointment::where('freelancer_id', '=', $freelancer_id)
            ->where('customer_uuid', '=', $customer_uuid)
            ->where('status', '=', 'completed')
            ->sum('price');
    }

    protected static function getAppointmentsRevenue($column, $value)
    {
        return Appointment::where($column, '=', $value)
            ->where('status', '=', 'completed')
            ->sum('price');
    }

    protected static function getClientAppointmentsCount($freelancer_id, $customer_uuid)
    {
        return Appointment::where('freelancer_id', '=', $freelancer_id)
            ->where('customer_uuid', '=', $customer_uuid)
            ->count();
    }

    protected static function oneDayAppointmentCheck($customer_uuid, $freelancer_id)
    {
        $data = Appointment::where('freelancer_id', '=', $freelancer_id)
            ->where('customer_uuid', '=', $customer_uuid)
            ->where('status', "pending")
            ->get();
        $result = self::getResult($data);
        if (empty($result)) {
            $data = Appointment::where('freelancer_id', '=', $freelancer_id)
                ->where('customer_uuid', '=', $customer_uuid)
                ->where('status', "confirmed")
                ->select('status', 'appointment_date', 'from_time')
                ->get();
            $result = self::getResult($data);
        }
        return !empty($result) ? $result : [];
    }

    protected static function completedAppointmentCheck($customer_uuid, $freelancer_id)
    {
        $data = Appointment::where('freelancer_id', '=', $freelancer_id)
            ->where('customer_uuid', '=', $customer_uuid)
            ->where('status', "completed")
            ->select('status', 'appointment_date', 'from_time')
            ->get();
        return ($data) ? $data->toArray() : [];
    }

    protected static function getResult($result)
    {
        return ($result) ? $result->toArray() : [];
    }

    protected static function checkHasAppointment($customer_uuid, $freelancer_id)
    {
        $result = Appointment::where('freelancer_id', '=', $freelancer_id)
            ->where('customer_uuid', '=', $customer_uuid)
            ->where(function ($q) {
                $q->where('status', "pending")
                    ->orWhere('status', "confirmed");
            })
            //                ->where('status', '=', 'pending')
            //                ->ORwhere('status', '=', 'confirmed')
            //                ->ORwhereBetween('from_time', [now()->subMinutes(1440), now()])
            ->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getClientAppointments($freelancer_id, $customer_uuid, $limit = null, $offset = null)
    {
        $query = Appointment::where('freelancer_id', '=', $freelancer_id);
        $query = $query->where('customer_uuid', '=', $customer_uuid);
        $query = $query->with('AppointmentServices.SubCategory', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $query = $query->with('LastRescheduledAppointment');

        $query->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('status', 'pending');
                $q2->where(function ($inner_q) {
                    $inner_q->where('appointment_date', '>', date('Y-m-d'));
                    $inner_q->orWhere(function ($q3) {
                        $q3->where('appointment_date', '=', date('Y-m-d'));
                        $q3->where('from_time', '>', date('H:i:s'));
                    });
                });
            });
            $q->orWhere(function ($q4) {
                $q4->where('status', '!=', 'pending');
            });
        });


        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();

        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getFreelancerAllAppointmentWithinDates($freelancer_id, $quey_parameters = [])
    {
        $query = Appointment::where('freelancer_id', '=', $freelancer_id);
        if (isset($quey_parameters['start_date'])) {
            $query = $query->where('appointment_date', '>=', $quey_parameters['start_date']);
        }
        if (isset($quey_parameters['end_date'])) {
            $query = $query->where('appointment_date', '<=', $quey_parameters['end_date']);
        }
        $query = $query->with('AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getUpcomingAppointments($column, $value, $limit = null, $offset = null)
    {
        $query = Appointment::where($column, '=', $value);
        $query = $query->where(function ($inner_q) {
            $inner_q->where('appointment_date', '>', date('Y-m-d'));
            $inner_q->orWhere(function ($q) {
                $q->where('appointment_date', '=', date('Y-m-d'));
                $q->where('from_time', '>', date('H:i:s'));
            });
        });
        $query = $query->with('AppointmentServices', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $query = $query->with('LastRescheduledAppointment');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getCustomerPackageAppointments($customer_uuid = null, $package_uuid = null, $limit = null, $offset = null)
    {
        $query = Appointment::where('customer_uuid', '=', $customer_uuid);
        $query = Appointment::where('package_uuid', '=', $package_uuid);
        //        $query = $query->where(function ($inner_q) {
        //            $inner_q->where('appointment_date', '>', date('Y-m-d'));
        //            $inner_q->orWhere(function ($q) {
        //                $q->where('appointment_date', '=', date('Y-m-d'));
        //                $q->where('from_time', '>', date('H:i:s'));
        //            });
        //        });
        $query = $query->with('AppointmentServices', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getFreelancerAppointmentsCount($freelancer_id, $status = null)
    {
        $query = Appointment::where('freelancer_id', '=', $freelancer_id);
        if (!empty($status)) {
            $query = $query->where('status', '=', $status);
            if ($status !== 'history') {
                $query = $query->where(function ($inner_q) {
                    $inner_q->where('appointment_date', '>', date('Y-m-d'));
                    $inner_q->orWhere(function ($q) {
                        $q->where('appointment_date', '=', date('Y-m-d'));
                        $q->where('from_time', '>', date('H:i:s'));
                    });
                });
            }
        }
        return $query->count();
    }

    protected static function getCustomerAppointmentsCount($customer_uuid, $status = [], $type = 'current')
    {
        $query = Appointment::where('customer_uuid', '=', $customer_uuid);
        if (!empty($status)) {
            $query = $query->whereIn('status', $status);
        }
        if ($type == 'current') {
            $query = $query->where(function ($inner_q) {
                $inner_q->whereDate('appointment_date', '>', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->whereDate('appointment_date', '=', date('Y-m-d'));
                    $q->where('from_time', '>', date('H:i:s'));
                });
            });
        }
        if ($type == 'history') {
            $query = $query->where(function ($inner_q) {
                $inner_q->whereDate('appointment_date', '<', date('Y-m-d'));
                $inner_q->orWhere(function ($q) {
                    $q->whereDate('appointment_date', '=', date('Y-m-d'));
                    $q->where('from_time', '<', date('H:i:s'));
                });
            });
        }
        return $query->count();
    }

    //    protected static function getAppointmentDetail($column, $value) {
    //        $appointment_detail = Appointment::where($column, '=', $value)
    //                ->with('AppointmentServices', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package', 'transaction')
    //                ->with('LastRescheduledAppointment')
    //                ->first();
    //        return !empty($appointment_detail) ? $appointment_detail->toArray() : [];
    //    }

    protected static function getAppointmentWithPurchasedPackages($column, $value)
    {
        $appointment_detail = Appointment::where($column, '=', $value)
            ->with('AppointmentServices', 'AppointmentFreelancer.profession', 'package', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'transaction', 'LastRescheduledAppointment')
            ->get();
        return !empty($appointment_detail) ? $appointment_detail->toArray() : [];
    }

    protected static function saveAppointment($data)
    {
        $result = Appointment::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function updateAppointmentStatus($column, $value, $data)
    {
        return Appointment::where($column, '=', $value)->update($data);
    }

    protected static function searchAppointments($column, $value, $query_parameters = [], $limit = null, $offset = null)
    {
        //$query = new Appointment;
        $query = Appointment::where($column, '=', $value);
        if (isset($query_parameters['status'])) {
            $query = $query->where('status', '=', $query_parameters['status']);
        }
        if (isset($query_parameters['start_date'])) {
            $query = $query->where('appointment_date', '>=', $query_parameters['start_date']);
        }
        if (isset($query_parameters['end_date'])) {
            $query = $query->where('appointment_date', '<=', $query_parameters['end_date']);
        }
        if (isset($query_parameters['from_time'])) {
            $query = $query->where('from_time', '>=', $query_parameters['from_time']);
        }
        if (isset($query_parameters['to_time'])) {
            $query = $query->where('to_time', '<=', $query_parameters['to_time']);
        }
        if (isset($query_parameters['customer'])) {
            $query = $query->where('customer_uuid', '=', $query_parameters['customer']);
        }
        if (isset($query_parameters['service_uuid'])) {
            $query = $query->where('service_uuid', '=', $query_parameters['service_uuid']);
        }
        $query = $query->with('AppointmentServices', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $query = $query->with('LastRescheduledAppointment');

        if (!empty($offset)) {
            $query = $query->offset($offset);
        }
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getAllAppointments($col, $val)
    {
        $appointments = self::with('AppointmentServices', 'AppointmentFreelancer', 'AppointmentCustomer')
            ->where($col, $val)->orderBy('id', 'desc')->get();
        return !empty($appointments) ? $appointments->toArray() : [];
    }

    protected static function getAppointmentDetail($where = [])
    {
        $appointment_detail = array();
        if (!empty($where)) {
            $appointment_detail = self::with('AppointmentServices', 'AppointmentFreelancer')
                ->where($where)->orderBy('id', 'desc')->first();
        }
        return !empty($appointment_detail) ? $appointment_detail->toArray() : [];
    }

    public static function pluckFavIds($column, $value, $pluck_data = null)
    {
        $query = Appointment::where($column, $value);
        $result = $query->pluck($pluck_data);
        return !empty($result) ? $result->toArray() : [];
    }

    public static function pluckFavIdsWithStatus($column, $value, $pluck_data = null)
    {
        $query = Appointment::where($column, $value);
        $query->where('status', '!=', "completed");
        $query->where('status', '!=', "rejected");
        $result = $query->pluck($pluck_data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getPendingAppointments($column, $value)
    {
        $query = Appointment::where($column, '=', $value);
        $query = $query->where('status', '=', 'pending');
        $query = $query->where(function ($inner_q) {
            $inner_q->where('appointment_date', '>=', date('Y-m-d'));
            $inner_q->orWhere(function ($q) {
                $q->where('appointment_date', '=', date('Y-m-d'));
                $q->where('from_time', '>', date('H:i:s'));
            });
        });
        $query = $query->with('AppointmentServices', 'AppointmentFreelancer.profession', 'AppointmentFreelancer.profession', 'AppointmentCustomer', 'AppointmentWalkinCustomer', 'package');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getPastAppointmentsWithStatus($column, $value, $status, $limit = null, $offset = null)
    {
        $query = Appointment::where($column, '=', $value);
        $query = $query->where(function ($inner_q) use ($status) {
            $inner_q->where('appointment_date', '<', date('Y-m-d'));
            $inner_q->where('status', '=', $status);
            $inner_q->orWhere(function ($q) {
                $q->where('appointment_date', '=', date('Y-m-d'));
                $q->where('from_time', '<', date('H:i:s'));
            });
        });
        //        $result = $query->pluck('appointment_uuid');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function updateAppointmentWithIds($col, $ids = [], $data = [])
    {
        $query = Appointment::whereIn($col, $ids)
            ->update($data);
        return $query ? true : false;
    }

    protected static function getAppointmentWithIds($col, $uuid, $val, $ids = [])
    {
        $query = Appointment::whereIn($val, $ids)
            ->where($col, '=', $uuid)
            ->where('is_archive', '', 0)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'completed');
        $result = $query->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getPastAppointments($column, $value, $status)
    {
        $query = Appointment::where($column, '=', $value)
            ->where('status', '=', $status)
            ->where('created_at', '<', Carbon::parse('-24 hours'));
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected static function getClientAppointmentsHistoryCount($customer_uuid, $freelancer_id, $status = [])
    {
        $query = Appointment::where('customer_uuid', '=', $customer_uuid);
        $query = $query->where('freelancer_id', '=', $freelancer_id);
        if (!empty($status)) {
            $query = $query->whereIn('status', $status);
        }

        $query = $query->where(function ($inner_q) {
            $inner_q->whereDate('appointment_date', '<', date('Y-m-d'));
            $inner_q->orWhere(function ($q) {
                $q->whereDate('appointment_date', '=', date('Y-m-d'));
                $q->where('to_time', '<', date('H:i:s'));
            });
        });

        return $query->count();
    }

    protected static function getSingleAppointment($column, $value)
    {
        $appointment_detail = Appointment::where($column, '=', $value)
            ->where('is_archive', '=', 0)
            ->first();
        return !empty($appointment_detail) ? $appointment_detail->toArray() : [];
    }

    protected static function getAppointmentsCount($where = [])
    {
        $count = self::where($where)->count();
        return !empty($count) ? $count : 0;
    }

    protected static function getFreelancerCustomers($where = [])
    {
        $count = self::where($where)->distinct()->count('customer_id');
        return !empty($count) ? $count : 0;
    }

    protected static function createAppointment($data)
    {
        $result = self::create($data);
        return ($result) ? $result->toArray() : [];
    }

    protected static function updateAppointment($where, $data)
    {
        return self::where($where)->update($data);
    }

    protected static function getAppointmentsCurrentMonth($status)
    {
        $result = self::whereIn('status', $status)->where('is_archive', 0)
            ->whereMonth('appointment_date', date('m'))
            ->select(
                array(
                    DB::Raw('count(status) as status_count'),
                    'status',
                    'appointment_date'
                )
            )
            ->groupBy('status', 'appointment_date')
            ->get();
        return ($result) ? $result->toArray() : [];
    }

    protected static function getCustomerAppointmentsData($column, $value)
    {
        $appointments = array();
        if (!empty($value)) {
            $appointments = self::with('AppointmentFreelancer', 'AppointmentCustomer')
                ->where($column, $value)
                ->orderBy('appointments.id', 'desc')->get();
        }
        return !empty($appointments) ? $appointments->toArray() : [];
    }

    protected static function getFreelancerEarnings($freelancer_id)
    {
        $appointments = array();
        if (!empty($freelancer_id)) {
            $appointments = self::where('appointments.freelancer_id', '=', $freelancer_id)->where('status', '=', 'completed')->sum('price');
        }
        return !empty($appointments) ? $appointments : 0;
    }

    protected static function getFreelancerMonthlyEarnings($freelancer_id)
    {
        $appointments = array();
        if (!empty($freelancer_id)) {
            $appointments = self::where('appointments.freelancer_id', '=', $freelancer_id)->where('status', '=', 'completed')->whereMonth('appointment_date', \Carbon\Carbon::now()->month)->sum('price');
        }

        return !empty($appointments) ? $appointments : 0;
    }

    protected static function getFreelancerYearlyEarnings($freelancer_id)
    {
        $appointments = array();
        if (!empty($freelancer_id)) {
            $appointments = self::where('appointments.freelancer_id', '=', $freelancer_id)->where('status', '=', 'completed')->whereYear('appointment_date', \Carbon\Carbon::now()->year)->sum('price');
        }
        return !empty($appointments) ? $appointments : 0;
    }

    protected static function getAppEarning()
    {
        $appointments = self::where('status', '=', 'completed')->sum('price');
        return !empty($appointments) ? $appointments : 0;
    }

    //    protected static function updateAppointmentStatus($col, $val, $data) {
    //        $result = self::where($col, '=', $val)->update($data);
    //        return !$result ? false : true;
    //    }

}
