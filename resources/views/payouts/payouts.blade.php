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
                            @if($type == 'available')
                               
                            @elseif($type == 'in_progress')
                                In Progress Payouts
                            @elseif($type == 'completed')
                                Completed Payouts
                            @elseif($type == 'failed')
                                Failed Payouts
                            @endif
                            
                            </span>

                    </div>

                    <div class="col-md-6">
                        @if(count($payouts) > 0)
                        <div style="float: right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">
                                Update Status
                            </button>
    
                            {{-- <a  class="btn btn-primary " style="float: right" href="{{url('download-funds-transfer-csv')}}">
                                <i class="glyphicon glyphicon-download-alt"></i>
                                Download CSV
                            </a> --}}
                        </div>
                        @endif

                        
                    </div>

                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dtTables">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> File Reference # </th>
                                <th> Total No of Payments </th>
                                {{-- <th> Total Amount</th> --}}
                                <th>File Creation Date</th>
                                <th>File Creation Time</th>
                                <th>Status</th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payouts as $payout)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payout['reference_no'] }}</td>
                                    <td>{{ $payout['total_number_of_payments'] }}</td>
                                    {{-- <td>{{ round($payout['total_amount'],2) }}</td> --}}
                                    <td>{{ $payout['created_date'] }}</td>
                                    <td>{{ $payout['created_time'] }}</td>
                                    <td><span class="badge badge-pill badge-primary">{{$payout['status']}}</span></td>

                                    <td>
                                        <a class="btn green btn-sm btn-outline"
                                            href="{{ route('payoutDetails', ['uuid' => $payout['funds_transfer_uuid']]) }}">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                               
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Update Payouts Status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('updatePayoutStatusWithFile')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                    <div class="custom-file">
                        <input required name="file" type="file" class="custom-file-input" id="customFile">
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>

      </div>
    </div>
  </div>
@endsection
