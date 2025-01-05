<?php

namespace App\Helpers;

Class PromoCodeDataHelper {

    public static function makeUpdatePromoCodeArray($inputs = []) {
        $data = [];
        $data['freelancer_uuid'] = $inputs['freelancer_uuid'];
        $data['coupon_code'] = $inputs['coupon_code'];
        $data['valid_from'] = $inputs['valid_from'];
        $data['valid_to'] = $inputs['valid_to'];
        $data['discount_type'] = $inputs['discount_type'];
        $data['coupon_amount'] = $inputs['coupon_amount'];
        return $data;
    }


}

?>
