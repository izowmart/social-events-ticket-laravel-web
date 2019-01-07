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
            $table->text('comment')->nullable();
            $table->boolean('anonymous')->default(false)->comment('this is whether the creator would like to be anonymous or not');
            $table->integer('type')->comment("1: Everyone,2: Friends,3: Venue");//1:everyone 2:friends 3: venue
//            $table->boolean('shared')->default(false)->comment('is this post an original or a shared one');
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
        Schema::dropIfExists('posts');
    }
}
