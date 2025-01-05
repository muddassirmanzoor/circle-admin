@extends('layouts.main')
@section('title')
    {{ $title }} Freelancers
@endsection
{{-- Old Code for Active / Inactive / Block Freelancer --}}
{{-- @php
$status = '';
$active_btn = '';
$block_btn = '';
$delete_btn = '';
if ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 0) {
    $status = 'Not Verified';
    $active_btn = true;
    $block_btn = false;
    $delete_btn = true;
} elseif ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 1) {
    $status = 'Not Verified and Deleted';
    $active_btn = true;
    $block_btn = false;
    $delete_btn = false;
}
if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 0) {
    if ($freelancer['is_active'] == 1) {
        $status = 'Active';
        $active_btn = false;
        $block_btn = true;
        $delete_btn = true;
    } else {
        $active_btn = true;
        $block_btn = false;
        $delete_btn = true;
        $status = 'Blocked';
    }
}
if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 1) {
    $status = 'Deleted';
    $active_btn = true;
    $block_btn = false;
    $delete_btn = false;
}
@endphp --}}
{{-- Old Code for Active / Inactive / Block Freelancer --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">{{ $title }} Freelancers</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="allFreelancers">
                        <thead>
                            <tr>
                                <th> #</th>
                                <th> Name</th>
                                <th> Email</th>
                                <th> Phone</th>
                                <th> Created at</th>
                                <th> Gender</th>
                                <th> Profession</th>
                                <th> Company</th>
                                <th> Status</th>
                                <th> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($freelancers)
                                @foreach ($freelancers as $freelancer)
                                    <tr>
                                        <td style="vertical-align: inherit;cursor: pointer;">
                                            {{ $loop->iteration }} </td>
                                        <td style="vertical-align: inherit;cursor: pointer;">
                                            <a
                                                href="{{ route('freelancerDetailPage', ['uuid' => $freelancer['freelancer_uuid']]) }}">
                                                {{ $freelancer['first_name'] }}
                                            </a>
                                        </td>
                                        <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['email'] }}
                                        </td>
                                        <td style="vertical-align: inherit;cursor: pointer;">
                                            {{ $freelancer['phone_number'] }} </td>
                                        <td style="vertical-align: inherit;cursor: pointer;">
                                            {{ \App\Helpers\CommonHelper::setUserDateFormat($freelancer['created_at']) }} </td>
                                        <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['gender'] }}
                                        </td>
                                        <td style="vertical-align: inherit;cursor: pointer;">
                                            {{ $freelancer['profession'] }} </td>
                                        <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['company'] }}
                                        </td>
                                        @php
                                            $status = '';
                                            $active_btn = '';
                                            $block_btn = '';
                                            $delete_btn = '';
                                            if ($freelancer['is_archive'] == 1) {
                                                $status = 'Deleted';
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = false;
                                            }
                                            if ($freelancer['is_active'] == 1 && $freelancer['is_archive'] == 0) {
                                                $status = 'Active';
                                                $active_btn = false;
                                                $block_btn = true;
                                                $delete_btn = true;
                                            } elseif($freelancer['is_active'] == 0 && $freelancer['is_archive'] == 0) {
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = true;
                                                $status = 'Not Active';
                                            }
                                        @endphp
                                        <td style="vertical-align: inherit;cursor: pointer;"> {{ $status }} </td>
                                        <td>
                                            <div class="btn-group">
                                                {{-- @if ($active_btn || $block_btn) --}}
                                                <button class="btn btn-xs green dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false"> Actions
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                {{-- @endif --}}
                                                @if ($delete_btn)
                                                    {{-- &nbsp;&nbsp; --}}
                                                    <a href="javascript:void(0);" class="btn btn-xs red deleteFreelancer"
                                                        data-uuid="{{ $freelancer['freelancer_uuid'] }}"
                                                        data-is_active="0" data-is_archive="1"> Delete</a>
                                                @endif
                                                <ul class="dropdown-menu pull-left" role="menu">
                                                    @if ($active_btn)
                                                        <li>
                                                            <a href="javascript:void(0);" class="updateFreelancer"
                                                                data-uuid="{{ $freelancer['freelancer_uuid'] }}"
                                                                data-value="1">Active</a>
                                                        </li>
                                                    @endif
                                                    @if ($block_btn)
                                                        <li>
                                                            <a href="javascript:void(0);" class="updateFreelancer"
                                                                data-uuid="{{ $freelancer['freelancer_uuid'] }}"
                                                                data-value="0">In Active </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
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

    <div class="modal fade" id="freelancersDeleteModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Freelacner</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to delete this Freelancer? </p>
                    <form class="bootbox-form">
                        {{-- <label for="notes">Reason</label>
                        <input class="bootbox-input bootbox-input-text form-control" id="freelancer_deletion_notes"
                            name="freelancer_deletion_notes" autocomplete="off" type="text"> --}}
                        <input type="hidden" id="is_active" name="is_active" value="" />
                        <input type="hidden" id="is_archive" name="is_archive" value="" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green"
                        id="yes_delete_freelancer">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="freelancersUpdateModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update Freelacner</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to update this Freelancer? </p>
                    <form class="bootbox-form">
                        {{-- <label for="notes">Reason</label>
                        <input class="bootbox-input bootbox-input-text form-control" id="freelancer_update_reason"
                            name="freelancer_update_reason" autocomplete="off" type="text"> --}}
                        <input type="hidden" id="status" name="status" value="" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green"
                        id="yes_update_freelancer">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $("#allFreelancers").dataTable({
                "pageLength": 25,
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>

@endsection
