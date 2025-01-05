<?php

namespace App\Http\Controllers;


class SubscriptionController extends Controller {

    public function showSubscriptionForm() {
        return view('add_subscription');
    }

    public function addSubCategory(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $sub_cat_arr = array();
            $validation = Validator::make($inputs, SubCategoryValidationHelper::addSubCategoryRules()['rules'], SubCategoryValidationHelper::addSubCategoryRules()['message_en']);
            if ($validation->fails()) {
                return redirect()->back()->with('error_message', $validation->errors()->first());
            }
            $sub_cat_arr['category_uuid'] = $inputs['category_uuid'];
            $sub_cat_arr['name'] = $inputs['sub_category_name'];
            $file = $request->file('sub_category_picture');
            if (!empty($file)) {
                $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['category_image']);
                if (!$upload_image['success']) {
                    return redirect()->back()->with('error', 'Image could not be uploaded');
                }
                $sub_cat_arr['image'] = !empty($upload_image['file_name']) ? $upload_image['file_name'] : null;
            }
            $sub_cat_arr['is_archive'] = $inputs['sub_category_status'];
            $sub_category = SubCategory::addSubCategory($sub_cat_arr);
            if (!$sub_category) {
                DB::rollBack();
                return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
            }
            DB::commit();
//            return redirect('getAllSubCategories')->back()->with('success_message', CommonMessageHelper::getMessageData('success')['successful_request']);
            return redirect(route('getAllSubCategories'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getAllSubCategories() {
        $sub_categories = SubCategory::getAllSubCategories();
        return view('sub_categories', compact('sub_categories'));
    }

    public function deleteSubCategory(Request $request) {
        SubCategory::updateSubCategorydataById(array('is_archive' => 1), $request->input(['sub_category_uuid']));
        return response()->json(['response' => 'success']);
    }

    public function editSubCategory($id) {
        $all_categories = Category::getAllCategories(array('is_archive' => 0));
        $sub_category_data = SubCategory::getSubCategorydataById($id);
        return view('edit_sub_category', compact('sub_category_data', 'all_categories'));
    }

    public function updateSubCategory(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $sub_cat_arr = array();
            $validation = Validator::make($inputs, SubCategoryValidationHelper::addSubCategoryRules()['rules'], SubCategoryValidationHelper::addSubCategoryRules()['message_en']);
            if ($validation->fails()) {
                return redirect()->back()->with('error_message', $validation->errors()->first());
            }
            $sub_cat_arr['category_uuid'] = $inputs['category_uuid'];
            $sub_cat_arr['name'] = $inputs['sub_category_name'];
            $file = $request->file('sub_category_picture');
            if (!empty($file)) {
                $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['category_image']);
                if (!$upload_image['success']) {
                    return redirect()->back()->with('error', 'Image could not be uploaded');
                }
                $sub_cat_arr['image'] = !empty($upload_image['file_name']) ? $upload_image['file_name'] : null;
            }
            $sub_cat_arr['is_archive'] = $inputs['sub_category_status'];
            $sub_category = SubCategory::updateSubCategorydataById($sub_cat_arr, $inputs['sub_category_uuid']);
            if (!$sub_category) {
                DB::rollBack();
                return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
            }
            DB::commit();
            return redirect()->route('getAllSubCategories')->with('success_message', CommonMessageHelper::getMessageData('success')['update_success']);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

}
