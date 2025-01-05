@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@section('content')
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
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            {{-- {{strtoupper(Str::random(10))}} --}}
            <a href="{{ route('getActiveFreelancers') }}" class="dashboard-stat dashboard-stat-v2 blue">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['active_freelancer_count'] }}"></span>
                    </div>
                    <div class="desc"> Active Freelancers</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getNotActiveFreelancers') }}" class="dashboard-stat dashboard-stat-v2 red">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['not_active_freelancer_count'] }}"></span>
                    </div>
                    <div class="desc"> Not Active Freelancers</div>
                </div>
            </a>
        </div>
        {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getBlockedFreelancers') }}" class="dashboard-stat dashboard-stat-v2 green">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['blocked_freelancer_count'] }}"></span>
                    </div>
                    <div class="desc"> Blocked Freelancers</div>
                </div>
            </a>
        </div> --}}
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getDeletedFreelancers') }}" class="dashboard-stat dashboard-stat-v2 purple">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['deleted_freelancer_count'] }}"></span>
                    </div>
                    <div class="desc"> Deleted Freelancers</div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getActiveCustomers') }}" class="dashboard-stat dashboard-stat-v2 blue">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['active_customer_count'] }}"></span>
                    </div>
                    <div class="desc"> Active Customers</div>
                </div>
            </a>
        </div>
        <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('getPendingCustomers') }}" class="dashboard-stat dashboard-stat-v2 red">
                    <div class="visual">
                        <i class="icon-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $data['pending_customer_count'] }}"></span></div>
                        <div class="desc"> Pending Customers</div>
                    </div>
                </a>
            </div> -->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getBlockedCustomers') }}" class="dashboard-stat dashboard-stat-v2 green">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['blocked_customer_count'] }}"></span>
                    </div>
                    <div class="desc"> Blocked Customers</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getDeletedCustomers') }}" class="dashboard-stat dashboard-stat-v2 purple">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['deleted_customer_count'] }}"></span>
                    </div>
                    <div class="desc"> Deleted Customers</div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getPendingAppointments') }}" class="dashboard-stat dashboard-stat-v2 blue">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['pending_appointment_count'] }}"></span>
                    </div>
                    <div class="desc"> Pending Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getConfirmedAppointments') }}" class="dashboard-stat dashboard-stat-v2 red">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['confirmed_appointment_count'] }}"></span>
                    </div>
                    <div class="desc"> Confirmed Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getCompletedAppointments') }}" class="dashboard-stat dashboard-stat-v2 green">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['completed_appointment_count'] }}"></span>
                    </div>
                    <div class="desc"> Completed Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getCancelledAppointments') }}" class="dashboard-stat dashboard-stat-v2 purple">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['cancelled_appointment_count'] }}"></span>
                    </div>
                    <div class="desc"> Cancelled Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getReportedPost') }}" class="dashboard-stat dashboard-stat-v2 blue">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['reported_post_count'] }}"></span>
                    </div>
                    <div class="desc"> Reported Post</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('getBlockedPosts') }}" class="dashboard-stat dashboard-stat-v2 red">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $data['blocked_post_count'] }}"></span>
                    </div>
                    <div class="desc"> Blocked Post</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Graphs -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN CHART PORTLET -->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze"> New Freelancer Chart</span>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                        <a href="javascript:;" class="reload"> </a>
                        <a href="javascript:;" class="fullscreen"> </a>
                        <a href="javascript:;" class="remove"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="chart_1" class="chart" style="height: 500px;"> </div>
                </div>
            </div>
            <!-- END CHART PORTLET-->
        </div>


        <div class="col-md-6" style="display: none;">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze"> New customer chart</span>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                        <a href="javascript:;" class="reload"> </a>
                        <a href="javascript:;" class="fullscreen"> </a>
                        <a href="javascript:;" class="remove"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="chart_4" class="chart" style="height: 400px;"> </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN : GOOGLE LINE CHARTS -->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green bold uppercase">Appointments</span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                            <i class="icon-cloud-upload"></i>
                        </a>
                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                            <i class="icon-wrench"></i>
                        </a>
                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                            <i class="icon-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="gchart_line_1" style="height:500px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN : GOOGLE LINE CHARTS -->
@endsection
