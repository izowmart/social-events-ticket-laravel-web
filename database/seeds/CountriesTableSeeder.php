<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name'  => 'Kenya',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        Country::create([
            'name'  => 'Uganda',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        Country::create([
            'name'  => 'Tanzania',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        Country::create([
            'name'  => 'Ethiopia',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        Country::create([
            'name'  => 'Nigeria',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );
    }
}
