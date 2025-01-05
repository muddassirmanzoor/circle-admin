<div class="row">
    <div class="col-md-12">
        <div class="portlet box ">
            <div class="">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <strong class="bold col-md-5">Total Subscribers:</strong>
                            <strong
                                class="bold col-md-1">{{ $data['freelancer_subcribers']['subscriber_count'] }}</strong>
                            <br />
                            <strong class="bold col-md-5">Monthly Subscribers:</strong>
                            <strong class="bold col-md-1">
                                {{ $data['freelancer_monthly_subcribers'] }}</strong>
                            <br />
                            <strong class="bold col-md-5">Yearly Subscribers:</strong>
                            <strong class="bold col-md-1">{{ $data['freelancer_subcribers']['subscriber_count'] }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <strong class="bold col-md-6">Transfer Balance:</strong>
                            <strong class="bold col-md-6">
                                {{ round($data['transfer_balance'], 2) }}
                                {{ $data['freelancer_detail']['default_currency'] }}
                            </strong>
                            <br />
                            <strong class="bold col-md-6">Pending Balance:</strong>
                            <strong class="bold col-md-6">
                                {{ $data['pending_balance'] ? round($data['pending_balance'], 2) : 0 }}
                                {{ $data['freelancer_detail']['default_currency'] }}
                            </strong>
                            <br />
                            <strong class="bold col-md-6">Available Balance:</strong>
                            <strong class="bold col-md-6">
                                {{ $data['available_balance'] ? round($data['available_balance'], 2) : 0 }}
                                {{ $data['freelancer_detail']['default_currency'] }}
                            </strong>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
            <?php $freelancer_uuid = $data['freelancer_uuid']; ?>
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#subscribers" data-toggle="tab">Subscribers </a>
                        </li>
                        <li>
                            <a href="#clients" data-toggle="tab">Clients</a>
                        </li>
                        <li>
                            <a href="#payment_requests" data-toggle="tab">Withdraw History</a>
                        </li>
                        <li>
                            <a href="#all_transactions" data-toggle="tab">All Transactions</a>
                        </li>
                        <li>
                            <a href="#pending_transactions" data-toggle="tab">Pending Transactions</a>
                        </li>
                        <li>
                            <a href="#available_transactions" data-toggle="tab">Available Transactions</a>
                        </li>
                        <li>
                            <a href="#freelancer_appointments" data-toggle="tab">Appoinments</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div style="overflow-y: scroll;max-height: 500px" class="tab-pane active" id="subscribers">
                            <table class="table table-striped table-bordered table-hover" id="classessTable">
                                <thead>
                                    <tr>
                                        <th>Subscriber Name</th>
                                        <th>Subscription</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th class="text-right">Amount Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['freelancer_subcribers']['subscribers'] != [])
                                        @foreach ($data['freelancer_subcribers']['subscribers'] as $subscribers)
                                            <tr>
                                                <td style="vertical-align: inherit;">
                                                    <a
                                                        href="{{ route('customerDetailPage', ['uuid' => $subscribers['subscriber_uuid']]) }}">
                                                        {{ $subscribers['first_name'] . ' ' . $subscribers['last_name'] }}
                                                    </a>
                                                </td>
                                                <td style="vertical-align: inherit;">
                                                    {{ ucfirst($subscribers['subscription_type']) }}
                                                </td>
                                                <td style="vertical-align: inherit;">
                                                    {{ $subscribers['subscription_start'] }}
                                                </td>
                                                <td style="vertical-align: inherit;">
                                                    {{ $subscribers['subscription_end'] }}
                                                </td>
                                                <td class="text-right" style="vertical-align: inherit;">
                                                    {{ $subscribers['total_amount'] }}
                                                    <b>{{ $subscribers['currency'] }}</b>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="clients">
                            <table class="table table-striped table-bordered table-hover" id="classessTable">
                                <thead>
                                    <tr>
                                        <th>Client Name </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['freelancer_clients'] != [])
                                        @foreach ($data['freelancer_clients'] as $clients)
                                            <tr>
                                                <td style="vertical-align: inherit;">
                                                    <a
                                                        href="{{ route('customerDetailPage', ['uuid' => $clients['customer_uuid']]) }}">
                                                        {{ $clients['first_name'] . ' ' . $clients['last_name'] }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="payment_requests">
                            <div style="overflow-y: scroll;max-height: 500px">
                                <table class="table table-striped table-bordered table-hover"
                                    id="payment_request_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> Status</th>
                                            <th class="text-center"> Payment Date </th>
                                            <th class="text-right"> Withdraw Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['withdraw_history'] != [])
                                            @foreach ($data['withdraw_history'] as $ind => $withdraw)
                                                <tr>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ config('arrays.withdraw_statuses.' . $withdraw['schedule_status'] . '.color') }}">
                                                            {{ config('arrays.withdraw_statuses.' . $withdraw['schedule_status'] . '.text') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date('Y-m-d H:i:s', strtotime($withdraw['created_at'])) }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ round($withdraw['amount'], 2) }}<b> {{ $withdraw['currency'] }} </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="all_transactions">
                            <div style="overflow-y: scroll;max-height: 500px">
                                <table class="table table-striped table-bordered table-hover"
                                    id="all_transactions_table">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Transaction Id </th>
                                            <th> Service Name </th>
                                            <th> price</th>
                                            <th> Purchase Date</th>
                                            <th> Freelancer Earned</th>
                                            <th> Customer Paid</th>
                                            <th> Status</th>
                                            {{-- <th> Action </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['all_transactions'] != [])
                                            @foreach ($data['all_transactions'] as $ind => $trans)
                                                <tr>
                                                    <td> {{ $ind + 1 }} </td>
                                                    <td> {{ $trans['transaction_id'] ?? '' }} </td>
                                                    <td> {{ $trans['name'] ?? '' }} </td>
                                                    <td> {{ round($trans['price'], 2) }} {{ $trans['currency'] }}
                                                    </td>
                                                    <td> {{ $trans['purchase_date'] ?? '' }}
                                                        at
                                                        {{ date('h:i a', strtotime($trans['purchase_time'])) ?? '' }}
                                                    </td>
                                                    <td> {{ round($trans['earned_amount_by_freelancer'], 2) }}
                                                        {{ $trans['currency'] }}</td>
                                                    <td> {{ round($trans['amount_paid_by_customer'], 2) }}
                                                        {{ $trans['purchased_currency'] }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ config('arrays.transaction_statuses.' . $trans['status'] . '.color') }}">
                                                            {{ config('arrays.transaction_statuses.' . $trans['status'] . '.text') }}
                                                        </span>
                                                    </td>
                                                    {{-- <td> <a href="#" class="btn btn-xs green">Detail</a> </td> --}}
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="pending_transactions">
                            <div style="overflow-y: scroll;max-height: 500px">
                                <table class="table table-striped table-bordered table-hover"
                                    id="pending_transactions_table">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Transaction Id </th>
                                            <th> Service Name </th>
                                            <th> price</th>
                                            <th> Purchase Date</th>
                                            <th> Customer Paid</th>
                                            <th> Freelancer Earned</th>
                                            <th> Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['pending_transactions'] != [])
                                            @foreach ($data['pending_transactions'] as $ind => $trans)
                                                <tr>
                                                    <td> {{ $ind + 1 }} </td>
                                                    <td> {{ $trans['transaction_id'] ?? '' }} </td>
                                                    <td> {{ $trans['name'] ?? '' }} </td>
                                                    <td> {{ round($trans['price'], 2) }} {{ $trans['currency'] }}
                                                    </td>
                                                    <td> {{ $trans['purchase_date'] ?? '' }}
                                                        at
                                                        {{ date('h:i a', strtotime($trans['purchase_time'])) ?? '' }}
                                                    </td>
                                                    <td> {{ round($trans['earned_amount_by_freelancer'], 2) }}
                                                        {{ $trans['currency'] }}</td>
                                                    <td> {{ round($trans['amount_paid_by_customer'], 2) }}
                                                        {{ $trans['currency'] }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ config('arrays.transaction_statuses.' . $trans['status'] . '.color') }}">
                                                            {{ config('arrays.transaction_statuses.' . $trans['status'] . '.text') }}
                                                        </span>
                                                    </td>
                                                    {{-- <td> <a href="#" class="btn btn-xs green">Detail</a> </td> --}}
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="available_transactions">
                            <div style="overflow-y: scroll;max-height: 500px">
                                <table class="table table-striped table-bordered table-hover"
                                    id="available_transactions_table">
                                    <thead>
                                        <tr>
                                            <th> Transaction Id </th>
                                            <th> Earn Type </th>
                                            <th> Purchase Date</th>
                                            <th> Earn Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['availble_transactions'] != [])
                                            @foreach ($data['availble_transactions'] as $ind => $trans)
                                                <tr>
                                                    <td> {{ $trans['transaction_id'] ?? '' }} </td>
                                                    <td>
                                                        {{$trans['type']}}
                                                    </td>
                                                    <td> {{ date('Y-m-d H:i:s', strtotime($trans['purchase_date'])) ?? '' }}
                                                    </td>
                                                    <td> {{ isset($trans['earned_amount_by_freelancer']) ? round($trans['earned_amount_by_freelancer'], 2) : 0 }}
                                                        {{ $trans['currency'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="freelancer_appointments">
                            <div style="overflow-y: scroll;max-height: 500px">
                                <table class="table table-striped table-bordered table-hover"
                                    id="available_transactions_table">
                                    <thead>
                                        <tr>
                                            <th> #</th>
                                            <th> Title</th>
                                            <th> Date</th>
                                            <th> Customer Name</th>
                                            <th> Services</th>
                                            <th> Timings</th>
                                            <th class="text-center"> Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data['freelancer_appointments'])
                                            @foreach ($data['freelancer_appointments'] as $appointment)
                                                <tr>
                                                    <td style="vertical-align: inherit;"> {{ $loop->index + 1 }}
                                                    </td>
                                                    <td style="vertical-align: inherit;">
                                                        <a
                                                            href="{{ route('getAppointment', ['uuid' => $appointment['appointment_uuid']]) }}">
                                                            {{ $appointment['appointment_title'] ?? '' }}
                                                        </a>
                                                    </td>
                                                    <td style="vertical-align: inherit;">
                                                        {{ $appointment['appointment_date'] }}
                                                    </td>
                                                    <td style="vertical-align: inherit;">
                                                        {{ $appointment['appointment_customer'] }}
                                                    </td>
                                                    <td width="20%" style="vertical-align: inherit;">
                                                        @foreach ($appointment['service_arr'] as $service)
                                                            <span>{{ $service }}</span><br>
                                                        @endforeach
                                                    </td>
                                                    <td style="vertical-align: inherit;">
                                                        {{ $appointment['appointment_start_time'] . ' to ' . $appointment['appointment_end_time'] }}
                                                    </td>
                                                    <td style="vertical-align: inherit;text-align: center;">
                                                        <span
                                                            class="text-bold-700 badge badge-{{ config('arrays.transaction_statuses.' . $appointment['appointment_status'] . '.color') }}">
                                                            {{ config('arrays.transaction_statuses.' . $appointment['appointment_status'] . '.text') }}
                                                        </span>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
