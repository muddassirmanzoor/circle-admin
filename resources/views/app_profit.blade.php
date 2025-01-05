@extends('layouts.main')
@section('title') App Profit @endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> App Profit </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="app_profit">
                        <thead>
                        <tr>
                            <th> Total Amount</th>
                            <th> Percentage</th>
                            <th> Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!empty($profit['total_amount']))
                            <tr>
                                <td>{{ $profit['total_amount'] }} $</td>
                                <td>{{ $profit['percentage'] }} %</td>
                                <td>{{ $profit['app_profit'] }} $</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(function () {
        $("#app_profit").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Record found"
            }
        });
    })
</script>

@endsection
