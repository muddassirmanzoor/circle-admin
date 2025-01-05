<?php
$customer_classes = $data['customer_classes'];
?>
<table class="table table-striped table-bordered table-hover" id="classesTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Class Title</th>
{{--            <th> Class Date</th>--}}
            <th> Freelancer Name</th>
{{--            <th> Services </th>--}}
            <th> Price</th>
            <th> Class Timings</th>
            <th> Status</th>
        </tr>
    </thead>
    <tbody>
    @if($customer_classes)
        @foreach($customer_classes as $class)
            <tr>
                <td style="vertical-align: inherit;"> {{ $class['id'] }} </td>
                <td style="vertical-align: inherit;"> {{ $class['appointment_title'] }} </td>
{{--                <td style="vertical-align: inherit;"> {{ $class['appointment_date'] }} </td>--}}
                <td style="vertical-align: inherit;"> {{ $class['appointment_freelancer'] }} </td>
{{--                <td width="20%" style="vertical-align: inherit;">--}}
{{--                    @foreach($class['service_arr'] as $classService)--}}
{{--                        <span>{{ $classService }}</span><br>--}}
{{--                    @endforeach--}}
{{--                </td>--}}
                <td style="vertical-align: inherit;text-align: center;"> {{ $class['appointment_price'] }} </td>
                <td style="vertical-align: inherit;"> {{ $class['appointment_start_time'].' to '.$class['appointment_end_time'] }} </td>
                <td style="vertical-align: inherit;"> {{ $class['appointment_status']}} </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(function () {
        $("#classesTable").dataTable( {
            "oLanguage": {
                "sEmptyTable":  "No Class Found"
            }
        });
    })
</script>
