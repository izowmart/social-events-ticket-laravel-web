<?php

use App\Http\Resources\ScannerResource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdminsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(EventOrganizerSeeder::class);
         $this->call(ScannersTableSeeder::class);
         $this->call(TownsTableSeeder::class);
         $this->call(VenuesTableSeeder::class);
    }
}
