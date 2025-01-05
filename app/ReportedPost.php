<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedPost extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'reported_posts';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'reported_post_uuid';
    public $timestamps = true;
    protected $fillable = [
        'reported_post_uuid',
        'reporter_id',
        'reported_type',
        'comments',
        'post_id',
        'is_archive'
    ];

    public function ReportedPost() {
        return $this->hasOne('\App\Post', 'id', 'post_id');
    }

    public function Freelancer() {
        return $this->hasOne('\App\Freelancer', 'id', 'reporter_id')->where('is_archive',0 );
    }

    public function Customer() {
        return $this->hasOne('\App\Customer', 'id', 'reporter_id');
    }
    protected static function getReportedPost() {
        $result = ReportedPost::where(['is_archive' => 0])
        ->with('ReportedPost')
        ->with('Freelancer')
        ->with('Customer')
        ->orderBy('id', 'desc')
        ->get();
        return !empty($result) ? $result->toArray() : [];
    }

}
