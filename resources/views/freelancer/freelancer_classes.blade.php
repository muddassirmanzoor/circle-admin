<?php
$freelancer_classes = $data['freelancer_classes'];
?>
<table class="table table-striped table-bordered table-hover" id="classesTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Class Name </th>
            <th> No of Students</th>
            <th> Class Price</th>
            <th> Class Timings</th>
            <th> Notes</th>
            <th> Address</th>
        </tr>
    </thead>
    <tbody>
        @if ($freelancer_classes)
            @foreach ($freelancer_classes as $class)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $class['id'] }} </td>
                    <td style="vertical-align: inherit;">
                        <a href="{{ route('freelancerClasses', ['id' => $class['class_uuid']]) }}">
                            {{ $class['class_name'] }}
                        </a>
                    </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $class['no_of_students'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $class['class_price'] }} </td>
                    <td style="vertical-align: inherit;">
                        {{ $class['class_start_time'] . ' to ' . $class['class_end_time'] }} </td>
                    <td style="vertical-align: inherit;">
                        {{ \Illuminate\Support\Str::words($class['class_notes'], 50) }} </td>
                    <td style="vertical-align: inherit;"> {{ $class['class_address'] }} </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $(function() {
        $("#classesTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Class found"
            }
        });
    })
</script>
