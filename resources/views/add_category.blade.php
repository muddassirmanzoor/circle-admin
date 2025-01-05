@extends('layouts.main')
@section('title')
    Add Industries
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="glyphicon glyphicon-th font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Add Industries</span>
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
                        action="{{ route('addCategory') }}">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="categoryName">Industry Name</label>
                                <div class="col-md-4">
                                    <input class="form-control spinner" id="category_name" name="category_name" type="text"
                                        placeholder="Please enter industry name" value="" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_picture">Industry Picture</label>
                                <div class="col-md-4">
                                    <input type="file" name="category_picture" id="category_picture">
                                </div>
                                <div class="col-md-5">
                                    <img class="timeline-body-img pull-left center" height="200px" src=""
                                        id="picture_preview" alt="">
                                    <i class="icon-close fa-2x hidden" id="delete_category_picture"
                                        style="padding: 5px;cursor: pointer;"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Industry Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" rows="4" name="description"
                                        id="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Customer Description</label>
                                <div class="col-md-4">
                                    <textarea required class="form-control" rows="4" name="customer_description"
                                        id="customer_description"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Industry Status</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="category_status" id="active_category" value="0"
                                                checked> Active
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="category_status" id="inactive_category" value="1">
                                            In-Active
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-center">
                            <button type="submit" class="btn blue">Submit</button>
                            <a href="{{ route('getAllCategories') }}" class="btn red">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
