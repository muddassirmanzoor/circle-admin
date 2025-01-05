<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

Class AdminLoginHelper {
    /*
      |--------------------------------------------------------------------------
      | AdminLoginHelper that contains all the methods for admin login
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use admin login processes
      |
     */

    public static $adminLoginRules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public static function adminLoginProcess($inputs) {
        $validator = Validator::make($inputs, self::$adminLoginRules);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->with('error_message', $validator->errors()->first());
        }
        $customer = \App\Customer::where('email', $inputs['email'])->first();
        $login = Auth::guard('customer')->attempt($inputs);
        if ($login) {

            return Redirect::route('dashboard')->with('success_message', 'Login successfully!');
        }
        return Redirect::back()->withInput()->with('error_message', "Invalid login details");
    }

}

?>
