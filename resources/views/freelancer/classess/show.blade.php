@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="glyphicon glyphicon-th font-dark"></i>
                    <span class="caption-subject bold uppercase">Class Details</span>
                    <span>
                        (Freelancer:
                        <a href="{{ route('freelancerDetailPage', ['uuid' => $data['freelancer']['freelancer_uuid']]) }}">
                            {{ $data['freelancer']['first_name'] }} {{ $data['freelancer']['last_name'] }}
                        </a>
                        )
                    </span>

                </div>
            </div>
            <div class="portlet-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                 <h2>{{$data['name']}}</h2>
                            </div>
                            <div class="col-md-2">
                                @if($data['image'])
                                <img class="rounded z-depth-2 pull-right" style="width:100px;height:100px;" alt="100x100" src="{{ $data['image']}}" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Booking:</strong>  {{$data['name']}}</p>
                            </div>
{{--                            <div class="col-md-4">--}}
{{--                                <p><strong>Type:</strong>  {{$data['service']['name']}} </p>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4">--}}
{{--                                <p><strong>Duration:</strong> {{$data['service']['duration']}} Minutes </p>--}}
{{--                            </div>--}}
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Date/Time:</strong> {{ $data['schedule'][0]['date']  }}  - {{ $data['schedule'][0]['start_time'] }} - {{ $data['schedule'][0]['end_time'] }} UTC</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Location:</strong> <i class="icon-location-pin"></i> {{ $data['address'] }}
                            </div>
                            <div class="col-md-4">
                                <p><strong>Members:</strong> {{ !empty($data['members'])? count($data['members']): 0 }}  </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Description</strong> {{ $data['description']  }} </p>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer"><p><strong>Price:</strong> ${{$data['price']}} </p></div>
                </div>

{{--                <div class="panel panel-default">--}}
{{--                    <div class="panel-heading">--}}
{{--                        Class Description Video--}}
{{--                    </div>--}}
{{--                    <div class="panel-body">--}}
{{--                        @if($data['service']['description_video'])--}}
{{--                            <video controls>--}}
{{--                                <source src="{{{ $data['service']['description_video'] }}}" type="video/mp4">--}}
{{--                            </video>--}}
{{--                        @else--}}
{{--                            <p>No Video Found</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}

                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Location On Map
                    </div>
                    <div class="panel-body" style="text-align:center;">
                        @if(empty($data['address']) || empty($data['lat']) || empty($data['lng']))
                            <p class="text-center">No address provided</p>
                        @else
                            <iframe
                                width="500"
                                height="350"
                                frameborder="0" style="border:0"
                                src="{{ "https://www.google.com/maps/embed/v1/place?key=AIzaSyDZwB1bGEAA8vdAojjv3N26GArZ8kEAo58&q=".preg_replace('/\s+/', '+', $data['address'])."&center=".$data['lat'].",".$data['lng'] }}">
                            </iframe>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
