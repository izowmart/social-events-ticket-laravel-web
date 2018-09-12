<?php

use Illuminate\Database\Seeder;
use App\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'event_organizer_id' => 1,
            'name'  => 'Test event one',
            'description'      => 'No real description here!',
            'location'   => 'Hilton hotel, Nairobi',
            'type'  => '2',
            'status' => '1',
            'created_at'    =>now(),
            'updated_at' => now()
        ]);

        Event::create([
            'event_organizer_id' => 1,
            'name'  => 'Test event two',
            'description'      => 'No real description here too!',
            'location'   => 'Uhuru park, Nairobi',
            'type'  => '1',
            'status' => '0',
            'created_at'    =>now(),
            'updated_at' => now()
        ]);

        Event::create([
            'event_organizer_id' => 1,
            'name'  => 'Test event three',
            'description'      => 'No real description here again too!',
            'location'   => 'Uhuru park, Nairobi',
            'type'  => '1',
            'status' => '1',
            'created_at'    =>now(),
            'updated_at' => now()
        ]);
    }
}
