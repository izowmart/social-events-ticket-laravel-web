<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('initializer_id')->unsigned();
            $table->foreign('initializer_id')->references('id')->on('users');
            $table->integer('recipient_id')->unsigned();
            $table->foreign('recipient_id')->references('id')->on('posts');
            $table->integer('type')->comment("1: Like,2: Share,3: Follow,4: Follow Request");
            $table->text('message');
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
        Schema::dropIfExists('notifications');
    }
}
