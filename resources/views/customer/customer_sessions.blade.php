<?php
$customer_sessions = $data['customer_sessions'];
?>
<table class="table table-striped table-bordered table-hover" id="sessionsTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Session Title</th>
            <th> Session Date</th>
            <th> Freelancer Name</th>
            <th> Services </th>
            <th> Price</th>
            <th> Session Timings</th>
            <th> Status</th>
        </tr>
    </thead>
    <tbody>
    @if($customer_sessions)
        @foreach($customer_sessions as $session)
            <tr>
                <td style="vertical-align: inherit;"> {{ $session['id'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['appointment_title'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['appointment_date'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['appointment_freelancer'] }} </td>
                <td width="20%" style="vertical-align: inherit;">
                    @foreach($session['service_arr'] as $sessionService)
                        <span>{{ $sessionService }}</span><br>
                    @endforeach
                </td>
                <td style="vertical-align: inherit;text-align: center;"> {{ $session['appointment_price'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['appointment_start_time'].' to '.$session['appointment_end_time'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['appointment_status']}} </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(function () {
        $("#sessionsTable").dataTable( {
            "oLanguage": {
                "sEmptyTable": "No Session Found"
            }
        });
    })
</script>
