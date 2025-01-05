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
                            Freelancers
                        </span>

                    </div>
                    <div class="caption font-dark col-md-6">
                        <a class="btn btn-primary" style="float: right" href="{{route('freelancerCSV',['uuid'=>$uuid])}}">
                            <i class="glyphicon glyphicon-download-alt"></i>
                            Download CSV
                        </a>

                    </div>
                </div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dtTables">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Reference # </th>
                                <th> User </th>
                                <th> Email</th>
                                <th> Profession </th>
                                <th> Acount Title </th>
                                <th> Account # </th>
                                <th> IBAN </th>
                                <th> Swfit Code </th>
                                <th> Address 1 </th>
                                <th> Address 2 </th>
                                <th> Address 3 </th>
                                <th> Total Earnings </th>
                                <th> Status </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php
                                $total_sum = 0;
                            @endphp --}}
                            @forelse ($freelancers as $freelancer)
                            {{-- @php
                                $total_sum += $freelancer['amount'];
                            @endphp --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $freelancer['fp_payment_reference'] }}</td>
                                    <td>{{ $freelancer['withdrawal_freelancer']['first_name'].' '.$freelancer['withdrawal_freelancer']['last_name']  }}</td>
                                    <td>{{ $freelancer['withdrawal_freelancer']['email'] }}</td>
                                    <td>{{ $freelancer['withdrawal_freelancer']['profession'] }}</td>
                                    <td>{{$freelancer['account_name']}}</td>
                                    <td>{{$freelancer['account_number']}}</td>
                                    <td>{{$freelancer['iban_account_number']}}</td>
                                    <td>{{$freelancer['swift_code']}}</td>
                                    <td>{{$freelancer['beneficiary_address_1']}}</td>
                                    <td>{{$freelancer['beneficiary_address_2']}}</td>
                                    <td>{{$freelancer['beneficiary_address_3']}}</td>
                                    <td>{{ round($freelancer['amount'], 2) . ' ' . $freelancer['withdrawal_freelancer']['default_currency'] }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-primary">{{$freelancer['transfer_status']}}</span>
                                    </td>
                                    <td>
                                        <a class="btn green btn-sm btn-outline"
                                            href="{{ route('freelancerDetailPage',$freelancer['withdrawal_freelancer']['freelancer_uuid']) }}">
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
                        {{-- <tfoot>
                            <tr>
                                <th > </th>
                                <th>  </th>
                                <th> </th>
                                <th>  </th>
                                <th > </th>
                                <th>  </th>
                                <th> </th>
                                <th>  </th>
                                <th>  </th>
                                <th> </th>
                                <th>  </th>
                                <th> {{round($total_sum,2)}} </th>
                                <th>  </th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
