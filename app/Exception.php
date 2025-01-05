<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exceptions extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'exceptions';
    protected $primarykey = 'id';
    protected $uuidFieldName = 'exception_uuid';
    protected $fillable = ['exception_uuid', 'exception_file', 'exception_line', 'exception_message', 'exception_url', 'exception_code'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    protected $guarded = [];

    /**
     * The database functions goes here
     *
     * @var array
     */
    protected function saveException($data) {
        $exception = new Exceptions($data);
        if ($exception->save()) {
            return $exception;
        }
        return false;
    }

}
