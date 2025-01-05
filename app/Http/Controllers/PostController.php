<?php

namespace App\Http\Controllers;

use App\Helpers\PostHelper;
use App\Helpers\CommonMessageHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ReportedPost;


class PostController extends Controller
{

    public function getReportedPost() {
    	try {
    		$title = "Reported Post";
        	$reported_posts = PostHelper::getReportedPost();
        	return view('post.reported_post_listing', compact('reported_posts', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updatePostStatus(Request $request) {
        try{
            $inputs = $request->all();
            return $posts = PostHelper::updatePostStatus($inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getPostDetail($id) {
        try{
            $postDetail = PostHelper::getPostDetail($id);
            return view('post/post_detail_page', compact('postDetail'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getBlockedPostDetail($id) {
        try{
            $postDetail = PostHelper::getBlockedPostDetail($id);
            return view('post/post_detail_page', compact('postDetail'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function updateReportedPost(Request $request) {
        try{
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $file = $request->file('post_picture');
            return  PostHelper::updateReportedPost($file, $inputs);
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

    public function getBlockedPosts()
    {
        try{
            $title = "Blocked Post";
            $reported_posts = PostHelper::getBlockedPosts();
            return view('post.blocked_post_listing', compact('reported_posts', 'title'));
        } catch (\Exception $ex) {
            CommonHelper::storeException($ex);
            return CommonHelper::CommonExceptions($ex);
        }
    }

}
