@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> {{ $title }} Promo Code List</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="promoCode">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> Coupon Title</th>
                            <th> Validity Start Date</th>
                            <th> Validity End Date</th>
                            <th> Discount</th>
                            <th> Amount</th>
                            <th> Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($promoCodes)
                            @foreach($promoCodes as $promoCode)
                                <tr>
                                    <td> {{ $promoCode['id'] }} </td>
                                    <td>{{ $promoCode['coupon_code'] }}</td>
                                    <td> {{ $promoCode['valid_from'] }} </td>
                                    <td> {{ $promoCode['valid_to'] }} </td>
                                    <td> {{ $promoCode['discount_type'] }} </td>
                                    <td> {{ $promoCode['coupon_amount'] }} </td>                
                                    <td width="12%">
                                        <div class="btn-group">
                                            <button class="btn btn-xs blue "
                                                    data-uuid="{{ $promoCode['code_uuid'] }}" type="button"> <a
                                            href="{{ route('editPromoCode', ['id' => $promoCode['code_uuid']]) }}">Edit</a>
                                            </button>
                                            <button class="btn btn-xs red deletePromoCode"
                                                    data-uuid="{{ $promoCode['code_uuid'] }}" type="button"> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="odd">
                                <td valign="top" colspan="9" class="dataTables_empty">No records found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
        $(function () {
            $("#promoCode").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>
@endsection
