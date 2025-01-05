@extends('layouts.main')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> {{ $title }} </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="promoCode">
                        <thead>
                            <tr>
                                <th> #</th>
                                <th> Post Text</th>
                                <!-- <th> Reporter Name</th> -->
                                <th> Post Type</th>
                                <th> Comments</th>
                                <th> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($reported_posts)
                                @foreach ($reported_posts as $reported_post)
                                    <tr>
                                        <td> {{ $reported_post['id'] }} </td>
                                        <td><a
                                                href="{{ route('getBlockedPostDetail', ['id' => $reported_post['post_uuid']]) }}">
                                                {{ $reported_post['text'] != '' ? $reported_post['text'] : 'N-A' }}
                                            </a>
                                        </td>
                                        <td> {{ $reported_post['post_type'] }} </td>
                                        <td> {{ isset($reported_post['reported_post']) ? $reported_post['reported_post']['comments'] : 'No Comments'  }} </td>
                                        <td width="12%">
                                            <div class="btn-group">
                                                <button class="btn btn-xs green dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false"> Actions
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-left" role="menu">
                                                    <li>
                                                        <a href="javascript:void(0);" class="updatePost"
                                                            data-uuid="{{ $reported_post['post_uuid'] }}" data-value="0"
                                                            data-reported="{{ $reported_post['freelance_id'] }}">Un Block
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td valign="top" colspan="9" class="dataTables_empty">No records found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="postUpdateModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update Post</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to update this Post? </p>
                    <form class="bootbox-form">
                        <label for="notes">Reason</label>
                        <input class="bootbox-input bootbox-input-text form-control" id="report_post_reason"
                            name="report_post_reason" autocomplete="off" type="text">
                        <input type="hidden" id="is_blocked" name="is_blocked" value="" />
                        <input type="hidden" id="reported_by" name="reported_by" value="" />
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
    <script src="{{url('assets/js/post.js')}}" type="text/javascript"></script>
    <script>
        $(function() {
            $("#promoCode").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>
@endsection
