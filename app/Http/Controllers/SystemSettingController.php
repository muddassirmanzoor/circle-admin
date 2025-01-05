<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index() {
    	try {
        	$setting = SystemSetting::where('is_active', 1)->first();
            return view('system-settings.index', compact('setting'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }
}
