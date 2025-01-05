<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    protected $uuidFieldName = 'bank_detail_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'freelancer_uuid',
        'account_name',
        'account_number',
        'iban_account_number',
        'bank_name',
        'swift_code'
    ];

}
