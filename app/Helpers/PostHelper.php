<?php

namespace App\Helpers;

use DB;
use App\ReportedPost;
use App\Post;
use App\PostImage;
use App\PostLog;
use Illuminate\Support\Facades\Validator;

class PostHelper
{
    public static function getReportedPost()
    {
        return ReportedPost::getReportedPost();
    }

    public static function updatePostStatus($inputs)
    {
        if($inputs['is_blocked'] == 1){
            $update = ['is_blocked' => $inputs['is_blocked'], 'is_archive' => 1];
        }
        else {
            $update = ['is_blocked' => $inputs['is_blocked'], 'is_archive' => 0];
        }
        if(!Post::updatePostData('post_uuid', $inputs['post_uuid'], $update)) {
            DB::rollBack();
            return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error')['update_error']);
        }
        $data['profile_log_uuid'] = $inputs['reporter_uuid'];
        $data['comments'] = $inputs['reason'];
        PostLog::createReaosn($data);
        DB::commit();
        return CommonHelper::jsonSuccessResponse(MessageHelper::getMessageData('success')['successful_request']);
    }

    public static function getPostDetail($post_uuid)
    {
        return Post::getPostDetail('post_uuid',$post_uuid);
    }

    public static function getBlockedPostDetail($post_uuid)
    {
        return Post::getBlockedPostDetail('post_uuid',$post_uuid);
    }

    public static function updateReportedPost($file, $inputs = [])
    {
        if (!empty($file)) {
            $upload_image = CommonHelper::uploadSingleImage($file, CommonHelper::$s3_image_paths['post_image']);
            if (!$upload_image['success']) {
                return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['image_not_uploaded']);
            }
            $inputs['media'] = $upload_image['file_name'];
            //return redirect()->back()->with('error_message', MessageHelper::getMessageData('error')['file_not_found']);
        }
        
        $post_inputs = self::processUpdatePostInputs($inputs);
        $service = new ApiService();
        $res = $service->guzzleRequest('POST','updatePost', $post_inputs);
        if($res['success']=="true") {
            return redirect()->back()->with('success_message', CommonMessageHelper::getMessageData('success')['update_post_success']);
        }
        else {
            return redirect()->back()->with('error_message', CommonMessageHelper::getMessageData('error')['general_error']);
        }
    }

    public static function getBlockedPosts()
    {
        $where = ['is_blocked' => 1, 'is_archive' => 1];
        return Post::getBlockedPosts($where);
    }

    public static function processUpdatePostInputs($inputs) {
        $post_inputs = [
            'post_uuid' => $inputs['post_uuid'],
            'caption' => (!empty($inputs['caption'])) ? $inputs['caption'] : "",
            'text' => (!empty($inputs['text'])) ? $inputs['text'] : "",
            'post_type' => $inputs['post_type'],
            'media_type' => $inputs['media_type'],
            'media' => (!empty($inputs['post_picture'])) ? $inputs['media'] : $inputs['old_value'],
        ];
        return $post_inputs;
    }

}
