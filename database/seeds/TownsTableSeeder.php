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
    }
}
