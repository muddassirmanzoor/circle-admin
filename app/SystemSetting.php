<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $primaryKey = 'id';
    protected $uuidFieldName = 'system_setting_uuid';
    public $timestamps = true;

    protected $fillable = ['system_setting_uuid', 'vat', 'transaction_charges', 'circl_fee', 'withdraw_scheduled_duration', 'is_active'];

    protected static function getSystemSettings() {
        $result = self::where('is_active', 1)->first();
        return !empty($result) ? $result->toArray() : [];
    }
}
