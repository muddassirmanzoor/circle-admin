@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Transaction Detail </span>
                    </div>
                </div>
                @if(strtolower($data['purchase_type']) == 'subscription')
                    <div class="portlet-body form">
                        <div class="form-body">
                            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Subscription Due Info</h3>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Subscription Type:</label>
                                        <div class="col-md-8">
                                            <strong>{{$data['subscription_type'] ?? ''}}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Date</label>
                                        <div class="col-md-8">
                                            <strong>{{$data['due_date'] ?? ''}}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Amount:</label>
                                        <div class="col-md-8">
                                            <strong>{{$data['due_amount'] ?? ''}}</strong>
                                            <small>{{$data['currency'] ?? ''}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="portlet-body form">
                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Payment Info</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Amount Paid</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['service_fee'] ?? 0}}</strong>
                                        <small>{{$data['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Travel Fee:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['travel_fee'] ?? 0}}</strong>
                                        <small>{{$data['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Circle Fee</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['circl_charges'] ?? 0}}</strong>
                                        <small>{{$data['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Transaction Fee:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['hyperpay_fee'] ?? 0}}</strong>
                                        <small>{{$data['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Amount Earned:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['amount'] ?? 0}}</strong>
                                        <small>{{$data['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Booking Details</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Transaction Id:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['transaction_id'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Purchase Date/Time:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['purchase_date'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['transaction_status'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Purchase Type:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['purchase_type'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Purchase Details:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['purchase_detail'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Booking:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['booking_name'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Customer</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['customer'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Type:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['type'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Date/Time</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['booking_date'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Payment By:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['payment_by'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Location:</label>
                                    <div class="col-md-8">
                                        <strong>{{$data['location'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
