@extends('layouts.main')
@section('title')
    Appointment Details
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Appointment Details</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><b>Customer:</b> {{ $data['customer_name'] }}</h2>
                                        </div>
                                        <div class="col-md-6">
                                            <img class="rounded z-depth-2 pull-right" style="width:100px;height:100px;"
                                                alt="100x100" src="{{ $data['customer']['profile_images']['420'] }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2><b>Freelancer:</b> {{ $data['freelancer']['first_name'] }}
                                                {{ $data['freelancer']['last_name'] }}</h2>
                                        </div>
                                        <div class="col-md-6">
                                            <img class="rounded z-depth-2 pull-right" style="width:100px;height:100px;"
                                                alt="100x100" src="{{ $data['freelancer']['profile_images']['96'] }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Booking:</strong> {{ $data['title'] }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Type:</strong> {{ $data['service']['name'] }} </p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Duration:</strong> {{ $data['service']['duration'] }} Minutes </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Booking Date Time:</strong> {{ $data['date'] }} - {{ $data['start_time'] }} -
                                        {{ $data['end_time'] }} UTC</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Location:</strong> <i class="icon-location-pin"></i> {{ $data['address'] }}
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Appointment Status:</strong> {{ $data['status'] }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Booked At</strong> {{ $data['created_at'] }} </p>
                                </div>
                                <div class="col-md-12">
                                    <p><strong>Description</strong> {{ $data['service']['description'] }} </p>
                                </div>
                               
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Price:</strong> {{$data['freelancer']['currency']}} {{ $data['actual_price'] }} </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Paid Amount:</strong> {{$data['freelancer']['currency']}} {{ $data['paid_amount'] }} </p>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Location On Map
                            </div>
                            <div class="panel-body" style="text-align:center;">
                                @if (empty($data['address']) || empty($data['lat']) || empty($data['lng']))
                                    <p class="text-center">No address provided</p>
                                @else
                                    <iframe width="500" height="350" frameborder="0" style="border:0"
                                        src="{{ 'https://www.google.com/maps/embed/v1/place?key=AIzaSyDZwB1bGEAA8vdAojjv3N26GArZ8kEAo58&q=' .preg_replace('/\s+/', '+', $data['address']) .'&center=' .$data['lat'] .',' .$data['lng'] }}">
                                    </iframe>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
