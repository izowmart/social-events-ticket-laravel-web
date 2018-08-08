<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('venue_id')->unsigned();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->integer('media_type')->comment("1: Image,2: Video");
            $table->string('media_url');
            $table->text('comment');
            $table->boolean('anonymous');
            $table->integer('type')->comment("1: Everyone,2: Venue Profile,3: Friends");
            $table->boolean('shared');
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
        Schema::dropIfExists('posts');
    }
}
