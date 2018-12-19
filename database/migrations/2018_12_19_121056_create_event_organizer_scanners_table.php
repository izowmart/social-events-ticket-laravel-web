<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventOrganizerScannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_organizer_scanners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_organizer_id')->unsigned();
            $table->foreign('event_organizer_id')->references('id')->on('event_organizers');
            $table->integer('scanner_id')->unsigned();
            $table->foreign('scanner_id')->references('id')->on('scanners');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_organizer_scanners');
    }
}
