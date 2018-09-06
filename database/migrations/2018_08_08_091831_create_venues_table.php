<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('town_id')->unsigned();
            $table->foreign('town_id')->references('id')->on('towns');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('contact_person_name');
            $table->string('contact_person_phone');
            $table->string('contact_person_email');
            $table->integer('status')->default(1)->comment('0- inactive, 1 - active, 2 - deactivated');
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
        Schema::dropIfExists('venues');
    }
}
