<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'post_images';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'post_image_uuid';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_image_uuid', 'post_uuid', 'folder_uuid', 'post_image', 'is_archive', 'created_at', 'updated_at'];

    /*
     * All model relations goes down here
     *
     */

    protected function saveNewPostImage($data) {
        return PostImage::create($data);
    }

    protected function saveMultiplePostImage($data) {
        return PostImage::insert($data);
    }

    protected function updatePostImage($column, $value, $data) {
        return PostImage::where($column, '=', $value)->update($data);
    }

    protected function deletePostImage($column, $value) {
        if (PostImage::where($column, '=', $value)->exists()) {
            return PostImage::where($column, '=', $value)->delete();
        }
        return true;
    }

    protected function updatePostImagedataById($data, $id)
    {
        return PostImage::where('post_uuid', '=', $id)->update($data);
    }

}
