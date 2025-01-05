@extends('layouts.main')
@section('title')
    Edit Industry
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="glyphicon glyphicon-th font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Edit Industry</span>
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
                        action="{{ route('updateCategory') }}">
                        @csrf
                        <input type="hidden" name="category_uuid"
                            value="{{ isset($category_data['category_uuid']) ? $category_data['category_uuid'] : '' }}" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_name">Industry Name</label>
                                <div class="col-md-4">
                                    <input class="form-control spinner" id="category_name" name="category_name" type="text"
                                        placeholder="Please enter category name"
                                        value="{{ isset($category_data['name']) ? $category_data['name'] : '' }}"
                                        required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_picture">Industry Picture</label>
                                <div class="col-md-4">
                                    <input type="file" name="category_picture" id="category_picture">
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $image = '';
                                    if (isset($category_data['image'])) {
                                        $image = config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['category_image'] . $category_data['image'];
                                    }

                                    ?>
                                    <img class="timeline-body-img pull-left center" height="200px" src="{{ $image }}"
                                        id="picture_preview" alt="">
                                    <i class="icon-close fa-2x hidden" id="delete_category_picture"
                                        style="padding: 5px;cursor: pointer;"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Industry Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" rows="4" name="description"
                                        id="description">{{ isset($category_data['description']) ? $category_data['description'] : '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Customer Description</label>
                                <div class="col-md-4">
                                    <textarea required class="form-control" rows="4" name="customer_description"
                                        id="customer_description">{{ isset($category_data['customer_description']) ? $category_data['customer_description'] : '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="category_status">Industry Status</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio">
                                            <input type="radio" name="category_status" id="active_category" value="0"
                                                <?= isset($category_data['is_archive']) && $category_data['is_archive'] == 0 ? 'checked' : '' ?>
                                                checked> Active
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="category_status" id="inactive_category" value="1"
                                                <?= isset($category_data['is_archive']) && $category_data['is_archive'] == 1 ? 'checked' : '' ?>>
                                            In-Active
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-center">
                            <button type="submit" class="btn blue">Submit</button>
                            <a href="{{ route('getAllCategories') }}" class="btn red cancelClick">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
