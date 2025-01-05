@extends('layouts.main')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Payment Requests </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="payment_request_table">
                        <thead>
                        <tr>
                            <th> No </th>
                            <th> User </th>
                            <th> Req Amount</th>
                            <th> Deductions </th>
                            <th> Final Amount</th>
                            <th> Date </th>
                            <th> Status</th>
                            <th> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $ind => $req)
                                    <tr>
                                        <td> {{$req['no']}} </td>
                                        <td> {{$req['freelancer']['first_name'] ?? ''}} </td>
                                        <td> {{$req['requested_amount']}} <small>{{$req['currency']}}</small></td>
                                        <td> {{$req['deductions']}} <small>{{$req['currency']}}</small></td>
                                        <td> {{$req['final_amount']}} <small>{{$req['currency']}}</small></td>
                                        <td> {{$req['date']}} </td>
                                        <td>
                                            @if($req['status'] == 1)
                                                <strong style="color: blue"> processed </strong>
                                            @elseif($req['status'] == 2)
                                                <strong style="color: green"> transferred</strong>
                                            @elseif($req['status'] == 3)
                                                <strong style="color: red"> rejected </strong>
                                            @else
                                                <strong style="color: orange"> pending </strong>
                                            @endif
                                        </td>
                                        <td> <a href="{{$req['action_detail']}}" class="btn btn-xs green">Detail</a>  </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(function () {
        $("#payment_request_table").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Record found"
            }
        });
    })
</script>

@endsection
