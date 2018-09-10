<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('abuses')->insert([
            'user_id' => 1,
            'post_id' => 1,
            'type' => 1,
            'created_at'    =>now(),
            'updated_at' => now()
        ]);
    }
}
