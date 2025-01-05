<?php
$freelancer_categories = $data['freelancer_categories'];
?>
<table class="table table-striped table-bordered table-hover" id="categoryTable">
    <thead>
    <tr>
        <th> # </th>
        <th> Category Name </th>
    </tr>
    </thead>
    <tbody>
    @if($freelancer_categories)
        @foreach($freelancer_categories as $key => $category)
            <tr>
                <td style="vertical-align: inherit;"> {{ $key+1 }} </td>
                <td style="vertical-align: inherit;"> {{ $category['category_name'] }} </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(function () {
        $("#categoryTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Category found"
            }
        });
    })
</script>
