@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Send Promo Code
                    </div>
                    
                </div>
                <div class="portlet-body form">
                    @if( \Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if( \Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('error_message') }} </span>
                        </div>
                    @endif
                    <form action="{{ route('sendPromoCodes') }}" id="" class="form-horizontal" method="post">
                        @csrf
                        <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
                        <input type="hidden" name="code_uuid" id="code_uuid" value="">
                        <input type="hidden" name="coupon_code" id="coupon_code" value="">
                        <input type="hidden" name="customer_uuid" id="customer_uuid" value="">

                        <div class="form-body">
                            <!-- <div class="form-group">
                                <label class="control-label col-md-3">Freelancer</label>
                                <div class="col-md-4">
                                    <select class="form-control freelancer_search" multiple="multiple"></select>
                                   
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="control-label col-md-3">Promo Code Title</label>
                                <div class="col-md-4">
                                    <select class="form-control code_search" multiple="multiple"></select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Clients</label>
                                <div class="col-md-4">
                                    <select class="form-control client_search" multiple="multiple"></select>
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
