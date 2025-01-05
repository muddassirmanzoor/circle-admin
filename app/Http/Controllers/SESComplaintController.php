<?php


namespace App\Http\Controllers;


use App\SESBounce;
use App\SESComplaint;
use Illuminate\Http\Request;

class SESComplaintController extends Controller{

    public function index(Request $request){
        $complaints = SESComplaint::orderBy('created_at', 'DESC')->where('is_archive', '=', '0')->paginate(10);

        return view('ses.complaint.index', compact('complaints'));
    }
}
