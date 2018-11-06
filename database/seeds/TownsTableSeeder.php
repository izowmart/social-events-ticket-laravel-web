<?php

use App\Country;
use App\Town;
use Illuminate\Database\Seeder;

class TownsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Town::create([
            'name'  => 'Nairobi',
            'country_id'    => Country::all()->first()->id
        ]
        );

        Town::create([
            'name'  => 'Lagos',
            'country_id' => 5
        ]
        );

        Town::create([
            'name'  => 'Nakuru',
            'country_id' => 1
        ]
        );

        Town::create([
            'name'  => 'Kisumu',
            'country_id' => 1
        ]
        );

        Town::create([
            'name'  => 'Arusha',
            'country_id' => 3
        ]
        );

        Town::create([
            'name'  => 'Kampala',
            'country_id' => 2
        ]
        );

        Town::create([
            'name'  => 'Tororo',
            'country_id' => 2
        ]
        );
    }
}
