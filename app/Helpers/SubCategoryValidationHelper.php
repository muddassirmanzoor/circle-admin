<?php

namespace App\Helpers;

Class SubCategoryValidationHelper
{
    public static function addSubCategoryRules() {
        $validate['rules'] = [
            'category_id' => 'required',
            'sub_category_name' => 'required',
            'sub_category_status' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function englishMessages() {
        return [
            'category_id.required' => 'Category is missing',
            'sub_category_name.required' => 'Sub Category Name is Required',
            'sub_category_status.required' => 'Sub Category Status is Required'
        ];
    }

}

?>
