<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Category;
use App\Helpers\CategoryHelper;
use App\Helpers\CategoryValidationHelper;
use App\Helpers\CommonHelper;
use App\Helpers\CommonMessageHelper;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\MessageHelper;

class CategoryController extends Controller {

    public function showAddCategoryForm() {
        return view('add_category');
    }

    public function addCategory(Request $request) {
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $sub_cat_arr = array();
            $validation = Validator::make($inputs, CategoryValidationHelper::addCategoryRules()['rules'], CategoryValidationHelper::addCategoryRules()['message_en']);
            if ($validation->fails()) {
                return redirect()->back()->with('error_message', $validation->errors()->first());
            }
            $cat_arr = array();
            $cat_arr['name'] = $request->input('category_name');
            $file = $request->file('category_picture');
            if (!empty($file)) {
                $file_size = $file->getSize();
                if($file_size > 1000000) {
                   return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['max_size_error']);
                }
                $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['category_image']);
                if (!$upload_image['success']) {
                    return redirect()->back()->with('error', 'Image could not be uploaded');
                }
                $cat_arr['image'] = !empty($upload_image['file_name']) ? $upload_image['file_name'] : null;
            }
            $cat_arr['is_archive'] = $request->input('category_status');
            $cat_arr['description'] = $request->input('description');
            $cat_arr['customer_description'] = $request->input('customer_description');
            $category = Category::create($cat_arr);
            if (!$category) {
                DB::rollBack();
                return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
            }
            DB::commit();
            //  return redirect('getAllCategories')->back()->with('success_message', CommonMessageHelper::getMessageData('success')['successful_request']);
            return redirect(route('getAllCategories'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public static function getAllCategories() {
        try {
//            $categories = array();
            $categories = CategoryHelper::getAllCategories(['is_archive' => 0]);
            return view('categories', compact('categories'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function deleteCategory(Request $request) {
        try {
            $inputs = $request->all();
            CategoryHelper::deleteCategory($inputs);
            return response()->json(['response' => 'success']);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function editCategory($id) {
        try {
            $category_data = CategoryHelper::editCategory($id);
            return view('edit_category', compact('category_data'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateCategory(Request $request) {
        try {
            $inputs = $request->except('_token');
            $sub_cat_arr = array();
            $validation = Validator::make($inputs, CategoryValidationHelper::updateCategoryRules()['rules'], CategoryValidationHelper::updateCategoryRules()['message_en']);
            if ($validation->fails()) {
                return redirect()->back()->with('error_message', $validation->errors()->first());
            }
            $cat_arr = array();
            $cat_arr['name'] = $request->input('category_name');
            $file = $request->file('category_picture');
            if (!empty($file)) {
                $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['category_image']);
                if (!$upload_image['success']) {
                    return redirect()->back()->with('error', 'Image could not be uploaded');
                }
                $cat_arr['image'] = !empty($upload_image['file_name']) ? $upload_image['file_name'] : null;
            }
            $cat_arr['is_archive'] = $request->input('category_status');
            $cat_arr['description'] = $request->input('description');
            $cat_arr['customer_description'] = $request->input('customer_description');
            $update = Category::updateCategorydataById($cat_arr, $request->input(['category_uuid']));
            if (!$update) {
                DB::rollBack();
                return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['update_error']);
            }
            DB::commit();
            return redirect()->route('getAllCategories')->with('success_message', CommonMessageHelper::getMessageData('success')['update_success']);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

}
