<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GraphHelper;

class GraphController extends Controller
{
    public function getAppointmentsCurrentMonth(){
    	$status = ['pending', 'confirmed', 'cancelled'];
    	return GraphHelper::getAppointmentsCurrentMonth($status);
    }

    public function getNewFreelancerGraphs(){
		return GraphHelper::getNewFreelancerGraphs();
	}

	public function getNewCustomerGraphs(){
		return GraphHelper::getNewCustomerGraphs();
	}
}
