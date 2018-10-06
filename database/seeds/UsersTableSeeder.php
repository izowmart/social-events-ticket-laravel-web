<?php

use App\Country;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'username'   => 'johndoe',
            'email'      => 'johnnjoroge@gmail.com',
            'phone_number' => '+254798541235',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 1,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);

        User::create([
            'first_name' => 'Sam',
            'last_name'  => 'Test',
            'username'   => 'samtest',
            'email'      => 'sam@muva.co.ke',
            'phone_number' => '+254798542175',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 1,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);

        User::create([
            'first_name' => 'Peter',
            'last_name'  => 'Test',
            'username'   => 'petertest',
            'email'      => 'peter@muva.co.ke',
            'phone_number' => '+254799546145',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 2,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>'2018-09-23 11:27:35',
            'updated_at' =>'2018-09-23 10:27:35',
        ]);

        User::create([
            'first_name' => 'James',
            'last_name'  => 'Nandi',
            'username'   => 'jemotest',
            'email'      => 'jemo@muva.co.ke',
            'phone_number' => '+254369544884',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 2,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>'2018-09-23 11:27:35',
            'updated_at' =>'2018-09-23 10:27:35',
        ]);

        User::create([
            'first_name' => 'Allan',
            'last_name'  => 'Test',
            'username'   => 'allantest',
            'email'      => 'allan@muva.co.ke',
            'phone_number' => '+2547112492796',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 2,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>'2018-09-24 10:27:35',
            'updated_at' =>'2018-09-21 10:27:35',
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name'  => 'Test',
            'username'   => 'janetest',
            'email'      => 'janen@muva.co.ke',
            'phone_number' => '+254712542154',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 3,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>'2018-09-21 10:27:35',
            'updated_at' =>'2018-09-21 10:27:35',
        ]);

        User::create([
            'first_name' => 'Liz',
            'last_name'  => 'Test',
            'username'   => 'liztest',
            'email'      => 'lizn@muva.co.ke',
            'phone_number' => '+254714566155',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'profile_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => 4,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>'2018-09-26 10:27:35',
            'updated_at' =>'2018-09-26 10:27:35',
        ]);
    }
}
