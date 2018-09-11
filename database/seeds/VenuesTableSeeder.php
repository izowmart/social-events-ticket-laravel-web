<?php

use App\Town;
use App\Venue;
use Illuminate\Database\Seeder;

class VenuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Venue::create(
            [
                'name' => 'The Venue',
                'town_id'=> Town::all()->first()->id,
                'latitude' => '0.37',
                'longitude' => '37.45',
                'contact_person_name'  => 'John Contact',
                'contact_person_phone' => '254711110128',
                'contact_person_email'  => 'johncontact@gmail.com',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
    }
}
