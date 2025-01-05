<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AdminCommonHelper;
use App\Helpers\AdminLoginHelper;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | LoginController Class
      |--------------------------------------------------------------------------
      |
      | This class will handle all main functions, which are not related to admin.
      |
     */

    /**
     * Method for admin login page.
     *
     * @param  'empty'
     * @return view data.
     */
    public function getLoginView() {
        try {
            if (Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin')) {
                return Redirect::route('dashboard');
            }
            return view('login');
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    /**
     * Method for admin login page.
     *
     * @param  'inputs'
     * @return view data.
     */
    public function adminLoginProcess(Request $request) {
        try {
            //error_log("Testing error log");
            $inputs = $request->all();
            if (isset($inputs['_token'])) {
                unset($inputs['_token']);
            }
            return AdminLoginHelper::adminLoginProcess($inputs);
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

    /**
     * Method for admin logout.
     *
     * @param  ''
     * @return view data.
     */
    public function adminLogout() {
        try {
            Auth::logout();
            Session::flush();
            return Redirect::route('adminLogin')->with('success_message', 'Logged out successfully!');
        } catch (\Exception $ex) {
            AdminCommonHelper::storeException($ex);
            return AdminCommonHelper::AdminExceptions($ex);
        }
    }

}
