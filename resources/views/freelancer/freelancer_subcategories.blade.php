<?php
$freelancer_subcategories = $data['freelancer_subcategories'];
?>
<table class="table table-striped table-bordered table-hover" id="subCategoryTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Industry Name </th>
            <th> Service Name </th>
            <th> Price </th>
        </tr>
    </thead>
    <tbody>
        @if ($freelancer_subcategories)
            @foreach ($freelancer_subcategories as $sub_category)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $sub_category['id'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $sub_category['category_name'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $sub_category['sub_category_name'] }} </td>
                    <td style="vertical-align: inherit;" class="text-right"> {{ $sub_category['price'] }} </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $(function() {
        $("#subCategoryTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Sub category found"
            }
        });
    })
</script>
