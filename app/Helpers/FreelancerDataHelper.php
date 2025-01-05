<?php

namespace App\Helpers;

Class FreelancerDataHelper {

    public static function makeAppointmentDetailArray($response = []) {
        $data = [];
        if(!empty($response))
        {
            foreach ($response as $key => $row)
            {
                $data['freelancer_uuid'] = $row['freelancer_uuid'];
                $data['appointment_uuid'] = $row['appointment_uuid'];
                $data['title'] = $row['title'];
                $data['type'] = $row['type'];
                $data['status'] = $row['status'];
                $data['price'] = $row['price'];
                $data['date'] = CommonHelper::setUserDateFormat($row['date']);
                $data['from_time'] = CommonHelper::setUserTimeFormat($row['from_time']);
                $data['to_time'] = CommonHelper::setUserTimeFormat($row['to_time']);
            }
        }
        return $data;
    }

    public static function makeAppointmentsCalendarArray($data = []) {
        $response = [];
        if(!empty($data))
        {
            foreach ($data as $key => $row)
            {
                $from_time = AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'],$row['local_timezone']);
                $to_time = AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'],$row['local_timezone']);
                $response[$key]['id'] = $row['appointment_uuid'];
                $response[$key]['freelancer_uuid'] = $row['freelancer_uuid'];
                $response[$key]['title'] = 'Appointment ' . ucfirst($row['title']);
                $response[$key]['type'] = $row['type'];
                $response[$key]['status'] = $row['status'];
                $response[$key]['start'] = $row['appointment_date'].'T'.$from_time;
                $response[$key]['end'] = $row['appointment_date'].'T'.$to_time;
                switch ($row['type'])
                {
                    case 'appointment':
                        $color = '#36c6d3';
                        break;

                    case 'class':
                        $color = '#659be0';
                        break;

                    case 'session':
                        $color = '#ed6b75';
                        break;
                }
                $response[$key]['color'] = $color;
            }
        }
        return $response;
    }

    public static function makeBlocktimeCalendarArray($data = []) {
        $response = [];
        if(!empty($data))
        {
            foreach ($data as $key => $row)
            {
                $from_time = AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'],$row['local_timezone']);
                $to_time = AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'],$row['local_timezone']);
                $response[$key]['id'] = $row['blocked_time_uuid'];
                $response[$key]['title'] = 'Blocktime' . ucfirst($row['notes']);
                $response[$key]['type'] = 'blocktime';
                $response[$key]['status'] = '';
                $response[$key]['start'] = $row['start_date'].'T'.$from_time;
                $response[$key]['end'] = $row['end_date'].'T'.$to_time;
                $response[$key]['color'] = '#76818D';
            }
        }
        return $response;
    }

    public static function makeClassesCalendarArray($data = []) {
        $response = [];
        if(!empty($data))
        {
            foreach ($data[0]['schedule'] as $key => $row)
            {
                $from_time = AdminCommonHelper::convertTimeToTimezone($row['from_time'], $row['saved_timezone'],$row['local_timezone']);
                $to_time = AdminCommonHelper::convertTimeToTimezone($row['to_time'], $row['saved_timezone'],$row['local_timezone']);
                $response[$key]['id'] = $row['class_uuid'];
                $response[$key]['title'] = 'Class';
                $response[$key]['type'] = $row['schedule_type'];
                $response[$key]['status'] = '';
                $response[$key]['start'] = $row['class_date'].'T'.$from_time;
                $response[$key]['end'] = $row['class_date'].'T'.$to_time;
                // $response[$key]['start'] = $row['from_time'];
                // $response[$key]['end'] = $row['to_time'];
                $response[$key]['color'] = '#659be0';
            }
        }
        return $response;
    }

    public static function makeSubsArray($inputs = []) {
        $data = [];
        $data['freelancer_uuid'] = $inputs['freelancer_uuid'];
        $data['type'] = $inputs['type'];
        $data['price'] = $inputs['price'];
        $data['currency'] = $inputs['currency'];
        return $data;
    }

    public static function makeFreelancersSelectArr($all_freelancers)
    {
        $data = array();
        foreach ($all_freelancers as $key => $row)
        {
            $data[] = array(
                'freelancer_uuid' => $row['freelancer_uuid'],
                'freelancer_name' => $row['first_name'].' '.$row['last_name']
            );
        }
        return $data;
    }


}

?>
