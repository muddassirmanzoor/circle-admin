<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{

    use \BinaryCabin\LaravelUUID\Traits\HasUUID;

    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    protected $uuidFieldName = 'code_uuid';
    public $timestamps = true;
    protected $fillable = ['code_uuid', 'freelancer_id', 'coupon_code', 'valid_from', 'valid_to', 'discount_type', 'coupon_amount', 'is_archive'];

    protected function getActivePromoCodelist()
    {
        $query = PromoCode::where('valid_to', '>=', date('Y-m-d'))->where('is_archive', 0);
        $result = $query->get();
        return ($result) ? $result->toArray() : [];
    }

    protected function getExpiredPromoCodelist()
    {
        $query = PromoCode::where('valid_to', '<', date('Y-m-d'))->where('is_archive', 0);
        $result = $query->get();
        return ($result) ? $result->toArray() : [];
    }

    protected  function updatePromoCodedata($data, $id)
    {
        self::where('code_uuid', '=', $id)->update($data);
    }

    protected function getFreelancerPromoCodes($column, $value)
    {
        $result = PromoCode::where($column, '=', $value)
            ->where('valid_to', '>=', date('Y-m-d'))
            ->where('is_archive', 0)
            ->get();
        return !empty($result) ? $result->toArray() : [];
    }
}
