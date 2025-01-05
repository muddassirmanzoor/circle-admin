<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CronJobLog extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'cron_job_logs';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'cron_job_log_uuid';
    public $timestamps = true;
    protected $fillable = [
        'cron_job_log_uuid',
        'name',
        'message',
        'success',
        'is_archive',
    ];

    public function getSuccessHTML(){
        return $this->success == 1 ? '<span style="color:green;">Success</span>' : '<span style="color:#e12330;">Failed</span>';
    }
}
