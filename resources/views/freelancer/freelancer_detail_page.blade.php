@extends('layouts.main')
@section('title')
    Freelancer Statistics
@endsection
@section('content')
    @php
    $freelancer_uuid = $data['freelancer_uuid'];
    $freelancer_stats = $data['freelancer_stats'];
    $freelancer_detail = $data['freelancer_detail'];
    $freelancer_bank_detail = $data['freelancer_detail']['bank_detail'];
    @endphp
    <h1 class="page-title"> Freelancer Statistics </h1>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup"
                                data-value="{{ $freelancer_stats['appointments'] }}">{{ $freelancer_stats['appointments'] }}</span>
                        </h3>
                        <small>Appointments</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 100%;" class="progress-bar progress-bar-success green-sharp"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze">
                            <span data-counter="counterup"
                                data-value="{{ $freelancer_stats['classess'] }}">{{ $freelancer_stats['classess'] }}</span>
                        </h3>
                        <small>Classes</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 100%;" class="progress-bar progress-bar-success red-haze"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup"
                                data-value="{{ $freelancer_stats['sessions'] }}">{{ $freelancer_stats['sessions'] }}</span>
                        </h3>
                        <small>Sessions</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 100%;" class="progress-bar progress-bar-success blue-sharp"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-purple-soft">
                            <span data-counter="counterup"
                                data-value="{{ $freelancer_stats['customers'] }}">{{ $freelancer_stats['customers'] }}</span>
                        </h3>
                        <small>Customers</small>
                    </div>
                    <div class="icon">
                        <i class="icon-user"></i>
                    </div>
                </div>
                <div class="progress-info">
                    <div class="progress">
                        <span style="width: 100%;" class="progress-bar progress-bar-success purple-soft"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Freelancer Related Info
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable tabbable-tabdrop">
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a href="#detail" data-toggle="tab">Detail </a>
                            </li>
                            <li>
                                <a href="#freelancer_profile_image" data-toggle="tab">Change Profile Picture</a>
                            </li>
                            {{-- <li> --}}
                            {{-- <a href="#category" data-toggle="tab">Category </a> --}}
                            {{-- </li> --}}
                            <li>
                                <a href="#subCategory" data-toggle="tab">Services </a>
                            </li>
                            <li>
                                <a href="#subscription" data-toggle="tab">Subscription </a>
                            </li>
                            <li>
                                <a href="#locations" data-toggle="tab">Locations </a>
                            </li>
                            <li onclick="loadFreelancerCalendarData()">
                                <a href="#freelancerCalendarData" data-toggle="tab">Calender</a>
                            </li>
                            <li>
                                <a href="#classess" data-toggle="tab">Classes </a>
                            </li>
                            {{-- <li> --}}
                            {{-- <a href="#sessions" data-toggle="tab">Sessions </a> --}}
                            {{-- </li> --}}
                            <li onclick="loadScheduleCalendar()">
                                <a href="#schedules" data-toggle="tab">Schedules </a>
                            </li>
                            <li>
                                <a href="#packages" data-toggle="tab">Packages </a>
                            </li>
                            {{-- <li onclick="loadBlocktimeCalendar()"> --}}
                            {{-- <a href="#blockedTimings" data-toggle="tab">Blocked Timings </a> --}}
                            {{-- </li> --}}
                            {{-- <li onclick="loadAppointmentCalendar()"> --}}
                            {{-- <a href="#appointments" data-toggle="tab">Appointments</a> --}}
                            {{-- </li> --}}
                            <li class="hidden">
                                <a href="#promoCodes" data-toggle="tab">Promo Codes </a>
                            </li>
                            <li>
                                <a href="#tab9" data-toggle="tab">Earning </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="detail">
                                @include('freelancer.freelancer_detail')
                            </div>
                            <div class="tab-pane" id="freelancer_profile_image">
                                <form id="freelancer_coords" action="{{ route('updateFreelancerPicture') }}"
                                    enctype="multipart/form-data" class="form-horizontal" method="post">
                                    @csrf
                                    <input type="hidden" name="freelancer_uuid" id="freelancer_uuid"
                                        value="{{ $data['freelancer_uuid'] }}">
                                    <input type="hidden" id="crop_x" name="x" value="" />
                                    <input type="hidden" id="crop_y" name="y" value="" />
                                    <input type="hidden" id="crop_w" name="w" value="" />
                                    <input type="hidden" id="crop_h" name="h" value="" />
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="">
                                                    <img src="{{ $freelancer_detail['profile_image'] }}" alt="" />
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                    style="max-width: 400px; max-height: 400px;"> </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> Select image </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="freelancer_picture" id="freelancer_picture"
                                                            value="" required>
                                                    </span>
                                                    <a href="javascript:void(0)" class="btn default fileinput-exists"
                                                        data-dismiss="fileinput"> Remove </a>
                                                </div>
                                                <br />
                                                <strong>Max Upload Size 1 MB</strong>
                                            </div>
                                            <!-- <div class="col-md-12">
                                                            <input type="file" name="freelancer_picture" id="freelancer_picture" required>
                                                        </div>
                                                        <br>
                                                        <div class="col-md-12">
                                                            <img class="timeline-body-img pull-left center" src="{{ $freelancer_detail['profile_image'] }}"  id="picture_preview" alt="">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="button" id="delete_freelancer_picture" class="btn btn-danger hidden">Remove</button>
                                                        </div> -->
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit"
                                                            class="btn green">Submit</button>
                                                        <a href="{{ route('getAllFreelancers') }}"
                                                            class="btn red">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="tab-pane" id="category"> --}}
                            {{-- @include('freelancer.freelancer_categories') --}}
                            {{-- </div> --}}
                            <div class="tab-pane" id="subCategory">
                                @include('freelancer.freelancer_subcategories')
                            </div>
                            <div class="tab-pane" id="subscription">
                                @include('freelancer.freelancer_subsc')
                            </div>
                            <div class="tab-pane" id="locations">
                                @include('freelancer.freelancer_locations')
                            </div>
                            <div class="tab-pane" id="freelancerCalendarData">
                                @include('freelancer.freelancer_calendar_data')
                            </div>
                            <div class="tab-pane" id="classess">
                                @include('freelancer.freelancer_classes')
                            </div>
                            {{-- <div class="tab-pane" id="sessions"> --}}
                            {{-- @include('freelancer.freelancer_sessions') --}}
                            {{-- </div> --}}
                            <div class="tab-pane" id="schedules">
                                @include('freelancer.freelancer_schedules')
                            </div>
                            <div class="tab-pane" id="packages">
                                @include('freelancer.freelancer_packages')
                            </div>
                            {{-- <div class="tab-pane" id="blockedTimings"> --}}
                            {{-- @include('freelancer.freelancer_blockedTimings') --}}
                            {{-- </div> --}}
                            {{-- <div class="tab-pane" id="appointments"> --}}
                            {{-- @include('freelancer.freelancer_appointments') --}}
                            {{-- </div> --}}
                            <div class="tab-pane" id="tab9">
                                @include('freelancer.freelancer_earnings')
                            </div>
                            <div class="tab-pane hidden" id="promoCodes">
                                @include('freelancer.freelancer_promoCode')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            function callCalendar() {
                alert("adsad");
            }
        })
    </script>
@endsection
