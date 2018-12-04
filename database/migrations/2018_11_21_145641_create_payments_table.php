<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * [{"payerTransactionID":"dev-test-1542957431","MSISDN":254711110128,
     * "accountNumber":"123456",
     * "customerName":"Customer","amountPaid":1800,"payerClientCode":"SAFKE",
     * "cpgTransactionID":10381981,
     * "paymentDate":"2018-11-23 13:17:24","clientName":"Safaricom Limited",
     * "clientDisplayName":"MPesa",
     * "currencyCode":"KES","currencyID":70,"paymentID":746207,
     * "hubOverallStatus":139}]
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_request_id')->unsigned();
            $table->foreign('payment_request_id')->references('id')->on('payment_requests');
            $table->string('payerTransactionID');
            $table->string("MSISDN");
            $table->string("accountNumber");
            $table->string("customerName");
            $table->double("amountPaid",10,2);
            $table->string("payerClientCode");
            $table->integer("cpgTransactionID");
            $table->dateTime("paymentDate");
            $table->string("clientName");
            $table->string("clientDisplayName");
            $table->string("currencyCode");
            $table->integer("currencyID");
            $table->integer("paymentID");
            $table->integer("hubOverallStatus");
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
        Schema::dropIfExists('payments');
    }
}
