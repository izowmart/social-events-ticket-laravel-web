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
        $country = Country::create([
            'name' => 'Kenya',
        ]);

        User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'username'   => 'johndoe',
            'email'      => 'johnnjoroge40@gmail.com',
            'year_of_birth' => '1990',
            'gender'     => 1,
            'image_url' => '5b903c5022bc21536179280.jpg',
            'country_id' => $country->id,
            'fcm_token' => 0,
            'auto_follow_status' => 1,
            'app_version_code' => '1.0.0',
            'password'   => bcrypt('password'),
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);
    }
}
