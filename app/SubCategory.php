<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'sub_categories';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'sub_category_uuid';

    public $timestamps = true;

    protected $fillable = ['sub_category_uuid', 'category_id', 'name', 'image', 'is_online', 'is_archive',];

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    protected function getAllSubCategories($where = '')
    {
        $sub_categories = array();
        if (!empty($where)) {
            $sub_categories = self::with('category')->where($where)->orderBy('id', 'desc')->get();
        } else {
            $sub_categories = self::with('category')
                ->orderBy('sub_categories.id', 'desc')->get();
        }
        return !empty($sub_categories) ? $sub_categories : '';
    }

    protected function getSubCategorydataById($id)
    {
        $data = array();
        $data = self::where('sub_category_uuid', '=', $id)->first();
        return !empty($data) ? $data : '';
    }

    protected function addSubCategory($data)
    {
        return self::create($data);
    }

    protected function updateSubCategorydataById($data, $id)
    {
        return self::where('sub_category_uuid', '=', $id)->update($data);
    }
}
