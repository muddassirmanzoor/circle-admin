@extends('layouts.main')
@section('title')
    Payment Transfer Detail
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Payment Transfer Detail </span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided">
                            @if ($withdrawal_detail['freelancer_withdrawal_uuid'])
                                <a class="btn btn-sm" style="background: #04AA6D; border: transparent; color: white"
                                    href="{{ route('transferPaymentPDFDownload', ['uuid' => $withdrawal_detail['freelancer_withdrawal_uuid']]) }}">
                                    <i class="glyphicon glyphicon-save"></i> Download Pyaments Transfer</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    @include('includes.session-messages')
                    <form id="updateTransactionForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $withdrawal_detail['withdrawal_freelancer']['default_currency'] }}" name="currency">
                        <input type="hidden" value="{{ $withdrawal_detail['freelancer_withdrawal_uuid'] }}" name="uuid">
                        <div class="form-body">
                            <h4 class="form-section"
                                style="margin-top:0 !important; margin-bottom: 5px !important;font-weight: 600">
                                Freelancer Detail
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">First Name</label>
                                        <div class="col-md-7">
                                            <strong>
                                                <a
                                                    href="{{ route('freelancerDetailPage', $withdrawal_detail['withdrawal_freelancer']['freelancer_uuid']) }}">{{ $withdrawal_detail['withdrawal_freelancer']['first_name'] ?? '' }}
                                                </a>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Last Name</label>
                                        <div class="col-md-7">
                                            <strong><a
                                                    href="{{ route('freelancerDetailPage', $withdrawal_detail['withdrawal_freelancer']['freelancer_uuid']) }}">{{ $withdrawal_detail['withdrawal_freelancer']['last_name'] ?? '' }}
                                                </a></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Profession</label>
                                        <div class="col-md-7">
                                            <strong>{{ $withdrawal_detail['withdrawal_freelancer']['profession'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Email</label>
                                        <div class="col-md-7">
                                            <strong>{{ $withdrawal_detail['withdrawal_freelancer']['email'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="form-section"
                                style="margin-top: 5px;margin-bottom: 5px !important;font-weight: 600">
                                Bank Detail
                            </h4>
                            <div class="row">
                                @if (isset($withdrawal_detail['withdrawal_freelancer']['bank_detail']) && $withdrawal_detail['withdrawal_freelancer']['bank_detail']['location_type'] == 'KSA')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">IBAN</label>
                                            <div class="col-md-7">
                                                <strong>{{ $withdrawal_detail['iban_account_number'] ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($withdrawal_detail['withdrawal_freelancer']['bank_detail']) && $withdrawal_detail['withdrawal_freelancer']['bank_detail']['location_type'] == 'UK')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5">Account Number</label>
                                            <div class="col-md-7">
                                                <strong>{{ $withdrawal_detail['account_number'] ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Account Name</label>
                                        <div class="col-md-7">
                                            <strong>{{ $withdrawal_detail['account_name'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Account Title</label>
                                        <div class="col-md-7">
                                            <strong>{{ $withdrawal_detail['account_title'] ?? '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="form-section" style="font-weight: 600">
                                Fill Receipt Information
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Receipt ID</label>
                                        <div class="col-md-7">
                                            <input required class="form-control"
                                                value="{{ $withdrawal_detail['reciept_id'] ?? '' }}" name="receipt_id"
                                                type="text" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Receipt File</label>
                                        <div class="col-md-7">
                                            <input  type="file" class="form-control" name="receipt_url"
                                                id="receipt_url" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5">Schedule Status</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="schedule_status" id="schedule_status">
                                                <option
                                                    {{ $withdrawal_detail['schedule_status'] == 'in_progress' ? 'selected' : '' }}
                                                    value="in_progress">
                                                    Progress
                                                </option>
                                                <option
                                                    {{ $withdrawal_detail['schedule_status'] == 'complete' ? 'selected' : '' }}
                                                    value="complete">
                                                    Complete
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-3" style="font-weight: 600">
                                        Payments
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-container" style="overflow-y: scroll; max-height: 500px">
                            <table class="table table-striped table-bordered table-hover earningTable">
                                <thead>
                                    <tr>
                                        <th> # </th>
                                        <th class="text-center"> Earning Type</th>
                                        <th class="text-right"> Earn Amount </th>
                                        <th class="text-center"> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($withdrawal_detail['withdrawal_earnings'] as $payment)
                                        @php
                                            $totalAmount += $payment['earned_amount'];
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
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
                                                    {{ $withdrawal_detail['withdrawal_freelancer']['default_currency'] }}</b>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ config('arrays.withdraw_statuses.' . $withdrawal_detail['schedule_status'] . '.color') }}">
                                                    {{ config('arrays.withdraw_statuses.' . $withdrawal_detail['schedule_status'] . '.text') }}
                                                </span>
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
                                            {{ round($totalAmount, 2) }}<b>
                                                {{ $withdrawal_detail['withdrawal_freelancer']['default_currency'] }}</b>
                                        </th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button data-text='Mark Schedule Status to completed'
                                        data-url={{ route('updateFreelancerPaymentTransfer') }} type="submit"
                                        class="btn btn-sm btn-info pull-right updateTransactionBtn">
                                        <i class="glyphicon glyphicon-saved"></i> Update Schedule
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('internal-files-script')
    <script>
        // update settings using sweetalert
        $("#updateTransactionForm").submit(function(event) {
            event.preventDefault()
            var form = $('#updateTransactionForm')[0];
            let params = new FormData(form);
            let file = $.trim($(this).find("#receipt_url").val().replace(/C:\\fakepath\\/i, ''));
            let text = $(this).find(".updateTransactionBtn").data("text");
            let url = $(this).find(".updateTransactionBtn").data("url");
            // params.push({name: 'receipt_url', value: file});
            console.log(params)
            Swal.fire({
                title: 'Are you sure!',
                text: `${text}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Update Anyway',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: params,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.status == 200) {
                                $(document).scrollTop(0);
                                $(document).find(".ajax-alert-suucess").attr('style',
                                    "display: block");
                                $(document).find(".ajax-alert-suucess").children(".alert-text")
                                    .text(data.message);
                                $(document).scrollTop(0);
                            }
                            if (data.status == 500) {
                                $(document).find(".ajax-alert-error").attr('style',
                                    "display: block");
                                $(document).find(".ajax-alert-error").children(".alert-text")
                                    .text(data.message);
                            }
                            // setTimeout(function() {
                            //     location.reload()
                            // }, 2000);
                        },
                        error: function(data) {
                            console.log('An error occurred.');
                            console.log(data);
                        },
                    })
                }
            })
        });
    </script>
@endsection
