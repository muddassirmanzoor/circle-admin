@extends('layouts.main')
@section('title')
    Edit Service
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="glyphicon glyphicon-th font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Edit Service</span>
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
                        action="{{ route('updateSubCategory') }}">
                        @csrf
                        <input type="hidden" name="sub_category_uuid"
                            value="{{ isset($sub_category_data['sub_category_uuid']) ? $sub_category_data['sub_category_uuid'] : '' }}" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="categoryUuid">Industory</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="category_id" id="category_id" required>
                                        <option value="">-- Select --</option>
                                        @if (count($categories) > 0)
                                            @foreach ($categories as $catgory)
                                                <?php
                                                $selected = '';
                                                if (isset($sub_category_data['id']) && $catgory['id'] == $sub_category_data['category_id']) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="{{ $catgory['id'] }}" {{ $selected }}>
                                                    {{ $catgory['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="subCategoryName">Service Name</label>
                                <div class="col-md-4">
                                    <input class="form-control spinner" id="sub_category_name" name="sub_category_name"
                                        type="text" placeholder="Please enter sub category name"
                                        value="{{ isset($sub_category_data['name']) ? $sub_category_data['name'] : '' }}"
                                        required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="subCategoryPicture">Service Picture</label>
                                <div class="col-md-4">
                                    <input type="file" name="sub_category_picture" id="sub_category_picture">
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $image = '';
                                    if (isset($sub_category_data['image'])) {
                                        $image = config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['category_image'] . $sub_category_data['image'];
                                    }

                                    ?>
                                    <img class="timeline-body-img pull-left center" height="200px" src="{{ $image }}"
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
                                                value="0"
                                                <?= isset($sub_category_data['is_archive']) && $sub_category_data['is_archive'] == 0 ? 'checked' : '' ?>
                                                checked> Active
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="sub_category_status" id="inactive_sub_category"
                                                value="1"
                                                <?= isset($sub_category_data['is_archive']) && $sub_category_data['is_archive'] == 1 ? 'checked' : '' ?>>
                                            In-Active
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
                                            <input type="radio" name="is_online" id="is_online" value="0"
                                                <?= isset($sub_category_data['is_online']) && $sub_category_data['is_online'] == 0 ? 'checked' : '' ?>
                                                checked> Face to Face
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" name="is_online" id="is_online" value="1"
                                                <?= isset($sub_category_data['is_online']) && $sub_category_data['is_online'] == 1 ? 'checked' : '' ?>>
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
