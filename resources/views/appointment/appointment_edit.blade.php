@extends('layouts.main')
@section('title') Edit Appointment @endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Edit Appointment Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if (\Session::has('message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('error_message') }} </span>
                        </div>
                    @endif
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
                    <form action="{{ route('updateAppointmentDetail') }}" id="appointmentDetailForm"
                        class="form-horizontal" method="post">
                        @csrf
                        <input type="hidden" name="appointment_uuid" id="appointment_uuid"
                            value="{{ request()->route('uuid') }}">
                        <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
                        <input type="hidden" name="local_timezone" id="local_timezone" value="{{ $local_timezone }}">
                        <input type="hidden" name="currency" id="currency" value="{{ $currency }}">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Customer</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="customer_id" id="customer_id" disabled>
                                        <option value=""> -- Select -- </option>
                                        @if (!empty($all_customers))
                                            @foreach ($all_customers as $key => $customer)
                                                <option value="{{ $customer['id'] }}"
                                                    {{ $customer['id'] == $customer_id ? 'selected' : '' }}>
                                                    {{ $customer['first_name'] . ' ' . $customer['last_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Service</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="sub_category_id" id="sub_category_id" disabled>
                                        <option value=""> -- Select -- </option>
                                        @if (!empty($freelancer_subcategories))
                                            @foreach ($freelancer_subcategories as $key => $sub_category)
                                                <option value="{{ $sub_category['sub_category_id'] }}"
                                                    {{ $sub_category['sub_category_id'] == $service_id ? 'selected' : '' }}>
                                                    {{ $sub_category['sub_category_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Date</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control date-picker" data-date-start-date="-0d"
                                        data-date-format="yyyy-mm-dd" name="date" id="date" placeholder="yyyy-mm-dd"
                                        required value="{{ $appointment_date }}" readonly>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Start Time</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker timepicker-24" name="from_time"
                                            id="from_time" value="{{ $from_time }}" readonly required>
                                        <span class="input-group-btn">
                                            <button class="btn default"
                                                type="buttonf19ee982-ef6a-4807-9f25-baba2d2b36fb">
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
                                        <input type="text" class="form-control timepicker timepicker-24" name="to_time"
                                            id="to_time" value="{{ $to_time }}" readonly required>
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i class="fa fa-clock-o"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php $timezoneArray = timezone_identifiers_list(); ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Timezone</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="local_timezone" id="local_timezone" disabled>
                                        <option value=""> -- Select -- </option>
                                        @if (!empty($timezoneArray))
                                            @foreach ($timezoneArray as $zone)
                                                <option value="{{ $zone }}"
                                                    {{ $local_timezone == $zone ? 'selected' : '' }}>{{ $zone }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-4">
                                        <select class="form-control" name="status" id="status" required="">
                                            <option value=""> -- Select -- </option>
                                            <option value="pending" >Pending</option>
                                            <option value="confirmed" >Confirmed</option>
                                            <option value="completed" >Completed</option>
                                            <option value="cancelled" >Cancelled</option>
                                            <option value="rejected" >Rejected</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div> -->
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button disabled type="submit" class="btn green">Submit</button>
                                        <a href="{{ route('getPendingAppointments') }}" class="btn red">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $.ajax({
                url: siteUrl + 'getAppointmentDetail',
                type: 'post',
                data: {
                    appointment_uuid: $("#appointment_uuid").val()
                },
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $("#customer_id").val(data['customer_id']);
                        $("#sub_category_id").val(data['service_id']);
                        $("#date").val(data['appointment_date']);
                        $("#start_time").val(data['start_time']);
                        $("#end_time").val(data['end_time']);
                        $("#status").val(data['status']);
                    }
                }
            })
        })
    </script>
@endsection
