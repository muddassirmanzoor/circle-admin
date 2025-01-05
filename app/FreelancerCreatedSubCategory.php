<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreelancerCreatedSubCategory extends Model
{
    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'freelancer_created_sub_categories';

    protected $primaryKey = 'id';

    protected $uuidFieldName = 'freelancer_created_sub_uuid';

    public $timestamps = true;

    protected $fillable = [
        'freelancer_created_sub_uuid',
        'freelancer_uuid',
        'name',
        'is_archive'
    ];

}
