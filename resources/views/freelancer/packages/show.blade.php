@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Package Details</span>
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
                                    <h2>{{$data['package_name']}}</h2>
                                </div>
                                <div class="col-md-2">
                                    @if($data['package_image'])
                                    <img class="rounded z-depth-2 pull-right" style="width:100px;height:100px;" alt="100x100" src="{{ config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['package_image'] . $data['package_image'] }}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Type:</strong>  {{$data['package_type']}} </p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>No of Sessions:</strong> {{$data['no_of_session']}} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Discount:</strong> {{ $data['discount_amount'] }}
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Discount Type:</strong> {{ $data['discount_type'] }}  </p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Validity:</strong> {{ $data['package_validity'] }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Validity Type:</strong> {{ $data['validity_type'] }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Description</strong> {{ $data['package_description']  }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer"><p><strong>Price:</strong> {{ $data['price'] }} {{ $data['currency'] }} </p></div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Package Description Video
                        </div>
                        <div class="panel-body">
                            @if($data['description_video'])
                                <video controls>
                                    <source
                                        src="{{{ config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['package_description_video'] . $data['description_video'] }}}"
                                        type="video/mp4"
                                        @if($data['description_video_thumbnail'])
                                        poster="{{ config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['package_description_video'] . $data['description_video_thumbnail'] }}"
                                        @endif
                                    >
                                </video>
                            @else
                                <p class="text-center mt-2 mb-2">No Video Found</p>
                            @endif
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Package Bookings
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover packages-table" data-title="Booking" id="bookingTable">
                                <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Booking Number </th>
                                    <th> Class </th>
                                    <th> Customer </th>
                                    <th> Sessions </th>
                                    <th> Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data['class_booking'])
                                    @foreach($data['class_booking'] as $booking)
                                        <tr>
                                            <td style="vertical-align: inherit;"> {{ $booking['id'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $booking['class_booking_uuid'] }} </td>
                                            <td style="vertical-align: inherit;">
                                                <a href="{{ route('freelancerClasses', ['id' => $booking['class_uuid']]) }}">
                                                    {{ $booking['class_object']['name'] }}
                                                </a>
                                            </td>
                                            <td style="vertical-align: inherit;">
                                                <a href="{{ route('customerDetailPage', ['id' => $booking['customer_uuid']]) }}">
                                                    {{ $booking['customer']['email'] }}
                                                </a>
                                            </td>
                                            <td style="vertical-align: inherit;text-align: center;"> {{ $booking['session_number'] }} / {{ $booking['total_session'] }} </td>
                                            <td style="vertical-align: inherit;text-align: center;"> {{ $booking['status'] }} </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Package Services
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover packages-table" data-title="Service" id="serviceTable">
                                <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Industry Name </th>
{{--                                    <th> Service Name </th>--}}
                                    <th> Price </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data['package_service'])
                                    @foreach($data['package_service'] as $service)
                                        <tr>
                                            <td style="vertical-align: inherit;"> {{ $service['id'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $service['freelancer_category']['category']['name'] }} </td>
{{--                                            <td style="vertical-align: inherit;"> {{ $service['freelancer_category']['sub_category']['name'] }} </td>--}}
                                            <td style="vertical-align: inherit;"> {{ $service['freelancer_category']['price'] }} </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Package Appointments
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover packages-table" data-title="Appointment" id="appointmentTable">
                                <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Title </th>
                                    <th> Type </th>
                                    <th> Price </th>
                                    <th> Date </th>
                                    <th> Time </th>
                                    <th> Status </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data['package_appointment'])
                                    @foreach($data['package_appointment'] as $appointment)
                                        <tr>
                                            <td style="vertical-align: inherit;"> {{ $appointment['id'] }} </td>
                                            <td style="vertical-align: inherit;">
                                                <a href="{{ route('getAppointment', ['uuid' => $appointment['appointment_uuid']]) }}">
                                                    {{ $appointment['title'] }}
                                                </a>
                                            </td>
                                            <td style="vertical-align: inherit;"> {{ $appointment['type'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $appointment['price'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $appointment['appointment_date'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $appointment['from_time'] }} to {{ $appointment['to_time'] }} </td>
                                            <td style="vertical-align: inherit;"> {{ $appointment['status'] }} </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>





                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $(".packages-table").each(function(){
                let elem = $(this);
                let title = elem.data('title');
                elem.dataTable({
                    "oLanguage": {
                        "sEmptyTable": "No "+title+" found"
                    }
                });
            });
        })
    </script>
@endsection
