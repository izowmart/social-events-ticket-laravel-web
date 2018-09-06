<?php

use App\EventOrganizer;
use App\Scanner;
use Illuminate\Database\Seeder;

class ScannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event_organizer = EventOrganizer::all()->first();

        Scanner::create([
            'first_name' => 'John',
            'last_name' => 'Scanner',
            'email' =>'johnscanner@gmail.com',
            'password'=>bcrypt('password'),
            'event_organizer_id'=> $event_organizer->id,
            'created_at'    =>now(),
            'updated_at' => now(),
        ]);
    }
}
