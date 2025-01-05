<?php

namespace App\Helpers;

Class CategoryValidationHelper
{
    public static function addCategoryRules() {
        $validate['rules'] = [
            'category_name' => 'required',
            'category_status' => 'required',
            'customer_description' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function updateCategoryRules() {
        $validate['rules'] = [
            'category_uuid' => 'required',
            'category_name' => 'required',
            'category_status' => 'required',
            'customer_description' => 'required',
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function englishMessages() {
        return [
            'category_uuid.required' => 'Category Id is missing',
            'category_name.required' => 'Category Name is Required',
            'category_status.required' => 'Category Status is Required'
        ];
    }

}

?>
