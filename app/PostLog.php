<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLog extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'post_logs';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'post_log_uuid';
    public $timestamps = true;
    protected $fillable = [
        'post_log_uuid',
        'profile_log_uuid',
        'comments',
        'is_archive'
    ];

    protected function createReaosn($data)
    {
        return self::create($data);
    }

}
