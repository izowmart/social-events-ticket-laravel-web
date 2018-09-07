<?php

namespace App\Http\Controllers\Api;

use App\Abuse;
use App\Http\Resources\PostResource;
use App\Http\Traits\UniversalMethods;
use App\Like;
use App\Notification;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all()->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($posts),
                    "data" => PostResource::make($posts),
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
        $validator = Validator::make($request->all(),
            [
                'user_id'       => 'required|exists:users,id',
                'venue_id'      => 'required|exists:venues,id',
                'media_type'    => 'required',
                'type'          => 'required',
                'comment'       => 'sometimes|min:2',
            ],
            [
                'user_id.required'      => 'Kindly log in to continue!',
                'user_id.exists'        => 'Kindly log in to continue',
                'venue_id.required'     => 'Kindly log in to continue!',
                'venue_id.exists'       => 'Kindly log in to continue',
                'media_type.required'   => 'Something is wrong with the uploaded media file!',
                'comment.min'           => 'You comment should be at least 2 characters!',
                'type.required'                  => "Confirm who you'd like to see your post"
            ]
            );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => []
                ], 200
            );
        }

        $user_id = $request->input('user_id');
        $venue_id = $request->input('venue_id');
        $media_type = $request->input('media_type');
        $type = $request->input('type');

        $shared = $request->has('shared') ? $request->input('shared') : false ;
        $comment = $request->has('comment') ? $request->input('comment') : null;
        $anonymous = $request->has('anonymous') ? $request->input('anonymous') : false ;

        try {
            if ($media_type == 1) {
                $file_name = $user_id . "_" . uniqid() . '.'.$request->file('image')->getClientOriginalExtension();;
                $file_path = "uploads/posts/images";
                $success = $request->file('image')->storeAs($file_path, $file_name);
            } else {
                $file_name = $user_id . "_" . uniqid() . '.'.$request->file('video')->getClientOriginalExtension();
                $file_path = "uploads/posts/videos";
                $success = $request->file('video')->storeAs($file_path, $file_name);
            }


            if ($success) {
                $media_url = $file_name;
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
                    "data" => PostResource::make($post),
                ));
            } else {
                return Response::json(array(
                    "success" => false,
                    "message" => "post not saved",
                    "data" => [],
                ));
            }


        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error saving post" . $exception,
                "data" => [],

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
