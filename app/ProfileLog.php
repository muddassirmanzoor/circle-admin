<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileLog extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'profile_logs';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'profile_logs_uuid';

    public $timestamps = true;

    protected $fillable = ['profile_logs_uuid', 'freelancer_uuid', 'reason'];

    protected static function createReaosn($data)
    {
        return self::create($data);
    }
}
