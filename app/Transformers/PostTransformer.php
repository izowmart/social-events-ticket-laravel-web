<?php

namespace App\Transformers;

use App\Post;
use App\Share;
use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    private $user_id;
    private $user;

    /**
     * A Fractal transformer.
     *
     * @param \App\Post $post
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id'                => $post->id,
            'username'          => $post->user->username,
            'venue'             => $post->venue->name,
            'media_type'        => $post->media_type,
            'media_url'         => $post->media_url,
            'liked'             => $this->user->likesPost($post->id),
            'shared'            => $post->shared($this->user_id),
            'my_shared_post'    => $post->my_shared_post($this->user_id),
            'friend_post'       => $post->friends_post($this->user_id),
            'original_post'     => $post->original_post(),
            'comment'           => $post->comment,
            'status'            => $post->status == null ? 1 : $post->status,
            'created_at'        => Carbon::parse($post->created_at)->toDateTimeString(),
            'updated_at'        => Carbon::parse($post->updated_at)->toDateTimeString(),
        ];
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        $this->user = User::find($this->user_id);
    }

}
