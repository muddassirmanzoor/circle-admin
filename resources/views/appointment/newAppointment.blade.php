@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-th-list"></i>Add New Appointment
                </div>
                <div class="actions">
                    <a href="{{ route('freelancerDetailPage', ['id' => request()->route('id')]) }}" class="btn green btn-outline">
                        <i class="fa fa-repeat"></i> Back </a>
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
                <form action="{{ route('createNewAppointment') }}" id="freelancerAppointmentForm" class="form-horizontal" method="post">
                    @csrf
                    <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ request()->route('id') }}">
                    <input type="hidden" name="currency" id="currency" value="{{ $freelancer_detail['currency'] }}">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Select Customer</label>
                            <div class="col-md-4">
                                <select class="form-control" name="customer_uuid" id="customer_uuid" required>
                                    <option value=""> -- Select -- </option>
                                    @if(!empty($all_customers))
                                    @foreach($all_customers as $key => $customer)
                                    <option value="{{ $customer['customer_uuid'] }}">{{ $customer['first_name'].' '.$customer['last_name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Select Service</label>
                            <div class="col-md-4">
                                <select class="form-control" name="service_uuid" id="sub_category_uuid" required>
                                    <option value=""> -- Select -- </option>
                                    @if(!empty($freelancer_subcategories))
                                    @foreach($freelancer_subcategories as $key => $sub_category)
                                    <option value="{{ $sub_category['sub_category_uuid'] }}">{{ $sub_category['sub_category_name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Appointment Title</label>
                            <div class="col-md-4">
                                <input class="form-control" name="title" id="title" type="text" value="{{ old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Appointment Location</label>
                            <input type="hidden" id="lat" name="lat" value="" >
                            <input type="hidden" id="lng" name="lng" value="" >
                            <input type="hidden" id="route" name="route" value="" >
                            <input type="hidden" id="place_id" name="location_id" value="" >
                            <input type="hidden" id="country" name="country" value="" >
                            <input type="hidden" id="street_number" name="street_number" value="" >
                            <input type="hidden" id="administrative_area_level_1" name="state" value="" >
                            <input type="hidden" id="locality" name="city" value="" >
                            <input type="hidden" id="postal_code" name="zip_code" value="" >
                            <div class="col-md-4">
                                <input type="text" id="autocomplete" name="address" class="form-control" placeholder="enter location" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Select Date</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control date-picker" data-date-start-date="-0d" data-date-format="yyyy-mm-dd" name="date" id="date" placeholder="yyyy-mm-dd" required value="" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Start Time</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker timepicker-24" name="start_time" id="start_time"  readonly required>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-clock-o"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">End Time</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker timepicker-24" name="end_time" id="end_time" readonly required>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-clock-o"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $timezoneArray = timezone_identifiers_list();
                        ?>
                        <div class="form-group">
                            <label class="control-label col-md-3">Select Timezone</label>
                            <div class="col-md-4">
                                <select class="form-control" name="local_timezone" id="local_timezone" required>
                                    <option value=""> -- Select -- </option>
                                    @if(!empty($timezoneArray))
                                    @foreach($timezoneArray as $zone)
                                    <option value="{{ $zone }}">{{ $zone }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Notes</label>
                            <div class="col-md-4">
                                <textarea class="form-control" rows="4" name="notes" id="notes"></textarea>
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
@endsection
