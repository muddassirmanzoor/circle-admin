<?php
$freelancer_sessions = $data['freelancer_sessions'];
?>
<table class="table table-striped table-bordered table-hover" id="sessionsTable">
    <thead>
    <tr>
        <th> # </th>
        <th> Service Name</th>
        <th> Session Date</th>
        <th> Session Price</th>
        <th> Session Timings</th>
        <th> Notes</th>
    </tr>
    </thead>
    <tbody>
    @if($freelancer_sessions)
        @foreach($freelancer_sessions as $session)
            <tr>
                <td style="vertical-align: inherit;"> {{ $session['id'] }} </td>
                <td width="20%" style="vertical-align: inherit;">
                    @foreach($session['service_arr'] as $service)
                        <span>{{ $service }}</span><br>
                    @endforeach
                </td>
                <td style="vertical-align: inherit;"> {{ $session['session_date'] }} </td   >
                <td style="vertical-align: inherit;text-align: center;"> {{ $session['session_price'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['session_start_time'].' to '.$session['session_end_time'] }} </td>
                <td style="vertical-align: inherit;"> {{ $session['session_notes'] }} </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(function () {
        $("#sessionsTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Sessions found"
            }
        });
    })
</script>
