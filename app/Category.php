<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'category_uuid';

    public $timestamps = true;

    protected $fillable = ['category_uuid', 'name', 'image', 'description', 'customer_description', 'is_archive'];

    protected static function getAllCategories($where = [])
    {
        $categories = array();
        if (!empty($where)) {
            $categories = self::where($where)->orderBy('id', 'desc')->get();
        } else {
            $categories = self::orderBy('id', 'desc')->get();
        }
        return !empty($categories) ? $categories : '';
    }

    protected static function getCategorydataById($id)
    {
        $data = array();
        $data = self::where('category_uuid', '=', $id)->first();
        return !empty($data) ? $data : '';
    }

    protected static function updateCategorydataById($data, $id)
    {
        return self::where('category_uuid', '=', $id)->update($data);
    }
}
