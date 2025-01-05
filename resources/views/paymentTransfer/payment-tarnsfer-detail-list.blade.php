@extends('layouts.main')
@section('title')
    Payment Transfer Detail
@endsection
{{-- @dd($freelancer_withdraws) --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Payment Transfer Detail </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dtTables">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> User </th>
                                <th class="text-center"> Status </th>
                                <th> Payment Date </th>
                                <th class="text-right"> Payment </th>
                                <th class="text-center"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($freelancer_withdraws as $withdraw)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $withdraw['withdrawal_freelancer']['first_name'] }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                        class="badge badge-{{ config('arrays.withdraw_statuses.' . $withdraw['schedule_status'] . '.color') }}">
                                        {{ config('arrays.withdraw_statuses.' . $withdraw['schedule_status'] . '.text') }}
                                    </span>
                                </td>
                                <td>
                                    {{ date('Y-m-d H:i:s', strtotime($withdraw['created_at'])) }}
                                </td>
                                <td class="text-right">
                                    {{ round($withdraw['amount'], 2) }}
                                    <b>{{ $withdraw['withdrawal_freelancer']['default_currency'] }}</b>
                                </td>
                                <td class="text-center">
                                    @if ($withdraw['schedule_status'] == 'in_progress')
                                        <a class="btn green btn-sm btn-outline"
                                            href="{{ route('freelancerPaymentRequestDetail', ['uuid' => $withdraw['freelancer_withdrawal_uuid']]) }}">
                                            Complete Now
                                        </a>
                                    @else
                                        <a class="btn btn-outline red-pink btn-sm"
                                            href="{{ route('freelancerPaymentRequestDetail', ['uuid' => $withdraw['freelancer_withdrawal_uuid']]) }}">
                                            View Detail
                                        </a>
                                    @endif
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" class="text-center">No Payments found</th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
