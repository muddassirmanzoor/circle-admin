<?php

namespace App\Console\Commands;

use App\ClassSchedule;
use Illuminate\Console\Command;
use App\Appointment;
use App\ClassBooking;
use App\Package;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangeBookingStatus extends BaseCommand {

    /**

     * @author ILSA Interactive

     * @var string

     */
    protected $signature = 'change_status:reminder';

    /**
     * The console command name To Display.
     *
     * @var string
     */
    protected $commandName = 'Booking Status Update';
    /**

     * The console command description.

     *

     * @var string

     */
    protected $description = 'This job automatically updates booking statuses from pending to reject and confirm to complete after 24 hour of their appointment time';

    /**
     * File Log Channel
     *
     * @var string
     */
    protected $logChannel = 'cron_booking_status';

    /**

     * Create a new command instance.

     *

     * @return void

     */
    public function __construct() {

        parent::__construct();
    }

    /**

     * Execute the console command.

     *

     * @return mixed

     */
    public function handle() {

        $this->log('Initiating');
//        Log::channel('daily_change_status')->debug('job started');

        $booking_status = self::automateProcess();

        if (!$booking_status['success']) {
            $this->log('Process failed with exception', [
                'message' => $booking_status['message']
            ], 'error');
            $this->InsertDBLog(0);
        }
        else{
            $this->log($booking_status['message']);
//            DB::rollback();
            $this->InsertDBLog();
        }
//        if (!$send_emails['success']) {
//            $this->info($send_emails['message']);
//        }
//        $this->info($send_emails['message']);
//        Log::channel('daily_change_status')->debug($send_emails['message']);
//        Log::channel('daily_change_status')->debug('job ended');
    }

    public function automateProcess() {
        try {
            DB::beginTransaction();
            $pending_appointments = self::updatePendingAppointmentStatus();
            $confirmed_appointments = self::updateConfirmedAppointmentStatus();
            $confirmed_class_bookings = self::updateConfirmedClassBookingStatus();
            //$cancelled_class_bookings = self::updateCancelledClassBookingStatus();
            $process_packages = self::updatePackages();
            DB::commit();
            return ['success' => true, 'message' => 'Appointment Statuses updated ! job successfully executed.'];
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return ['success' => false, 'message' => 'Error: ' . $ex->getMessage()];
        } catch (\Exception $ex) {
            DB::rollback();
            return ['success' => false, 'message' => 'Error: ' . $ex->getMessage()];
        }
    }

    public function updatePendingAppointmentStatus() {
        $update_data = true;
//                $appointments = Appointment::getPastAppointmentsWithStatus('is_archive', 0, "pending");
        $appointments = Appointment::getPastAppointments('is_archive', 0, "pending");
        if (!empty($appointments)) {
            $ids = [];
            foreach ($appointments as $key => $appointment_id) {
                if (!in_array($appointment_id['appointment_uuid'], $ids)) {
                    array_push($ids, $appointment_id['appointment_uuid']);
                }
            }
//            $appointment_ids = self::confirmedAppointmentCheck($appointments);

            $data = ['status' => 'rejected'];
            $this->log('Pending Appointments Found: ', [
                'appointments' => $ids
            ]);
//            Log::channel('daily_change_status')->debug("pending appointmnets");
//            Log::channel('daily_change_status')->debug($ids);
            $update_data = Appointment::updateAppointmentWithIds('appointment_uuid', $ids, $data);
//            $update_data = Appointment::updateAppointmentWithIds('appointment_uuid', $appointment_ids, $data);
//            DB::commit();
        } else{
            $this->log('No Pending Appointment Found');
        }
        return $update_data;
    }

    public function updateConfirmedAppointmentStatus() {
        $update_data = true;
        $appointments = Appointment::getPastAppointmentsWithStatus('is_archive', 0, "confirmed");
        if (!empty($appointments)) {
            $appointment_ids = self::confirmedAppointmentCheck($appointments);
            $data = ['status' => 'completed'];
            if (!empty($appointment_ids)):
                $this->log('Confirmed Appointments Found.', [
                    'appointments' => $appointment_ids
                ]);
            else:
                $this->log('No Confirmed Appointment Found.');
            endif;

//            Log::channel('daily_change_status')->debug("confirmed appointmnets");
//            Log::channel('daily_change_status')->debug($appointment_ids);
            $update_data = Appointment::updateAppointmentWithIds('appointment_uuid', $appointment_ids, $data);
//            DB::commit();
        } else{
            $this->log('No Confirmed Appointment Found');
        }
        return $update_data;
    }

    public function confirmedAppointmentCheck($appointment_time) {
        if (!empty($appointment_time)) {
            $ids = [];
            $to_time = now();
            foreach ($appointment_time as $key => $time) {
                $from_time = $time['appointment_date'] . ' ' . $time['from_time'];
                if ((strtotime($time['appointment_date']) <= strtotime(date('Y-m-d')))) {
                    $calculate_difference = CommonHelper::getTimeDifferenceInHours($from_time, $to_time);
                    if ($calculate_difference->d >= 1 || $calculate_difference->h > 23) {
                        if (!in_array($time['appointment_uuid'], $ids)) {
                            array_push($ids, $time['appointment_uuid']);
                        }
                    }
                }
            }
        }
        return ($ids) ? $ids : [];
    }

    public function updatePackages() {
        $update_data = true;
        $package_ids = [];
        $packages = Package::getPackages('is_archive', 0);
        if (!empty($packages)) {
            foreach ($packages as $key => $package) {
                if (!empty($package)) {
                    $get_validity_date = \App\Helpers\PackageHelper::createPackageValidityDate($package);
                    if ((strtotime($get_validity_date) < strtotime(date('Y-m-d')))) {
                        $calculate_difference = CommonHelper::getTimeDifferenceInHours($get_validity_date, date('Y-m-d'));
                        if ($calculate_difference->d > 0 || $calculate_difference->m > 0 || $calculate_difference->y > 0) {
                            if (!in_array($package['package_uuid'], $package_ids)) {
                                array_push($package_ids, $package['package_uuid']);
                            }
                        }
                    }
                }
            }
            $data = ['is_archive' => 1];
            if (!empty($package_ids)):
                $this->log('Packages Found: ', [
                    'packages' => $package_ids
                ]);
            else:
                $this->log('No Package Found.');
            endif;

            $update_data = Package::updatePackagesUsingUuids($package_ids, $data);
        } else{
            $this->log('No Package Found');
        }
        return $update_data;
    }

    public function updateConfirmedClassBookingStatus() {
        $booking_ids = [];
        $schedule_ids = [];
        $class_bookings = ClassBooking::getPastConfirmedClassBookings();

        foreach ($class_bookings as $key => $booking) {
            if (!empty($booking)) {
                if ((strtotime($booking->date) < strtotime(date('Y-m-d')))) {
                    if (!in_array($booking->class_schedule_uuid, $schedule_ids)) {
                        array_push($schedule_ids, $booking->class_schedule_uuid);
                    }
                    if (!in_array($booking->class_booking_uuid, $booking_ids)) {
                        array_push($booking_ids, $booking->class_booking_uuid);
                    }
                }
            }
        }

        if (empty($class_bookings)):
            $this->log('No past confirmed class booking found');
        endif;

        if (!empty($schedule_ids)):
            $this->log('Confirmed schedules: ', [
                'schedules' => $schedule_ids
            ]);
        endif;

        if (!empty($booking_ids)):
            $this->log('Confirmed bookings: ', [
                'bookings' => $booking_ids
            ]);
        endif;

//        Log::channel('daily_change_status')->debug("confirmed schedules");
//        Log::channel('daily_change_status')->debug($schedule_ids);
//        Log::channel('daily_change_status')->debug("confirmed bookings");
//        Log::channel('daily_change_status')->debug($booking_ids);
        $data = ['status' => 'completed'];
        $updateSchedules = ClassSchedule::updateSchedulesWithIds('class_schedule_uuid', $schedule_ids, $data);
        $update_data = ClassBooking::updateBookingWithIds('class_booking_uuid', $booking_ids, $data);

        return $update_data;
    }

    public function updateCancelledClassBookingStatus() {
        $booking_ids = [];
        $class_bookings = ClassBooking::getPastCancelledClassBookings();
        foreach ($class_bookings as $key => $booking) {
            if (!empty($booking)) {
                if ((strtotime($booking->date) < strtotime(date('Y-m-d')))) {
                    if (!in_array($booking->class_booking_uuid, $booking_ids)) {
                        array_push($booking_ids, $booking->class_booking_uuid);
                    }
                }
            }
        }
        $data = ['status' => 'rejected'];
        if (!empty($booking_ids)):
            $this->log('Cancelled classes: ', [
                'classes' => $booking_ids
            ]);
        endif;
//        Log::channel('daily_change_status')->debug("cancelled classs");
//        Log::channel('daily_change_status')->debug($booking_ids);
        $update_data = ClassBooking::updateBookingWithIds('class_booking_uuid', $booking_ids, $data);
        return $update_data;
    }

//    public function checkDateAndGetIds($package) {
//        if (!empty($package)) {
//            $ids = [];
//            if ((strtotime($package['validity_date']) < strtotime(date('Y-m-d')))) {
//                $calculate_difference = CommonHelper::getTimeDifferenceInHours($package['validity_date'], date('Y-m-d'));
//                if ($calculate_difference->d > 0 || $calculate_difference->m > 0 || $calculate_difference->y > 0) {
//                    if (!in_array($package['package_uuid'], $package_ids)) {
//                        array_push($package_ids, $package['package_uuid']);
//                    }
//                }
//            }
//        }
//        return ($ids) ? $ids : [];
//    }
}
