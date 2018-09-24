<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCategoryPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_category_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_ticket_category_id')->unsigned();
            $table->foreign('event_ticket_category_id')->references('id')->on('event_ticket_categories');
            $table->integer('price');
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
        Schema::dropIfExists('ticket_category_prices');
    }
}
