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
            'media_url' => "http://fika3-web.site/storage/images/adverts/advert_1536323726.jpg",
            'comment' => "This is a test comment. No big deal!",
            'anonymous' => 1,
            'type' => 1,
            'shared' => 1,
            'status' => 1,
            'created_at'    =>now(),
            'updated_at' => now()
        ]);
    }
}
