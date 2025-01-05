@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> AWS SES Complaints </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="complaints">
                        <thead>
                        <tr>
                            <th> No </th>
                            <th> Email </th>
                            <th> Type </th>
                            <th> Agent </th>
                            <th> Message ID </th>
                            <th> Feedback ID </th>
                            <th> Source Email </th>
                            <th> Mail Time </th>
{{--                            <th> Action </th>--}}
{{--                            <th> Actions </th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($complaints))
                            @foreach($complaints as $ind => $complaint)
                                <tr>
                                    <td> {{$ind + 1}} </td>
                                    <td>
                                        @php($links = $complaint->getFreelancerCustomerLinkByEmail())
                                        <span>{{ $complaint->email_address }} </span> <br/>
                                        <span>( {!! !empty($links) ? implode(' | ', $links)  : 'No profile found' !!} )</span>
                                    </td>
                                    <td> {{$complaint->type}} </td>
                                    <td> {{$complaint->user_agent}} </td>
                                    <td> {{$complaint->message_id}} </td>
                                    <td> {{$complaint->feedback_id}} </td>
                                    <td> {{$complaint->source_email_address}} </td>
                                    <td> {{$complaint->mail_time}} </td>

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
                        {{ $complaints->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("#complaints").dataTable({
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
