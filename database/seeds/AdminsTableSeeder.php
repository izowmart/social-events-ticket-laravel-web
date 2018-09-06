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
            'first_name' => 'John',
            'last_name'  => 'Admin',
            'email'      => 'johdoe@gmail.com',
            'password'   => bcrypt('password')
        ]);
    }
}
