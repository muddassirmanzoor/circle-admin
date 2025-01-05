<?php
$freelancer_packages = $data['freelancer_packages'];
?>
<table class="table table-striped table-bordered table-hover" id="packagesTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Package Name </th>
            <th> Package Type</th>
            <th> No of Sessions</th>
            <th> No of Classes</th>
            <th> Package Description</th>
            <th> Package price</th>
            <th> Validity</th>
            <th> Validity Type</th>
        </tr>
    </thead>
    <tbody>
        @if ($freelancer_packages)
            @foreach ($freelancer_packages as $package)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $package['id'] }} </td>
                    <td style="vertical-align: inherit;">
                        <a href="{{ route('freelancerPackages', ['id' => $package['package_uuid']]) }}">
                            {{ $package['package_name'] }}
                        </a>
                    </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $package['package_type'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $package['package_type'] == 'session' ? $package['no_of_session'] : ''}} </td>
                    <td style="vertical-align: inherit;"> {{ $package['package_type'] == 'class' ? $package['no_of_session'] : ''}}</td>
                    <td style="vertical-align: inherit;">
                        {{ \Illuminate\Support\Str::words($package['package_description'], 50) }} </td>
                    <td style="vertical-align: inherit;"> {{ $package['price'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $package['package_validity'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $package['validity_type'] }} </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $(function() {
        $("#packagesTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Package found"
            }
        });
    })
</script>
