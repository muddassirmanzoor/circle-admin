@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Payment Request Detail </span>
                    </div>
                    <a href="{{ route('getAllPaymentRequests') }}" class="pull-right" style="margin-top: 7px;"> back to list </a>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Freelancer Info</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">First Name:</label>
                                    <div class="col-md-9">
                                        <strong> <a href="{{route('freelancerDetailPage', $data['pay_req']['freelancer']['freelancer_uuid'])}}">{{$data['pay_req']['freelancer']['first_name'] ?? ''}}</a></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Last Name:</label>
                                    <div class="col-md-9">
                                        <strong><a href="{{route('freelancerDetailPage', $data['pay_req']['freelancer']['freelancer_uuid'])}}">{{$data['pay_req']['freelancer']['last_name'] ?? ''}} </a></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Profession:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['profession'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Email:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['email'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Total Earnings:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['total_earnings'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['freelancer']['default_currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Total withdrwal:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['total_withdrwal'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['freelancer']['default_currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Available Amount:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['available_balance'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['freelancer']['default_currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Pending Amount:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['pending_balance'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['freelancer']['default_currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">IBAN:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['freelancer']['bank_detail']['iban_account_number'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Payment Request</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Req Amount:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['requested_amount'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Deductions:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['deductions'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Final Amount:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['final_amount'] ?? ''}}</strong>
                                        <small>{{$data['pay_req']['currency'] ?? ''}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Date:</label>
                                    <div class="col-md-9">
                                        <strong>{{$data['pay_req']['date'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Status:</label>
                                    <div class="col-md-9">

                                            @if($data['pay_req']['status'] == 1)
                                                <strong style="color: blue"> processed </strong>
                                            @elseif($data['pay_req']['status'] == 2)
                                                <strong style="color: green"> transferred</strong>
                                            @elseif($data['pay_req']['status'] == 3)
                                                <strong style="color: red"> rejected </strong>
                                            @else
                                                <strong style="color: orange"> pending </strong>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Payment Dues</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover" id="payment_request_table">
                                    <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Type </th>
                                        <th> Sar Amount</th>
                                        <th> Pound Amount</th>
                                        <th> Due Date </th>
                                        {{--<th> Status </th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($data['pay_dues']))
                                        @foreach($data['pay_dues'] as $ind => $pay_due)
                                            <tr>
                                                <td>{{$pay_due['no']}}</td>
                                                <td>
                                                    @if($pay_due['freelancer_transaction']['transaction_type'] == 'appointment_bookoing')
                                                        Appointment Booking
                                                    @elseif($pay_due['freelancer_transaction']['transaction_type'] == 'class_booking')
                                                        Class Booking
                                                    @elseif($pay_due['freelancer_transaction']['transaction_type'] == 'subscription')
                                                        Subscription
                                                    @endif
                                                </td>
                                                <td>{{$pay_due['sar_amount'] ?? 0}}</td>
                                                <td>{{$pay_due['pound_amount'] ?? 0}}</td>
                                                <td>{{$pay_due['due_date']}}</td>
                                                {{--<td>{{$pay_due['status']}}</td>--}}
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="5">No Record Found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @if($data['pay_req']['status'] == 0)
                                    <button href="javascript:void(0)" class="btn green pull-right" id="approve-payment-request" data-requuid="{{$data['pay_req']['req_uuid']}}" >Approve</button>
                                    <button href="javascript:void(0)" class="btn red pull-right margin-right-10" id="cancel-payment-request" data-requuid="{{$data['pay_req']['req_uuid']}}" >Cancel</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
