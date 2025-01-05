<?php

namespace App\Helpers;

use App\Appointment;
use App\Freelancer;
use App\Customer;
use App\Helpers\CommonHelper;
use App\Helpers\MessageHelper;


class GraphHelper {

	public static function getAppointmentsCurrentMonth($status){
		$all_appointments = Appointment::getAppointmentsCurrentMonth($status);
		return self::setAppontmentFields($all_appointments);
	}
	
	public static function setAppontmentFields($appointments){
		$appointData = [];
		$data = [];
		if(!empty($appointments)){
			for($i = 1; $i <= date('t'); $i++){		
				foreach($appointments as $key => $appointment){
					$day = ($i < 10) ? '0'.$i : $i;
					if($appointment['appointment_date'] == date('Y-m-'.$day)){
						$appointData[$i][$i] = $i;
						$appointData[$i][$appointment['status']] = $appointment['status_count'];
					}
				}
			}
			$set_appointments = array_values($appointData);
			foreach($set_appointments as $key => $s_apt){
				$data[$key][0] = $key + 1;
				$data[$key][1] = isset($s_apt['confirmed']) ? $s_apt['confirmed'] : 0;
				$data[$key][2] = isset($s_apt['cancelled']) ? $s_apt['cancelled'] : 0;
				$data[$key][3] = isset($s_apt['pending']) ? $s_apt['pending'] : 0;
			}
		}
		return response()->json($data);
	}

	public static function getNewFreelancerGraphs(){
		$result = Freelancer::getNewCurrentMonthFreelancer();
		$result = self::setDataInWeeks($result);
		if($result)
			return response()->json($result);
		return 'no record founds.';
	}

	public static function getNewCustomerGraphs(){
		$result = Customer::getNewCurrentMonthCustomer();
		$result = self::setDataInWeeks($result, 'customer');
		if($result)
			return response()->json($result);
		return 'no record founds.';
	}

	public static function setDataInWeeks($result, $type='freelancer'){
		$dataArray = [];
		$orignalArray = [];
		$data = [];
		if(!empty($result)){
			foreach($result as $res){
				$noOfWeeak = \Carbon\Carbon::parse($res->created_at)->weekOfMonth;
				$data[$noOfWeeak][] = $res->toArray();
			}
			$dataArray = array_values($data);
			foreach($dataArray as $key => $d){
				$arraySet['noOfWeaks'] = 'week '.($key + 1);
				$arraySet[$type] = count($dataArray[$key]);
				$arraySet['expenses'] = 122;
				$orignalArray[$key] = $arraySet;
			}
		}
		return $orignalArray;
	}

}