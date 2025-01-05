<?php

namespace App\Helpers;

use App\Category;

class CategoryHelper
{
    public static function getAllCategories($where) {
        $categories = Category::getAllCategories($where);
        return $categories;
    }

    public static function editCategory($id) {
        $category_data = Category::getCategorydataById($id);
        return $category_data;
    }

    public static function deleteCategory($inputs = []) {
        Category::updateCategorydataById(array('is_archive' => 1), $inputs['category_uuid']);
        return response()->json(['response' => 'success']);
    }

}
