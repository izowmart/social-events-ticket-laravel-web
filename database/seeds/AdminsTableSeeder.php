<?php

use App\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'info@fikaplaces.com',
            'password'   => bcrypt('1nfoFIKAplac3s')
        ]);
    }
}
