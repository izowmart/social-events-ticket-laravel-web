<?php

use Illuminate\Database\Seeder;
use App\TicketCategory;

class TicketCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketCategory::create([
            'name'  => 'Vip',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        TicketCategory::create([
            'name'  => 'Regular',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );
    }
}
