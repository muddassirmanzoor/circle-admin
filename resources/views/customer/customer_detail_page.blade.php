@extends('layouts.main')
@section('title') Customer Statistics @endsection
@section('content')
    <?php
        $customer_uuid = $data['customer_uuid'];
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Customer Statistics
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable tabbable-tabdrop">
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a href="#Detail" data-toggle="tab">Detail</a>
                            </li>
                            <li>
                                <a href="#customer_profile_image" data-toggle="tab">Change Profile Picture</a>
                            </li>
{{--                            <li>--}}
{{--                                <a href="#interest" data-toggle="tab">Interests</a>--}}
{{--                            </li>--}}
                            <li onclick="loadCustAppointCalendar()">
                                <a href="#appointments" data-toggle="tab">Appointments</a>
                            </li>
                            <li>
                                <a href="#classess" data-toggle="tab">Classes </a>
                            </li>
                            <li>
                                <!-- <a href="#sessions" data-toggle="tab">Sessions </a> -->
                            </li>
                            <li>
                                <a href="#subscriptions" data-toggle="tab">Subscriptions </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="Detail">
                                @include('customer.customer_detail')
                            </div>
                            <div class="tab-pane" id="customer_profile_image">
                                <form action="{{ route('updateCustomerPicture') }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <input type="hidden" name="customer_uuid" id="customer_uuid" value="{{ $customer_uuid }}">
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="">
                                                <img src="{{ $data['customer_detail']['profile_image'] }}" alt=""  />
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 400px;"> </div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new"> Select image </span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    <input type="file" name="customer_picture" id="customer_picture" value="" required>
                                                </span>
                                                <a href="javascript:void(0)" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                            </div>
                                        </div>
                                        <br/>
                                        <strong>Max Upload Size 1 MB</strong>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">Submit</button>
                                                        <a href="{{ route('getAllCustomers') }}" class="btn red">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
{{--                            <div class="tab-pane" id="interest">--}}
{{--                                @include('customer.customer_interest')--}}
{{--                            </div>--}}
                            <div class="tab-pane" id="appointments">
                                @include('customer.customer_appointments')
                            </div>
                            <div class="tab-pane" id="classess">
                                @include('customer.customer_classes')
                            </div>
                            {{-- <div class="tab-pane" id="sessions"> --}}
                                {{-- @include('customer.customer_sessions')--}}
                            {{-- </div> --}}
                            <div class="tab-pane" id="subscriptions">
                                @include('customer.customers_subscription')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
