@extends('layouts.main')
@section('title') Professions @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Profession Listing</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided">
                            <a class="btn purple sbold" data-toggle="modal" id="addProfession"
                                href="#professionModel">
                                <i class="fa fa-plus-circle"></i>
                                Add Profession
                            </a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="professionTable">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Profession Name</th>
                                <th style="text-align: center"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($professions)
                                @foreach ($professions as $profession)
                                    <tr>
                                        <td style="vertical-align: inherit;"> {{ $loop->index + 1 }} </td>
                                        <td style="vertical-align: inherit;"> {{ $profession['name'] }} </td>
                                        <td style="vertical-align: inherit;text-align: center">
                                            <a href="{{ route('editProfession', ['id' => $profession['profession_uuid']]) }}"
                                                class="btn btn-sm green btn-outline filter-submit margin-bottom"> Edit </a>
                                            <button
                                                class="btn btn-sm red btn-outline filter-submit margin-bottom deleteProfession"
                                                data-uuid="{{ $profession['profession_uuid'] }}"> Delete </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <script>
                        $(function() {
                            $("#professionTable").dataTable({
                                "oLanguage": {
                                    "sEmptyTable": "No Profession found"
                                }
                            });
                        })
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="professionModel" tabindex="-1" role="basic" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title caption font-red">
                        <i class="glyphicon glyphicon-th font-red"></i>
                        <span class="caption-subject bold uppercase">Add Profession</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        @if (\Session::has('success_message'))
                            <div class="alert alert-success">
                                <button class="close" data-close="alert"></button>
                                <span> {{ \session::get('success_message') }} </span>
                            </div>
                        @endif
                        @if (\Session::has('error_message'))
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span> {{ \session::get('error_message') }} </span>
                            </div>
                        @endif
                        <form class="form-horizontal">
                            @csrf
                            <div class="form-body">

                                <input type="hidden" name="subscription_uuid" id="subscription_uuid" value="">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control spinner" name="name" id="name" value="">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark" data-dismiss="modal">Close</button>
                    <button type="button" class="btn green" id="saveProfession">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="static2" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Profession</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to delete this Profession? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green"
                        id="yes_delete_profession">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
