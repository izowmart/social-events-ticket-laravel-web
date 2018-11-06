<?php

use App\Notification;
use Illuminate\Database\Seeder;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::create([
            'initializer_id' => '1',
            'recipient_id'   =>  '001',
            'type'           =>  '4',
            'model_id'       =>   '1',
            'seen'           =>   '0'

        ]);
    }
}
