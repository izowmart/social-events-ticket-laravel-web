<?php

use Illuminate\Database\Seeder;
use App\TicketCustomer;

class TicketCustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketCustomer::create([
            'first_name'  => 'Johnny',
            'last_name'  => 'Doe',
            'email'  => 'johnnydoe@gmail.com',
            'phone_number'  => '0712525350',
            'source'  => '1',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        TicketCustomer::create([
            'first_name'  => 'Peter',
            'last_name'  => 'test',
            'email'  => 'pettertest@gmail.com',
            'phone_number'  => '0715977451',
            'source'  => '1',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        TicketCustomer::create([
            'first_name'  => 'Allan',
            'last_name'  => 'test',
            'email'  => 'allantest@gmail.com',
            'phone_number'  => '07067851421',
            'source'  => '2',
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );
    }
}
