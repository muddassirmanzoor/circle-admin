@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Cronjob Logs </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="cronjob_logs">
                        <thead>
                        <tr>
                            <th> No </th>
                            <th> Name </th>
                            <th> Response </th>
                            <th> Log </th>
                            <th> Date & Time </th>
                            <th> Actions </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($cronJobs))
                            @foreach($cronJobs as $ind => $cronJob)
                                <tr>
                                    <td> {{$ind + 1}} </td>
                                    <td> <a href="{{ route('CronJobs.show', ['id' => $cronJob->cron_job_log_uuid]) }}"> {{$cronJob->name}}</a> </td>
                                    <td> {!! $cronJob->getSuccessHTML() !!} </td>
                                    <td> {{ \Illuminate\Support\Str::limit($cronJob->message, 300) }} </td>
                                    <td> {{$cronJob->created_at}} </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{ route('CronJobs.show', ['id' => $cronJob->cron_job_log_uuid]) }}" class="btn btn-xs blue">View</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{ $cronJobs->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("#cronjob_logs").dataTable({
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
