<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'post_uuid';
    public $timestamps = true;
    protected $fillable = [
        'post_uuid',
        'profile_uuid',
        'folder_uuid',
        'caption',
        'text',
        'media_type',
        'post_type',
        'status',
        'url',
        'local_path',
        'is_featured',
        'is_blocked',
        'is_archive'
    ];

    public function image() {
        return $this->hasOne('App\PostImage', 'post_uuid', 'post_uuid')->where('is_archive', '=', 0);
    }

    public function video() {
        return $this->hasOne('App\PostVideo', 'post_uuid', 'post_uuid')->where('is_archive', '=', 0);
    }

    public function ReportedPost() {
        return $this->hasOne('\App\ReportedPost', 'post_id', 'id');
    }

    protected function updatePostData($column, $value, $data)
    {
        return Post::where($column, '=', $value)->update($data);
    }

    protected function getPostDetail($column, $value) {
        $query = Post::where($column, '=', $value)
                ->where('is_archive', '=', 0);
        // $query = $query->with('freelancer');
        $query = $query->with('image');
        $query = $query->with('video');

        $result = $query->first();
        return (!empty($result)) ? $result->toArray() : [];
    }

    protected function getBlockedPostDetail($column, $value) {
        $query = Post::where($column, '=', $value)
                ->where('is_archive', '=', 1)
                ->where('is_blocked', '=', 1);
        // $query = $query->with('freelancer');
        $query = $query->with('image');
        $query = $query->with('video');

        $result = $query->first();
        return (!empty($result)) ? $result->toArray() : [];
    }

    protected function getBlockedPosts($where)
    {
        $result =  Post::where($where)->with('ReportedPost')->orderBy('id', 'desc')->get();
        return !empty($result) ? $result->toArray() : [];
    }

}
