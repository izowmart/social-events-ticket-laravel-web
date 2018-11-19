<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_organizer_id')->unsigned();
            $table->foreign('event_organizer_id')->references('id')->on('event_organizers');
            $table->string('name');
            $table->text('description');
            $table->string('location');
            $table->decimal('latitude',8,6);
            $table->decimal('longitude',8,6);
            $table->integer('type')->comment("1: Free,2: Paid");
            $table->string('media_url');
            $table->datetime('ticket_sale_end_date')->nullable();
            $table->integer('status')->default(0)->comment('0- unverified, 1 - verified, 2 - deactivated, 3 - draft');
            $table->integer('featured_event')->default(2)->comment('1 - yes, 2 - no');
            $table->integer('ticket_template')->nullable();
            $table->string('slug');
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
        Schema::dropIfExists('events');
    }
}
