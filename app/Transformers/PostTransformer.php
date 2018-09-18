<?php

namespace App\Transformers;

use App\Post;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
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
            'created_at'        => Carbon::parse($post->created_at)->toDateTimeString(),
            'media_type'        => $post->media_type,
            'media_url'         => $post->media_url,
            'like'              => (bool) count($post->like) == 0 ? false : true,
            'shared'            => (bool) $post->shared,
            'comment'           => $post->comment,
        ];
    }
}
