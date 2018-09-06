<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('year_of_birth');
            $table->integer('gender')->comment("1: Male, 2: Female");
            $table->string('image_url');
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('fcm_token');
            $table->boolean('auto_follow_status')->default(true);
            $table->string('app_version_code');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
