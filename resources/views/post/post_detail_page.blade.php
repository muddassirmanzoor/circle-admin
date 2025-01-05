@extends('layouts.main')
@section('content')
    <div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-th-list"></i> Post Detail
                </div>
            </div>
            <div class="portlet-body form">
                @if( \Session::has('success_message'))
                    <div class="alert alert-success">
                        <button class="close" data-close="alert"></button>
                        <span> {{ \session::get('success_message') }} </span>
                    </div>
                @endif
                @if( \Session::has('error_message'))
                    <div class="alert alert-danger">
                        <button class="close" data-close="alert"></button>
                        <span> {{ \session::get('error_message') }} </span>
                    </div>
                @endif
                <form action="{{ route('updateReportedPost') }}" enctype="multipart/form-data" id="" class="form-horizontal" method="post">
                    @csrf
                    <input type="hidden" name="post_uuid" id="post_uuid" value="{{ $postDetail['post_uuid']}}">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Post Title</label>
                            <div class="col-md-4"> 
                                <input type="text" class="form-control" name="caption" value="{{  isset($postDetail['caption']) ? $postDetail['caption'] : '' }} ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Post Text</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="text" rows="12">{{ isset($postDetail['text']) ? $postDetail['text'] : '' }}</textarea>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Post Type</label>
                            <div class="col-md-4">
                                <select class="form-control" name="post_type" id="post_type" required>
                                    <option value=""> -- Select -- </option>
                                    <option value="paid"  {{ ( $postDetail['post_type'] == 'paid') ? 'selected' : '' }} >paid</option>
                                    <option value="unpaid" {{ ( $postDetail['post_type'] == 'unpaid') ? 'selected' : '' }} >unpaid</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Media Type</label>
                            <div class="col-md-4">
                                <select class="form-control" name="media_type" id="media_type" required>
                                    <option value=""> -- Select -- </option>
                                    <option value="video"  {{ ( $postDetail['media_type'] == 'video') ? 'selected' : '' }} >Video</option>
                                    <option value="image" {{ ( $postDetail['media_type'] == 'image') ? 'selected' : '' }} >Image</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        @if(!empty($postDetail['image']))
                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                    <?php
                                        $image = config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['post_image'] . $postDetail['image']['post_image'];
                                        ?>
                                    <div class="col-md-8">
                                        <img class="timeline-body-img pull-left center" src="{{ $image }}" height="400px" width="400px" src="" id="picture_preview" alt="">
                                    </div>
                                    <br/>
                                    <!-- <div class="col-md-8">
                                        <label class="control-label col-md-5"></label>
                                        <input type="file" name="post_picture" id="post_picture" >
                                        <input type="hidden" name="old_value" id="old_value" value="{{  isset($postDetail['image']['post_image']) ? $postDetail['image']['post_image']: '' }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="control-label col-md-5"></label>
                                        <button type="button" id="delete_post_picture" class="btn btn-danger hidden">Remove</button>
                                    </div> -->
                            </div>
                        @endif

                        @if(!empty($postDetail['video']))
                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <?php
                                    $video = config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['post_video'] . $postDetail['video']['post_video'];
                                    ?>
                                <div class="col-md-8">
                                    <video height="300px" controls>
                                        <source src="{{{ $video }}}" type="video/mp4">
                                    </video>
                                    <!-- <input type="file" name="post_picture" id="post_picture" >
                                    <input type="hidden" name="old_value" id="old_value" value="{{  isset($postDetail['video']['post_video']) ? $postDetail['video']['post_video']: '' }}"> -->
                                </div>
                            </div>
                        @endif
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Update</button>
                                    <button type="button" class="btn default">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="{!! asset('public') !!}/assets/js/post.js" type="text/javascript"></script>


@endsection
