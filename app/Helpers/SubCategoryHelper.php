<?php

namespace App\Helpers;


use App\SubCategory;
use Illuminate\Support\Facades\Validator;

class SubCategoryHelper
{

    public static function getAllSubcategories()
    {
        return SubCategory::getAllSubcategories();
    }

}
