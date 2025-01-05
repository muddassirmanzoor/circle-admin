@extends('layouts.main')
@section('title') Freelancers Earnings @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title row">
                    <div class="caption font-dark col-md-6">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">
                            Available Freelancer Earnings
                            </span>

                    </div>

                    <div class="col-md-6">
                        @if(count($freelancers)  > 0)
                            <a class="btn btn-primary csvDownloadBtn" style="float: right" href="{{url('download-funds-transfer-csv')}}">
                                <i class="glyphicon glyphicon-download-alt"></i>
                                Download CSV
                            </a>
                        @endif
                    </div>

                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dtTables">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> User </th>
                                <th> Email</th>
                                <th> Profession </th>
                                <th> Total Earnings </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($freelancers as $freelancer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $freelancer['freelancer'] }}</td>
                                    <td>{{ $freelancer['email'] }}</td>
                                    <td>{{ $freelancer['profession'] }}</td>
                                    <td>{{ round($freelancer['total_earning'], 2) . ' ' . $freelancer['default_currency'] }}</td>
                                    <td>
                                        <a class="btn green btn-sm btn-outline"
                                            href="{{ route('freelancerDetailPage',$freelancer['freelancer_uuid']) }}">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="text-center" colspan="6">No Freelancer Earnings found</th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('internal-files-script')
<script>
    $(document).on('click','.downloadCsvBtn',function(){
        setTimeout(function() { 
            window.location.reload();
    }, 2000);
    })
</script>
@endsection