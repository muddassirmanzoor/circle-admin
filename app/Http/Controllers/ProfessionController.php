<?php

namespace App\Http\Controllers;

use App\Helpers\ProfessionHelper;
use App\Helpers\CommonMessageHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Profession;


class ProfessionController extends Controller
{
    public function getAllProfessions() {
    	try {
        	$professions = ProfessionHelper::getAllProfessions();
        	return view('profession', compact('professions'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function saveProfession(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return ProfessionHelper::saveProfession($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
                return CommonHelper::CommonExceptions($ex);
        }
    }

    public function editProfession($id) {

        $profession = Profession::getProfessionDataById('profession_uuid',$id);
        return view('edit_profession', compact('profession'));
    } 

    public function updateProfession(Request $request) {
        try{
            DB::beginTransaction();
            $inputs = $request->except('_token');
            return ProfessionHelper::updateProfession($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function deleteProfession(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            return ProfessionHelper::deleteProfession($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
                return CommonHelper::CommonExceptions($ex);
        }
    }
}
