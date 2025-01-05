@extends('layouts.main')
@section('title')
    Add Service
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="glyphicon glyphicon-th font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Add Service</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if (Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if (Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('error_message') }} </span>
                        </div>
                    @endif
                    <form role="form" method="post" enctype="multipart/form-data" class="form-horizontal"
                        action="{{ route('addSubCategory') }}">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_id">Industry</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="category_id" id="category_id" required>
                                        <option value="">-- Select --</option>
                                        @if (count($categories) > 0)
                                            @foreach ($categories as $catgory)
                                                <option value="{{ $catgory['id'] }}">{{ $catgory['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="sub_category_name">Service Name</label>
                                <div class="col-md-4">
                                    <input class="form-control spinner" id="sub_category_name" name="sub_category_name"
                                        type="text" placeholder="Please enter service name" value="" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="sub_category_picture">Service Picture</label>
                                <div class="col-md-4">
                                    <input type="file" name="sub_category_picture" id="sub_category_picture">
                                </div>
                                <div class="col-md-5">
                                    <img class="timeline-body-img pull-left center" height="200px" src=""
                                        id="picture_preview" alt="">
                                    <i class="icon-close fa-2x hidden" id="delete_sub_category_picture"
                                        style="padding: 5px;cursor: pointer;"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="sub_category_status">Service Status</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="sub_category_status" id="active_sub_category"
                                                value="0" checked> Active
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="sub_category_status" id="inactive_sub_category"
                                                value="1"> In-Active
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="sub_category_status">Service Type</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="is_online" id="active_sub_category" value="0" checked>
                                            Face to Face
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="is_online" id="inactive_sub_category" value="1">
                                            Online
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-center">
                            <button type="submit" class="btn blue">Submit</button>
                            <a href="{{ route('getAllSubCategories') }}" class="btn red">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
