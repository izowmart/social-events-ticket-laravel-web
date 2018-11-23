<?php

use Illuminate\Database\Seeder;
use App\Ticket;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::create([
            'ticket_customer_id'  => 2,
            'event_id'  => 5,
            'validation_token'  => 'HSDJKSK54S5D5S',
            'qr_code_image_url'  => 'qr.jpg',
            'pdf_format_url'  => 'ticket.pdf',
            'ticket_category_id' => 2,
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );

        Ticket::create([
            'ticket_customer_id'  => 4,
            'event_id'  => 5,
            'validation_token'  => 'HSDJKSK54S5D5S',
            'qr_code_image_url'  => 'qr.jpg',
            'pdf_format_url'  => 'ticket.pdf',
            'ticket_category_id' => 2,
            'created_at'    =>now(),
            'updated_at' => now()
        ]
        );
    }
}
