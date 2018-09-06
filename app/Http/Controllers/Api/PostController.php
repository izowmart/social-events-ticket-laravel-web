<?php

namespace App\Http\Controllers\Api;

use App\Abuse;
use App\Like;
use App\Notification;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all()->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($posts),
                    "data" => $posts,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => "false",
                    "message" => "error fetching posts!" . $exception,
                )

            );
        }
    }

    public function store(Request $request)
    {
        $user_id = $request->input('user_id');
        $venue_id = $request->input('venue_id');
        $media_type = $request->input('media_type');
        $comment = $request->input('comment');
        $anonymous = $request->input('anonymous');
        $type = $request->input('type');
        $shared = $request->input('shared');

        try {
            if ($media_type == 1) {
                $file_path = "uploads/posts/images/" . $user_id . "_" . uniqid() . '.png';
                $success = move_uploaded_file($request->file('image'), $file_path);
            } else {
                $file_path = "uploads/posts/videos/" . $user_id . "_" . uniqid() . ".mp4";
                $success = move_uploaded_file($request->file('video'), $file_path);
            }


            if ($success) {
                $media_url = $file_path;
                $post = new Post();
                $post->user_id = $user_id;
                $post->venue_id = $venue_id;
                $post->media_type = $media_type;
                $post->media_url = $media_url;
                $post->comment = $comment;
                $post->anonymous = $anonymous;
                $post->type = $type;
                $post->shared = $shared;
                $post->save();
                return Response::json(array(
                    "success" => true,
                    "message" => "post successfully saved",
                    "data" => $post,
                ));
            } else {
                return Response::json(array(
                    "success" => false,
                    "message" => "post not saved",
                    "data" => $_FILES['video']['tmp_name'],
                ));
            }


        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error saving post" . $exception,
                "data" => $_FILES['video']['tmp_name'],

            ));
        }

    }

    public function delete(Request $request)
    {
        $post_id = $request->input('post_id');
        try {
            Post::where('id', $post_id)->delete();
            return Response::json(array(
                "success" => true,
                "message" => "post successfully deleted",
            ));
        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error deleting post",
            ));
        }
    }

    public function like(Request $request)
    {
        $user_id = $request->input('user_id');
        $post_id = $request->input('post_id');
        $report_abuse = Like::where("user_id", $user_id)->where("post_id", $post_id);
        if ($report_abuse == null) {
            $post = Post::where('id', $post_id);
            $user = User::where('id', $user_id)->username;
            $like = new Like();
            $like->user_id = $user_id;
            $like->post_id = $post_id;
            $like->save();
            $notification = new Notification();
            $notification->initializer_id = $user_id;
            $notification->recipient_id = $post->user_id;
            $notification->type = 1;
            $notification->message = $user . " liked your post";
            $notification->save();
            return Response::json(array(
                "success" => true,
                "message" => "You had liked a post",
            ));
        } else {
            return Response::json(array(
                "success" => false,
                "message" => "You had already liked this post",
            ));
        }

    }

    public function get_abuse(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $reported_abuses = Abuse::where('user_id', $user_id)->toarray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($reported_abuses),
                    "data" => $reported_abuses,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching reported abuses",
                )

            );
        }

    }

    public function report_abuse(Request $request)
    {
        $user_id = $request->input('user_id');
        $post_id = $request->input('post_id');
        $type = $request->input('type');

        $report_abuse = Abuse::where("user_id", $user_id)->where("post_id", $post_id);
        if ($report_abuse == null) {
            $abuse = new Abuse();
            $abuse->user_id = $user_id;
            $abuse->post_id = $post_id;
            $abuse->type = $type;
            $abuse->save();
            return Response::json(array(
                "success" => true,
                "message" => "Thank you for your response",
            ));
        } else {
            return Response::json(array(
                "success" => false,
                "message" => "You had reported abuse on this post, Please wait as the administrator handles it",
            ));
        }
    }


}
