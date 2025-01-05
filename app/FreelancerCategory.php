<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreelancerCategory extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'freelancer_categories';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'freelancer_category_uuid';

    public $timestamps = true;

    protected $fillable = ['freelancer_category_uuid', 'freelancer_id', 'category_id', 'sub_category_id', 'name', 'price', 'duration', 'is_archive'];

    public function Category()
    {
        return $this->hasOne('\App\Category', 'id', 'category_id');
    }

    public function SubCategory()
    {
        return $this->hasOne('\App\SubCategory', 'id', 'sub_category_id');
    }

    protected  function getFreelancerCategory($freelancer_uuid)
    {
        $freelancer_cats = array();
        if (!empty($freelancer_uuid)) {
            $freelancer_cats = self::select('categories.category_uuid as category_uuid', 'categories.name as category_name')
                ->groupBy('category_name', 'category_uuid')
                ->leftjoin('categories', 'categories.id', '=', 'freelancer_categories.category_id')
                ->where('freelancer_uuid', '=', $freelancer_uuid)
                //            ->orderBy('freelancer_categories.id', 'desc')
                ->get();
        }
        return !empty($freelancer_cats) ? $freelancer_cats->toArray() : [];
    }

    protected  function getFreelancerSubCategory($freelancer_uuid)
    {
        $freelancer_sub_cats = array();
        if (!empty($freelancer_uuid)) {
            $freelancer_sub_cats = self::select('freelancer_categories.sub_category_uuid as sub_category_uuid', 'sub_categories.name as sub_category_name', 'freelancer_created_sub_categories.name as created_sub_category_name', 'categories.name as category_name')
                ->join('categories', 'categories.category_uuid', '=', 'freelancer_categories.category_uuid')
                ->leftjoin('sub_categories', 'sub_categories.id', '=', 'freelancer_categories.sub_category_id')
                ->leftjoin('freelancer_created_sub_categories', 'freelancer_created_sub_categories.freelancer_created_sub_uuid', '=', 'freelancer_categories.sub_category_id')
                ->where('freelancer_categories.freelancer_uuid', '=', $freelancer_uuid)->orderBy('freelancer_categories.id', 'desc')->get();
        }
        return !empty($freelancer_sub_cats) ? $freelancer_sub_cats->toArray() : [];
    }

    protected  function getFreelancerServices($freelancer_id)
    {
        $freelancer_sub_cats = array();
        if (!empty($freelancer_id)) {
            $freelancer_sub_cats = self::with('Category', 'SubCategory')
                ->where('freelancer_id', '=', $freelancer_id)->orderBy('id', 'desc')->get();
        }
        return !empty($freelancer_sub_cats) ? $freelancer_sub_cats->toArray() : [];
    }
}
