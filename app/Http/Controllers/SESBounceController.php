<?php


namespace App\Http\Controllers;


use App\SESBounce;
use Illuminate\Http\Request;

class SESBounceController extends Controller{

    public function index(Request $request){
        $bounces = SESBounce::orderBy('created_at', 'DESC')->where('is_archive', '=', '0')->paginate(10);

        return view('ses.bounce.index', compact('bounces'));
    }
}
