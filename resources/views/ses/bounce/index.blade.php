@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> AWS SES Bounces </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="bounces">
                        <thead>
                        <tr>
                            <th> No </th>
                            <th> Email </th>
                            <th> Type </th>
                            <th> Subtype </th>
                            <th> Diagnostic Code </th>
                            <th> Message ID </th>
                            <th> Feedback ID </th>
                            <th> Source Email </th>
                            <th> Mail Time </th>
                            <th> Status </th>
                            <th> Status Code </th>
{{--                            <th> Action </th>--}}
{{--                            <th> Actions </th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($bounces))
                            @foreach($bounces as $ind => $bounce)
                                <tr>
                                    <td> {{$ind + 1}} </td>
                                    <td>
                                        @php($links = $bounce->getFreelancerCustomerLinkByEmail())
                                        <span>{{ $bounce->email_address }} </span> <br/>
                                        <span>( {!! !empty($links) ? implode(' | ', $links)  : 'No profile found' !!} )</span>
                                    </td>
                                    <td> {{$bounce->type}} </td>
                                    <td> {{$bounce->sub_type}} </td>
                                    <td> {{$bounce->diagnostic_code}} </td>
                                    <td> {{$bounce->message_id}} </td>
                                    <td> {{$bounce->feedback_id}} </td>
                                    <td> {{$bounce->source_email_address}} </td>
                                    <td> {{$bounce->mail_time}} </td>
                                    <td> {{$bounce->action}} </td>
                                    <td> {{$bounce->status}} </td>

{{--                                    <td>--}}
{{--                                        <div class="text-center">--}}
{{--                                            <a href="{{ route('CronJobs.show', ['id' => $cronJob->cron_job_log_uuid]) }}" class="btn btn-xs blue">View</a>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{ $bounces->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("#bounces").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record found"
                },
                "paging" : false,
                "searching" : false,
                "bInfo" : false
            });
        })
    </script>

@endsection
