<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostVideo extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'post_videos';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'post_video_uuid';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_video_uuid', 'post_uuid', 'folder_uuid', 'post_video', 'video_thumbnail', 'is_archive', 'created_at', 'updated_at'];

    /*
     * All model relations goes down here
     *
     */

    protected function saveNewPostVideo($data) {
        return PostVideo::create($data);
    }

    protected function saveMultiplePostVideo($data) {
        return PostVideo::insert($data);
    }

    protected function deletePostVideo($column, $value) {
        if (PostVideo::where($column, '=', $value)->exists()) {
            return PostVideo::where($column, '=', $value)->delete();
        }
        return true;
    }

}
