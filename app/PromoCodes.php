<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCodes extends Model {

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'code_uuid';
    public $timestamps = true;
    protected $fillable = [
        'code_uuid',
        'freelancer_uuid',
        'coupon_code',
        'valid_from',
        'valid_to',
        'discount_type',
        'coupon_amount',
        'is_archive'
    ];

    protected function savePromoCode($data) {
        $result = PromoCodes::create($data);
        return ($result) ? $result : [];
    }

    protected function getActivePromoCodelist($freelancer_uuid) {
        $result = PromoCodes::where('freelancer_uuid', $freelancer_uuid)->where('valid_to', '>=', date('Y-m-d'))->where('is_archive', 0)->get();
        return ($result) ? $result->toArray() : [];
    }

    protected function getExpiredPromoCodelist($freelancer_uuid) {
        $result = PromoCodes::where('freelancer_uuid', $freelancer_uuid)->where('valid_to', '<=', date('Y-m-d'))->where('is_archive', 0)->get();
        return ($result) ? $result->toArray() : [];
    }

}
