<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'locations';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'location_uuid';
    public $timestamps = true;
    protected $fillable = [
        'location_uuid',
        'address',
        'route',
        'street_number',
        'city',
        'state',
        'country',
        'country_code',
        'zip_code',
        'place_id',
        'lat',
        'lng',
        'is_archive'
    ];

}
