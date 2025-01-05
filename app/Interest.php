<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'interests';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'interest_uuid';
    public $timestamps = true;

    protected $fillable = ['interest_uuid','customer_id','category_id','is_archive'];

    public function category(){
        return $this->hasOne('\App\Category', 'id', 'category_id')->where('is_archive', 0);
    }

    public static function getCustomerIntrusts($column, $value){
        $result = self::with('category')->where($column, $value)->get();
        return !empty($result) ? $result->toArray() : [];
    }
}
