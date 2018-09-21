<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'user_id' => 1,
            'venue_id' => 1,
            'media_type' => 1,
            'media_url' => "1_5ba0f9bf5f78b.png", //TODO:: add relevant filename
            'comment' => "This is a test comment. No big deal!",
            'anonymous' => 1,
            'type' => 1,
            'shared' => 1,
            'status' => 1,
            'created_at'    =>now(),
            'updated_at' => now()
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'venue_id' => 1,
            'media_type' => 2,
            'media_url' => "1_5b6d6b2daf77b.mp4", //TODO:: add relevant filename
            'comment' => "This is a test comment for a video. No big deal!",
            'anonymous' => 1,
            'type' => 1,
            'shared' => 1,
            'status' => 1,
            'created_at'    =>now(),
            'updated_at' => now()
        ]);
    }
}
