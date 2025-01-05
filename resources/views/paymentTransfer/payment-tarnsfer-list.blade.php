@extends('layouts.main')
@section('title')
    Payment Transfer List
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Payment Transfer List </span>
                    </div>
                </div>
                <div class="portlet-body">
                    @include('includes.session-messages')
                    <form action="{{ route('storeFreelancerPaymentRequest') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $freelancer['default_currency'] }}" name="currency">
                        <input type="hidden" value="{{ $freelancer['id'] }}" name="freelancer_id">
                        <div class="form-body">
                            <h4 class="form-section"
                                style="margin-top:0 !important; margin-bottom: 5px !important;font-weight: 600">
                                Freelancer Detail
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">First Name:</label>
                                        <div class="col-md-7">
                                            <strong>
                                                <a
                                                    href="{{ route('freelancerDetailPage', $freelancer['freelancer_uuid']) }}">{{ $freelancer['first_name'] ?? '' }}
                                                </a>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Last Name:</label>
                                        <div class="col-md-7">
                                            <strong><a
                                                    href="{{ route('freelancerDetailPage', $freelancer['freelancer_uuid']) }}">{{ $freelancer['last_name'] ?? '' }}
                                                </a></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Profession:</label>
                                        <div class="col-md-7">
                                            <strong>{{ $freelancer['profession'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Email:</label>
                                        <div class="col-md-7">
                                            <strong>{{ $freelancer['email'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="form-section"
                                style="margin-top: 5px;margin-bottom: 5px !important;font-weight: 600">
                                Bank Detail
                            </h4>
                            <div class="row">
                                @if (isset($freelancer['bank_detail']) && $freelancer['bank_detail']['location_type'] == 'KSA')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">IBAN:</label>
                                            <div class="col-md-7">
                                                <input required class="form-control" name="iban_account_number"
                                                    type="text"
                                                    value="{{ $freelancer['bank_detail']['iban_account_number'] ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($freelancer['bank_detail']) && $freelancer['bank_detail']['location_type'] == 'UK')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Account Number:</label>
                                            <div class="col-md-7">
                                                <input required class="form-control" name="account_number" type="text"
                                                    value="{{ $freelancer['bank_detail']['account_number'] ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Account Name:</label>
                                        <div class="col-md-7">
                                            <input required class="form-control" name="account_name" type="text"
                                                value="{{ $freelancer['bank_detail']['account_name'] ?? '' }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Account Title:</label>
                                        <div class="col-md-7">
                                            <input required class="form-control" name="account_title" type="text"
                                                value="{{ $freelancer['bank_detail']['account_title'] ?? '' }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-3" style="font-weight: 600">
                                        Earning Detail
                                        <span class="pull-right"><span>Total Amount</span> <b
                                                id="total_transaction_amount"> 0
                                                {{ $freelancer['default_currency'] }}</b></span>
                                        <input id="total_earning_amount" type="hidden" name="amount" value="" />
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-container" style="overflow-y: scroll; max-height: 500px">
                            <table class="table table-striped table-bordered table-hover earningTable">
                                <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> Earning Type</th>
                                        <th> Earn Amount </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($payments as $payment)
                                        @php
                                            $totalAmount += $payment['earned_amount'];
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($payment['subscription_id'] != null)
                                                    {{ 'Subscription' }}
                                                @elseif ($payment['class_booking_id'] != null)
                                                    {{ 'Class Booking' }}
                                                @elseif ($payment['appointment_id'] != null)
                                                    {{ 'Appointment' }}
                                                @elseif ($payment['purchased_package_id'] != null)
                                                    {{ 'Package Purchase' }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ round($payment['earned_amount'], 2) }}<b>
                                                    {{ $payment['freelancer']['default_currency'] }}</b>
                                            </td>
                                            <td class="text-center">
                                                <input style="width: 20px; height: 20px;" id="{{ $payment['id'] }}"
                                                    class="bCheckbox" checked type="checkbox"
                                                    data-payment_id="{{ $payment['id'] }}"
                                                    value="{{ $payment['id'] }}"
                                                    data-earning="{{ $payment['earned_amount'] }}"
                                                    name="freelancer_earning_id[]" id="freelancer_earning_id">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th class="text-center" colspan="4">No records found</th>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th class="text-right">
                                            Total Amount
                                        </th>
                                        <th id="total_amount" colspan="2" class="text-right" style="color: red">
                                            {{ round($totalAmount, 2) }}<b> {{ $freelancer['default_currency'] }}</b>
                                        </th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right" style="margin: 50px 0px 0px 0px">
                            <button type="submit" class="btn green btn-sm">
                                <i class="glyphicon glyphicon-export"></i> Generate Schedule Payment Slip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#total_transaction_amount").text("")
            $("#total_transaction_amount").text($('#total_amount').text())
            $("#total_earning_amount").val($.trim(($('#total_amount').text()).replace(
                '{{ $freelancer['default_currency'] }}', '')))

            $('.bCheckbox').on('change', function() {
                calculateAmount(this)
            })
        });

        function calculateAmount(pointer) {
            let calculate_total_earnings = 0;
            let total_earning = Number($.trim(($("#total_transaction_amount").text()).replace(
                '{{ $freelancer['default_currency'] }}', '')))
            let earnings = Number($(pointer).attr("data-earning"));

            if ($(pointer).is(':checked')) {
                total_earning += earnings;
                calculate_total_earnings = total_earning;
            } else {
                total_earning -= earnings;
                calculate_total_earnings = total_earning;
            }

            $("#total_transaction_amount").text(calculate_total_earnings.toFixed(2) +
                '{{ $freelancer['default_currency'] }}')
            $("#total_earning_amount").val(calculate_total_earnings.toFixed(2))
        }
    </script>
@endsection
