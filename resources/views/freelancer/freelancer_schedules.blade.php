<?php
$freelancer_schedule = $data['freelancer_schedule'];
?>
<table class="table table-striped table-bordered table-hover" id="scheduleTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Day </th>
            <th> Schedule Timings</th>
        </tr>
    </thead>
    <tbody>
        @if ($freelancer_schedule)
            @foreach ($freelancer_schedule as $schedule)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $schedule['id'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $schedule['day'] }} </td>
                    <td style="vertical-align: inherit;">
                        {{ $schedule['schedule_start_time'] . ' to ' . $schedule['schedule_end_time'] }} </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $(function() {
        $("#scheduleTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Schedule found"
            }
        });
    })
</script>

<!-- <div id="freelacneScheduleCalendar" class="has-toolbar"> </div>
@if ($freelancer_uuid)
<input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ $freelancer_uuid }}">
@endif -->
