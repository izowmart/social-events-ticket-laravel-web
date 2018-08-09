<?php

namespace App\Http\Controllers\Api;

use App\Abuse;
use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all()->toArray();
            return Response::json(array(
                    "success" => "true",
                    "message" => "found " . count($posts),
                    "data" => $posts,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => "false",
                    "message" => "error fetching posts!",
                )

            );
        }
    }

    public function store(Request $request)
    {
        $user_id = $request->input('user_id');
        $venue_id = $request->input('venue_id');
        $media_type = $request->input('media_type');
        $media_data = $request->input('media_data');
        $comment = $request->input('comment');
        $anonymous = $request->input('anonymous');
        $type = $request->input('type');
        $shared = $request->input('shared');

        try {
            if ($media_type == 'image') {
                define('UPLOAD_DIR', 'images/');
                $img = str_replace('data:image/png;base64,', '', $media_data);
                $img = str_replace(' ', '+', $img);
                $img_file = base64_decode($img);
                $img_file_name = UPLOAD_DIR . $user_id . "_" . uniqid() . '.png';
                $disk = Storage::disk('uploads');
                $success =  $disk->put($img_file_name,$img_file);
                if ($success) {
                    $media_url = 'uploads/'.$img_file_name;
                    $post = new Post();
                    $post->user_id=$user_id;
                    $post->venue_id=$venue_id;
                    $post->media_type=$media_type;
                    $post->media_url=$media_url;
                    $post->comment=$comment;
                    $post->anonymous=$anonymous;
                    $post->type=$type;
                    $post->shared=$shared;
                    $post->save();
                    return Response::json(array(
                        "success" => "true",
                        "message" => "post successfully saved",
                        "data" => $post,
                    ));
                } else {
                    return Response::json(array(
                        "success" => "false",
                        "message" => "post not saved",
                    ));
                }

            } else if ($media_type == 'video') {
                define('UPLOAD_DIR', 'videos/');
            }
        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => "false",
                "message" => "error saving post",
            ));
        }

    }

    public function like(Request $request)
    {
        $user_id = $request->input('user_id');
        $post_id = $request->input('post_id');
        $report_abuse = Like::where("user_id", $user_id)->where("post_id", $post_id);
        if ($report_abuse == null) {
            $like = new Like();
            $like->user_id = $user_id;
            $like->post_id = $post_id;
            $like->save();
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

    public function abuse(Request $request)
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
                'success' => 'true',
                'message' => "Thank you for your response",
            ));
        } else {
            return Response::json(array(
                'success' => 'false',
                'message' => "You had reported abuse on this post, Please wait as the administrator handles it",
            ));
        }
    }
}
