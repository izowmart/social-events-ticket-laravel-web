<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('merchantTransactionID');
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events');
            $table->integer('ticket_customer_id')->unsigned();
            $table->foreign('ticket_customer_id')->references('id')->on('ticket_customers');
            $table->string('amount');
//            $table->integer('vip_quantity');
//            $table->integer('regular_quantity');
            $table->integer('payment_request_status')->default(0)->comment('0: pending, 1: accepted, 2: declined');
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
        Schema::dropIfExists('payment_requests');
    }
}
