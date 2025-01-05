@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Add New Promo Code
                    </div>

                </div>
                <div class="portlet-body form">
                    @if (\Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if (\Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('error_message') }} </span>
                        </div>
                    @endif
                    <form action="{{ route('createNewPromoCode') }}" id="" class="form-horizontal" method="post">
                        @csrf
                        <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
                        <div class="form-body">
                            <!-- <div class="form-group">
                                    <label class="control-label col-md-3">Freelancer</label>
                                    <div class="col-md-4">
                                        <select class="form-control freelancer_search" multiple="multiple"></select>

                                    </div>
                                </div> -->
                            <div class="form-group">
                                <label class="control-label col-md-3">Promo Code</label>
                                <div class="col-md-4">
                                    <input type="text" name="coupon_code" class="form-control"
                                        placeholder="Enter Promo Code Title" value="">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Validitiy Start Date</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control date-picker" data-date-start-date="-0d"
                                        data-date-format="yyyy-mm-dd" name="valid_from" id="date" placeholder="yyyy-mm-dd"
                                        required value="" readonly>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Validitiy End Date</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control date-picker" data-date-start-date="-0d"
                                        data-date-format="yyyy-mm-dd" name="valid_to" id="date" placeholder="yyyy-mm-dd"
                                        required value="" readonly>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Discount</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="discount_type" id="discount_type" required>
                                        <option value=""> -- Select -- </option>
                                        <option value="percentage">Percentage</option>
                                        <option value="amount">Amount</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Amount</label>
                                <div class="col-md-4">
                                    <input type="text" name="coupon_amount" class="form-control"
                                        placeholder="Enter amount" value="">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{!! asset('public') !!}/assets/js/promo_code.js" type="text/javascript"></script>
@endsection
