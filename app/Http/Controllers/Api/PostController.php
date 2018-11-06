<?php

namespace App\Http\Controllers\Api;

use App\Abuse;
use App\Http\Traits\SendFCMNotification;
use App\Http\Traits\UniversalMethods;
use App\Like;
use App\Notification;
use App\Post;
use App\Share;
use App\Transformers\PostTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all();
            $postTransformer = new PostTransformer();
            $user_id = \request()->user()->id;
            $postTransformer->setUserId($user_id);
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($posts),
                    "data" => fractal($posts, $postTransformer),
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

    /**
     * Get posts created by my following or
     * posts shared by my following or
     * posts that I have shared
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function friends_posts(Request $request)
    {
        try {
            $user = request()->user();

            if ($user != null) {
                $userTransformer = new UserTransformer();
                $userTransformer->setUserId($user->id);

                $postTransformer = new PostTransformer();
                $postTransformer->setUserId($user->id);

                //get the user followers
                $user_following = $user->following;

                //get friends shared posts
                $posts = Post::whereIn('user_id', $user_following->toArray())
                    ->get();

                return response()->json([
                    'success' => true,
                    'message' => 'Found ' . count($posts) . ' posts',
                    'friends' => fractal($user_following, $userTransformer),
                    'posts'   => fractal($posts, $postTransformer),
                ],200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Found 0 posts',
                    'friends' => [],
                    'posts'   => [],
                ],200);
            }
        } catch ( \Exception $exception ) {
            return response()->json([
                'success' => false,
                'message' => 'Failed '.$exception->getMessage(),
                'friends' => [],
                'posts'   => [],
            ],500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id'       => 'required|exists:users,id',
                'venue_id'      => 'required|exists:venues,id',
                'media_type'    => 'required',
                'media_file'    => 'required|file',
                'feed_viewers'  => 'required',
                'comment'       => 'nullable|min:2',
            ],
            [
                'user_id.required'      => 'Kindly log in to continue!',
                'user_id.exists'        => 'Kindly log in to continue',
                'venue_id.required'     => 'Kindly log in to continue!',
                'venue_id.exists'       => 'Kindly log in to continue',
                'media_type.required'   => 'Something is wrong with the uploaded media file!',
                'comment.min'           => 'You comment should be at least 2 characters!',
                'feed_viewers.required'                  => "Confirm who you'd like to see your post",

            ]
            );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'datum'    => []
                ], 200
            );
        }

        $user_id = $request->input('user_id');
        $venue_id = $request->input('venue_id');
        $media_type = $request->input('media_type');
        $type = $request->input('feed_viewers');

//        $shared = $request->has('shared') ? $request->input('shared') : false ;
        $comment = $request->has('comment') ? $request->input('comment') : null;
        $anonymous = $request->has('anonymous') ? $request->input('anonymous') : false ;

        try {
            if ($media_type == 1) {
                $file_name = $user_id . "_" . uniqid() . '.'.$request->file('media_file')->getClientOriginalExtension();;

                $file_path = "posts/images/";

                $image = $request->file("media_file");

                $success = Storage::disk('uploads')->put($file_path.$file_name, File::get($image));

            } else {
                $file_name = $user_id . "_" . uniqid() . '.'.$request->file('media_file')->getClientOriginalExtension();

                $file_path = "posts/videos/";

                $video = $request->file("media_file");

                $success = Storage::disk('uploads')->put($file_path.$file_name, File::get($video));
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
//                $post->shared = $shared;
                $post->save();

                //todo:: notify the users followers that they have new content???

                $postTransformer = new PostTransformer();
                $postTransformer->setUserId($user_id);

                return Response::json(array(
                    "success" => true,
                    "message" => "post successfully saved",
                    "datum" => fractal($post,$postTransformer),
                ),200);
            } else {
                return Response::json(array(
                    "success" => false,
                    "message" => "post not saved",
                    "datum" => [],
                ),200);
            }


        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error saving post" . $exception,
                "data" => [],

            ),500);
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
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'post_id' => 'required|exists:posts,id',
            ],
            [
                'user_id.required' => 'Kindly Login In!',
                'user_id.exists'    => 'Kindly Sign Up!',
                'post_id.required' => 'Kindly Login In!',
                'post_id.exists'    => 'Kindly Sign Up!'
            ]

        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'datum'    => null
                ], 200
            );
        }

        $user_id = $request->input('user_id');
        $post_id = $request->input('post_id');
        $liked = Like::where("user_id", $user_id)->where("post_id", $post_id)->first();
        if ($liked == null) {
            $post = Post::find($post_id);
            $user = User::find($user_id);

            $like = new Like();
            $like->user_id = $user_id;
            $like->post_id = $post_id;
            $like->save();

            //raise a new notification for the like
            $notification = new Notification();
            $notification->initializer_id = $user_id;
            $notification->recipient_id = $post->user_id;
            $notification->type = Notification::LIKE_NOTIFICATION;
            $notification->model_id = $post->id;
            $notification->save();

            $postTransformer = new PostTransformer();
            $postTransformer->setUserId($user_id);

            //FCM notification for the like to post owner,
            //if the post owner is not the one who has done the liking
            if ($user->id != $post->user_id) {
                $recipient = User::find($post->user_id);

                $token = [$recipient->fcm_token];

                $data = [
                    'message' => '' . $recipient->username . ' liked your post'
                ];

                if (count($token) > 0) {
                    SendFCMNotification::sendNotification($token, $data);
                }
            }

            return Response::json(array(
                "success" => true,
                "message" => "You have liked a post",
                'datum'  => fractal($post, $postTransformer)
            ));
        } else {
            //unlike the post
            $liked->delete();

            $post = Post::find($post_id);
            $postTransformer = new PostTransformer();
            $postTransformer->setUserId($user_id);


            return Response::json(array(
                "success" => true,
                "message" => "You had un-liked this post",
                'datum'   => fractal($post, $postTransformer),
            ));
        }

    }

    public function report_abuse(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'post_id' => 'required|exists:posts,id',
                'type'    => 'required'
            ],
            [
                'user_id.required' => 'Kindly Login In!',
                'user_id.exists'    => 'Kindly Sign Up!',
                'post_id.required' => 'Kindly Login In!',
                'post_id.exists'    => 'Kindly Sign Up!'
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
        $post_id = $request->input('post_id');
        $type = $request->input('type');

        $report_abuse = Abuse::where("user_id", $user_id)->where("post_id", $post_id)->first();

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

    public function share(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'post_id' => 'required|integer|exists:posts,id'
                ], [
                    'post_id.required' => 'Kindly login in!',
                    'post_id.integer'  => 'Kindly login in!',
                    'post_id.exists'   => 'Kindly sign up!'
                ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'datum'   => null
                    ], 200
                );
            }

            $post_id = $request->post_id;

            //authenticated user
            $user = request()->user();

            //find original post
            $post = Post::find($post_id);

            //create new shared post
            DB::beginTransaction();
            $new_post = new Post();
            $new_post->user_id = $post->user_id;
            $new_post->venue_id = $post->venue_id;
            $new_post->media_type = $post->media_type;
            $new_post->media_url = $post->media_url;
            $new_post->comment = $post->comment;
            $new_post->anonymous = $post->anonymous;
            $new_post->type = $post->type;
            $new_post->save();

            //shared record
            $share = Share::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'original_post_id' => $post->id
                ],
                [
                'new_post_id'   => $new_post->id,
                ]
            );


            //if sharing user is not the creator of the post,
            //notify the creator of the share...
            if($user->id != $post->user->id) {

                //create a notification record
                $notification = new Notification();
                $notification->initializer_id = $user->id;
                $notification->recipient_id = $post->user_id;
                $notification->type = Notification::SHARE_NOTIFICATION;
                $notification->model_id = $post->id;
                $notification->save();

                DB::commit();
                //raise an FMC notification for the post owner
                $recipient = User::find($post->user_id);
                $data = [
                    'message' => $user->name . ' has shared your post'
                ];

                if (!empty($recipient->fcm_token)) {
                    SendFCMNotification::sendNotification([$recipient->fcm_token], $data);
                }
            }

            $postTransformer = new PostTransformer();
            $postTransformer->setUserId($user->id);
            $data= [0=> $post, 1 => $new_post];

            return response()->json([
                'success' => true,
                'message' => 'post shared successfully!',
                'datum' => fractal($data, $postTransformer),
            ]);

        } catch ( \Exception $exception ) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'post share failed '.$exception->getMessage(),
                'datum'  => null
            ]);
        }
    }
}
