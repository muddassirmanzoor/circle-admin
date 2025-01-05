@extends('layouts.main')
@section('title')
    {{ ucfirst($status) }} Appointments
@endsection
@php
$appointment_status = config('arrays.appointment_type.' . $status);
@endphp
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">{{ ucfirst($status) }} Appointments</span>
                    </div>
                </div>
                <div class="portlet-body">
                    @if (Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if (Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('error_message') }} </span>
                        </div>
                    @endif
                    <table class="table table-striped table-bordered table-hover" id="appointments">
                        <thead>
                            <tr>
                                <th> #</th>
                                <th> Title</th>
                                <th> Booked At</th>
                                <th> Customer Name</th>
                                <th> Freelancer Name</th>
                                <th> Services</th>
                                <!-- <th> Price</th> -->
                                <th> Booking Date Time</th>
                                <!--<th> Status</th>-->
                                <th> Change Status</th>
                                <th> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($appointments)
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td style="vertical-align: inherit;"> {{ $loop->index + 1 }} </td>
                                        <td style="vertical-align: inherit;">
                                            <a
                                                href="{{ route('getAppointment', ['uuid' => $appointment['appointment_uuid']]) }}">
                                                {{ $appointment['appointment_title'] ?? '' }}
                                            </a>
                                        </td>
                                        <td style="vertical-align: inherit;"> {{ $appointment['appointment_date'] }} </td>
                                        <td style="vertical-align: inherit;"> {{ $appointment['appointment_customer'] }}
                                        </td>
                                        <td style="vertical-align: inherit;"> {{ $appointment['appointment_freelancer'] }}
                                        </td>
                                        <td width="20%" style="vertical-align: inherit;">
                                            @foreach ($appointment['service_arr'] as $service)
                                                <span>{{ $service }}</span><br>
                                            @endforeach
                                        </td>
                                        <!-- <td style="vertical-align: inherit;text-align: center;"> {{ $appointment['appointment_price'] }} </td> -->
                                        <td style="vertical-align: inherit;">
                                            {{ $appointment['appointment_start_time'] . ' to ' . $appointment['appointment_end_time'] }}
                                        </td>
                                        <!--<td style="vertical-align: inherit;text-align: center;"> {{ $appointment['appointment_status'] }} </td>-->
                                        <td style="vertical-align: inherit;text-align: center;">
                                            <select style="width: 150px" class="form-control changeAppointmentStatus"
                                                appointment_id="{{ $appointment['appointment_uuid'] }}"
                                                id="change_status" value="{{ $appointment['appointment_status'] }}">
                                                @if ($appointment_status != null)
                                                    <option disabled selected>Choose Status</option>
                                                    @foreach ($appointment_status as $status)
                                                        <option value="{{ $status['value'] }}">
                                                            {{ $status['text'] }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option disabled selected>
                                                        Appointment has been {{ $status }}
                                                    </option>
                                                @endif
                                                {{-- <option value="pending"
                                                    {{ strtolower($appointment['appointment_status']) == 'pending' ? 'selected=selected' : '' }}>
                                                    Pending</option>
                                                <option value="confirmed"
                                                    {{ strtolower($appointment['appointment_status']) == 'confirmed' ? 'selected=selected' : '' }}>
                                                    Confirmed</option>
                                                <option value="cancelled"
                                                    {{ strtolower($appointment['appointment_status']) == 'cancelled' ? 'selected=selected' : '' }}>
                                                    Cancelled</option>
                                                <option value="rejected"
                                                    {{ strtolower($appointment['appointment_status']) == 'rejected' ? 'selected=selected' : '' }}>
                                                    Rejected</option> --}}

                                            </select>
                                            <!--                                    <a href="{{ route('editAppointmentFrom', ['uuid' => $appointment['appointment_uuid']]) }}" class="btn btn-xs red">Edit</a>
                                                                                            <button class="btn btn-xs green dropdown-toggle" type="button"
                                                                                                    data-toggle="dropdown" aria-expanded="false"> Status
                                                                                                <i class="fa fa-angle-down"></i>
                                                                                            </button>
                                                                                            <ul class="dropdown-menu pull-left" role="menu">
                                                                                                <li>
                                                                                                    <a href="javascript:void(0);" class="updateAppointmentStatus"
                                                                                                       data-uuid="{{ $appointment['appointment_uuid'] }}"
                                                                                                       data-value="confirmed">Confirmed</a>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a href="javascript:void(0);" class="updateAppointmentStatus"
                                                                                                       data-uuid="{{ $appointment['appointment_uuid'] }}"
                                                                                                       data-value="cancelled">Cancelled</a>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a href="javascript:void(0);" class="updateAppointmentStatus"
                                                                                                       data-uuid="{{ $appointment['appointment_uuid'] }}"
                                                                                                       data-value="rejected">Rejeted</a>
                                                                                                </li>
                                                                                            </ul>-->

                                        </td>
                                        <td style="vertical-align: inherit;text-align: center;">
                                            @if ($appointment['appointment_status'] == 'Pending')
                                                <a href="{{ route('editAppointmentFrom', ['uuid' => $appointment['appointment_uuid']]) }}"
                                                    class="btn btn-s blue btn-outline">Edit</a>
                                            @else
                                                {{ $appointment['appointment_status'] }}
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="freelancersUpdateModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update Appointment Status</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to update this Status? </p>
                    <!-- <form class="bootbox-form">
                                            <label for="notes">Reason</label>
                                            <input class="bootbox-input bootbox-input-text form-control" id="freelancer_update_reason" name="freelancer_update_reason" autocomplete="off" type="text">
                                            <input type="hidden" id="status" name="status" value="" />
                                        </form> -->
                    <input type="hidden" id="status" name="status" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green"
                        id="yes_update_appointment">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $("#appointments").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>
    <script type="text/javascript">
        $('.changeAppointmentStatus').on('change', function() {
            var status = $(this).children("option:selected").val();
            var id = $(this).attr('appointment_id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure you want change status?',
                buttons: {
                    buttonA: {
                        text: 'Change Status',
                        action: function() {
                            $('.ajaxLoader').show();
                            window.location.href = siteUrl + 'updateAppointmentStatus/' + id + '/' +
                                status;
                        }

                    },
                    cancel: function() {}
                }
            });
        });
        //    });
    </script>
@endsection
