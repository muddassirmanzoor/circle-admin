<?php
$customer_interests = $data['customer_interests'];
?>
<table class="table table-striped table-bordered table-hover" id="interestesTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Title </th>
            <th> Description </th>
            <th> Image </th>
        </tr>
    </thead>
    <tbody>
    @if($customer_interests)
        @foreach($customer_interests as $key => $interest)
            <tr>
                <td style="vertical-align: inherit;"> {{ $key + 1 }} </td>
                <td style="vertical-align: inherit;"> {{ $interest['category']['name'] }} </td>
                <td style="vertical-align: inherit;"> {{ $interest['category']['description'] }} </td>
                <td style="vertical-align: inherit;"> 
                    <?php
                        $image = config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['category_image'] . $interest['category']['image'];
                    ?>
                    <img class="timeline-body-img pull-left" height="120px" src="{{ $image }}" alt="">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(function () {
        $("#interestesTable").dataTable( {
            "oLanguage": {
                "sEmptyTable":  "No interest Found"
            }
        });
    })
</script>
