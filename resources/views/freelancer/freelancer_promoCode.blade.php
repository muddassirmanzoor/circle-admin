<?php
$freelancer_promocodes = $data['freelancer_promocodes'];
?>
<div class="clearfix">
    <h4 class="block">
        <span class="pull-right">
            <a href="{{ route('addPromoCode', ['id' => $freelancer_uuid]) }}" class="btn btn-info">Add New Promo
                Code</a>
        </span>
        <span class="pull-right" style="margin-right:10px;">
            <a href="{{ route('sendPromoCodeForm', ['id' => $freelancer_uuid]) }}" class="btn btn-info">Send Promo
                Code</a>
        </span>
    </h4>
</div>
<br />
<table class="table table-striped table-bordered table-hover" id="promoCodeTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Code Title </th>
            <th> Validity Start Date</th>
            <th> Validity End Date</th>
            <th> Discount</th>
            <th> Amount</th>
            <th> Actions</th>
        </tr>
    </thead>
    <tbody>
        @if ($freelancer_promocodes)
            @foreach ($freelancer_promocodes as $promo_code)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $promo_code['id'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $promo_code['coupon_code'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $promo_code['valid_from'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $promo_code['valid_to'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $promo_code['discount_type'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $promo_code['coupon_amount'] }} </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-xs primary" data-uuid="{{ $promo_code['code_uuid'] }}"
                                type="button"> <a
                                    href="{{ route('editPromoCode', ['id' => $promo_code['code_uuid']]) }}">Edit</a>
                            </button>
                            <button class="btn btn-xs red deletePromoCode" data-uuid="{{ $promo_code['code_uuid'] }}"
                                type="button"> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="modal fade" id="static2" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Promo Code</h4>
            </div>
            <div class="modal-body">
                <p> Do you want to delete this Promo Code? </p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                <button type="button" data-dismiss="modal" class="btn green" id="yes_delete_promocode">Yes</button>
            </div>
        </div>
    </div>
</div>
<script src="{!! asset('public') !!}/assets/js/promo_code.js" type="text/javascript"></script>
<script>
    $(function() {
        $("#promoCodeTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Promo Codes found"
            }
        });
    })
</script>
